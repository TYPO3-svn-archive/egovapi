<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011-2012 Xavier Perseguers <xavier@causal.ch>
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
 * Plugin 'eGov API - Selector Form' for the 'egovapi' extension.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_pi2 extends tx_egovapi_pibase {

	public $prefixId      = 'tx_egovapi_pi2';
	public $scriptRelPath = 'Classes/Controller/Pi2/class.tx_egovapi_pi2.php';

	/**
	 * @var array
	 */
	protected $parameters;

	/**
	 * @var boolean
	 */
	protected $debug;

	/**
	 * @var string
	 */
	protected $template;

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
		$this->pi_USER_INT_obj = 1;	// Configuring so caching is not expected.
		$this->pi_loadLL();

		if (!$this->conf['wsdlVersion']) {
			die('Plugin ' . $this->prefixId . ' is not configured properly!');
		}

		$templateFile = $this->conf['template'];
		$template = $this->cObj->fileResource($templateFile);

		$this->template = $this->cObj->getSubpart($template, '###TEMPLATE###');

		/** @var $utilityConstants tx_egovapi_utility_constants */
		$utilityConstants = t3lib_div::makeInstance('tx_egovapi_utility_constants');

		$markers = array(
			'LABEL_COMMUNITY'    => $this->pi_getLL('header_community'),
			'LABEL_ORGANIZATION' => $this->pi_getLL('header_organization'),
			'LABEL_SERVICE'      => $this->pi_getLL('header_service'),
			'LABEL_VERSION'      => $this->pi_getLL('pi_flexform.versions.useVersion'),
			'LABEL_BLOCKS'       => $this->pi_getLL('pi_flexform.displayBlocks'),
			'LABEL_BLOCK_1'      => $this->pi_getLL('pi_flexform.displayBlocks.LEVEL_INFORMATION'),
			'LABEL_BLOCK_2'      => $this->pi_getLL('pi_flexform.displayBlocks.GENERAL_INFORMATION'),
			'LABEL_BLOCK_3'      => $this->pi_getLL('pi_flexform.displayBlocks.PREREQUISITES'),
			'LABEL_BLOCK_4'      => $this->pi_getLL('pi_flexform.displayBlocks.PROCEDURE'),
			'LABEL_BLOCK_5'      => $this->pi_getLL('pi_flexform.displayBlocks.FORMS'),
			'LABEL_BLOCK_6'      => $this->pi_getLL('pi_flexform.displayBlocks.DOCUMENTS_REQUIRED'),
			'LABEL_BLOCK_7'      => $this->pi_getLL('pi_flexform.displayBlocks.SERVICE_PROVIDED'),
			'LABEL_BLOCK_8'      => $this->pi_getLL('pi_flexform.displayBlocks.FEE'),
			'LABEL_BLOCK_9'      => $this->pi_getLL('pi_flexform.displayBlocks.LEGAL_REGULATION'),
			'LABEL_BLOCK_10'     => $this->pi_getLL('pi_flexform.displayBlocks.DOCUMENTS_OTHER'),
			'LABEL_BLOCK_11'     => $this->pi_getLL('pi_flexform.displayBlocks.REMARKS'),
			'LABEL_BLOCK_12'     => $this->pi_getLL('pi_flexform.displayBlocks.APPROVAL'),
			'LABEL_BLOCK_13'     => $this->pi_getLL('pi_flexform.displayBlocks.CONTACT'),
			'LABEL_TOGGLE_MARKS' => $this->pi_getLL('action_toggle_marks'),
			'LABEL_LANGUAGE'     => $this->pi_getLL('common_language'),
			'LABEL_OK'           => $this->pi_getLL('common_ok'),
			'AJAX_LOADER_LARGE'  => $this->conf['ajaxLoaderLarge'],
			'AJAX_LOADER_SMALL'  => $this->conf['ajaxLoaderSmall'],
			'AJAX_URL'           => $this->pi_getPageLink($GLOBALS['TSFE']->id),
			'SHOW_GOOGLEMAP'     => $this->conf['showGoogleMap'],
			'LANGUAGE'           => t3lib_div::inList('de,en,fr,it,rm', $GLOBALS['TSFE']->lang) ? $GLOBALS['TSFE']->lang : 'de',
		);

		$subparts = array(
			'COMMUNITIES' => $utilityConstants->getCommunities(array('fieldName' => 'tx_egovapi_community')),
		);

		$output = $this->cObj->substituteSubpartArray($this->template, $subparts);
		$output = $this->cObj->substituteMarkerArray($output, $markers, '###|###');

		$output = $this->pi_wrapInBaseClass($output);
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

		if ($this->conf['wsdlVersion']) {
			$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $this->conf);
			tx_egovapi_domain_repository_factory::injectDao($dao);
		}
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi2/class.tx_egovapi_pi2.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi2/class.tx_egovapi_pi2.php']);
}

?>