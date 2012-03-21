<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Xavier Perseguers <xavier@causal.ch>
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
 * Plugin 'eGov API - RDF Generator' for the 'egovapi' extension.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_pi3 extends tx_egovapi_pibase {

	public $prefixId      = 'tx_egovapi_pi3';
	public $scriptRelPath = 'Classes/Controller/Pi3/class.tx_egovapi_pi3.php';

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

		$output = $this->pi_wrapInBaseClass('Output of the RDF Generator');
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


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi3/class.tx_egovapi_pi3.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi3/class.tx_egovapi_pi3.php']);
}

?>