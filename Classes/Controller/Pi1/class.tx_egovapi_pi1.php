<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 Xavier Perseguers <xavier@causal.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Plugin 'eGov API' for the 'egovapi' extension.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_pi1 extends tx_egovapi_pibase {

	public $prefixId      = 'tx_egovapi_pi1';
	public $scriptRelPath = 'Classes/Controller/Pi1/class.tx_egovapi_pi1.php';

	/**
	 * @var array
	 */
	protected $parameters;

	/**
	 * @var boolean
	 */
	protected $debug;

	/**
	 * Main function.
	 *
	 * @param string $content: The Plugin content
	 * @param array $conf: The Plugin configuration
	 * @return string Content which appears on the website
	 */
	public function main($content, array $conf) {
		$this->init($conf);
		$this->pi_setPiVarDefaults();
		$useCaching = !$this->conf['dynamicConfig'];
		$this->pi_USER_INT_obj = $useCaching ? 0 : 1;

		if (!$useCaching && $this->cObj->getUserObjectType() == tslib_cObj::OBJECTTYPE_USER) {
			$this->cObj->convertToUserIntObject();
			return '';
		}

		$this->pi_loadLL();

		if (!$this->conf['wsdl']) {
			die('Plugin ' . $this->prefixId . ' is not configured properly!');
		}

		if ($this->debug) {
			if (t3lib_div::int_from_ver(TYPO3_version) < 4005000) {
				t3lib_div::debug($this->conf, 'Settings of ' . $this->prefixId);
			} else {
				t3lib_utility_Debug::debug($this->conf, 'Settings of ' . $this->prefixId);
			}
		}

		$start = microtime(TRUE);
		if ($this->conf['useFluid']) {
			$renderer = t3lib_div::makeInstance('tx_egovapi_controller_pi1_fluidRenderer');
		} else {
			$renderer = t3lib_div::makeInstance('tx_egovapi_controller_pi1_templateRenderer');
		}

		/** @var tx_egovapi_controller_pi1_abstractRenderer $renderer */
		$renderer->initialize($this, $this->conf, $this->parameters);
		$content = $renderer->render();
		$end = microtime(TRUE) - $start;

		if ($this->conf['showRenderTime']) {
			$content .= '<!-- ' . $this->prefixId . ' rendered in ' . $end . ' sec -->';
		}

		// Wrap the whole result, with baseWrap if defined, else with standard pi_wrapInBaseClass() call
		if (isset($this->conf['baseWrap.'])) {
			$output = $this->cObj->stdWrap($content, $this->conf['baseWrap.']);
		}
		else {
			$output = $this->pi_wrapInBaseClass($content);
		}

		return $output;
	}

	/**
	 * This method performs various initializations.
	 *
	 * @param array $settings: Plugin configuration, as received by the main() method
	 * @return void
	 */
	protected function init(array $settings) {
			// Initialize default values based on extension TS
		$this->conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		if (!is_array($this->conf)) {
			$this->conf = array();
		}

			// Base configuration is equal to the plugin's TS setup
		$this->conf = t3lib_div::array_merge_recursive_overrule($this->conf, $settings, FALSE, FALSE);

			// Basically process stdWrap over all global parameters
		$this->conf = tx_egovapi_utility_ts::preprocessConfiguration($this->cObj, $this->conf);

			// Load the flexform and loop on all its values to override TS setup values
			// Some properties use a different test (more strict than not empty) and yet some others no test at all
			// see http://wiki.typo3.org/index.php/Extension_Development,_using_Flexforms
		$this->pi_initPIflexForm(); // Init and get the flexform data of the plugin

			// Assign the flexform data to a local variable for easier access
		$piFlexForm = $this->cObj->data['pi_flexform'];

		if (is_array($piFlexForm['data'])) {
			$multiValueKeys = array('audiences', 'views', 'domains', 'topics', 'services');
				// Traverse the entire array based on the language
				// and assign each configuration option to $this->settings array...
			foreach ($piFlexForm['data'] as $sheet => $data) {
				foreach ($data as $lang => $value) {
					foreach ($value as $key => $val) {
						$value = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
						if (trim($value) !== '' && in_array($key, $multiValueKeys)) {
							// Funny, FF contains a comma-separated list of key|value and
							// we only want to have key...
							$tempValues = explode(',', $value);
							$tempKeys = array();
							foreach ($tempValues as $tempValue) {
								list($k, $v) = explode('|', $tempValue);
								$tempKeys[] = $k;
							}
							$value = implode(',', $tempKeys);
						}
						if (trim($value) !== '' || !isset($this->conf[$key])) {
							$this->conf[$key] = $value;
						}
					}
				}
			}
		}

		$this->parameters = t3lib_div::_GET($this->prefixId);
		if (!is_array($this->parameters)) {
			$this->parameters = array();
		}

			// Fallback configuration
		$allLevels = array('AUDIENCE', 'VIEW', 'DOMAIN', 'TOPIC', 'SERVICE');
		$levels = t3lib_div::trimExplode(',', $this->conf['displayLevels'], TRUE);
		$requestedLevel = '';
		if (isset($this->parameters['action']) && t3lib_div::inArray($allLevels, strtoupper($this->parameters['action']))) {
			$requestedLevel = strtoupper($this->parameters['action']);
		}
		if ($levels) {
			// Static list of levels to show, take first one
			// if requested level is empty or not authorized
			$this->conf['level'] = $levels[0];
			if ($requestedLevel && t3lib_div::inArray($levels, $requestedLevel)) {
				$this->conf['level'] = $requestedLevel;
			}
		} else {
			$this->conf['level'] = $requestedLevel ? $requestedLevel : $allLevels[0];
		}

		$allModes = array('LIST', 'SINGLE');
		$requestedMode = '';
		if (isset($this->parameters['mode']) && t3lib_div::inArray($allModes, strtoupper($this->parameters['mode']))) {
			$requestedMode = strtoupper($this->parameters['mode']);
		}
		if ($this->conf['displayMode']) {
			// mode is forced by configuration
			$this->conf['mode'] = $this->conf['displayMode'];
		} else {
			$this->conf['mode'] = $requestedMode ? $requestedMode : $allModes[0];
		}

			// singlePid and listPid
		$levels = array('audience', 'view', 'domain', 'topic', 'service');
		$keys = array('listPid', 'singlePid');
		foreach ($levels as $level) {
			foreach ($keys as $key) {
				if (isset($this->conf['targets.'][$level . '.'][$key . '.'])) {
					$this->conf['targets.'][$level . '.'][$key] = $this->cObj->stdWrap(
						$this->conf['targets.'][$level . '.'][$key],
						$this->conf['targets.'][$level . '.'][$key . '.']
					);
				}
				if (!$this->conf['targets.'][$level . '.'][$key]) {
					$this->conf['targets.'][$level . '.'][$key] = $GLOBALS['TSFE']->id;
				}
			}
		}

			// Blocks to show
		foreach ($this->conf['displayBlocks.'] as $type => &$blocks) {
				// Legacy support for spaces instead of commas
			$blocks = str_replace(' ', ',', $blocks);

			if (preg_match('/^[0-9,-]+/', $blocks)) {
					// Blocks as number, update configuration to use corresponding names
				$blocks = t3lib_div::expandList($blocks);
				$blockNumbers = t3lib_div::intExplode(',', $blocks);
				$temp = array();
				foreach ($blockNumbers as $blockNumber) {
					$temp[] = tx_egovapi_utility_constants::getBlockName($blockNumber);
				}
				$blocks = implode(',', $temp);
			}
		}

		if ($this->conf['wsdl']) {
			$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $this->conf);
			tx_egovapi_domain_repository_factory::injectDao($dao);
		}

			// Merge configuration with business logic and local override TypoScript (myTS)
		$this->conf = tx_egovapi_utility_ts::getMergedConfiguration($this->conf, $this->parameters, $GLOBALS['TSFE']->tmpl->setup);

		if ($this->conf['wsdl']) {
			$dao = tx_egovapi_domain_repository_factory::getDao();
			$dao->updateSettings($this->conf);
		}

		$stripTags = isset($this->conf['stripTags']) ? $this->conf['stripTags'] : FALSE;
		tx_egovapi_domain_repository_factory::setStripTags($stripTags);

			// Fluid templates are only available since TYPO3 4.5
		if (t3lib_div::int_from_ver(TYPO3_version) < 4005000) {
			$this->conf['useFluid'] = 0;
		}

		$dynamicConfig = isset($this->conf['dynamicConfig']) ? (bool) $this->conf['dynamicConfig'] : FALSE;
		$this->conf['dynamicConfig'] = $dynamicConfig;

		if ($dynamicConfig && $this->conf['version']) {
			if (!is_array($this->conf['versions.'])) {
				$this->conf['versions.'] = array();
			}
			$this->conf['versions.'][$this->conf['services']] = $this->conf['version'];
			unset($this->conf['version']);
		}

		$this->debug = $this->conf['enableDebug'];
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi1/class.tx_egovapi_pi1.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi1/class.tx_egovapi_pi1.php']);
}

?>