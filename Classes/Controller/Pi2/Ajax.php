<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Xavier Perseguers <xavier@causal.ch>
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

// Exit, if script is called directly (must be included via eID in index_ts.php)
if (!defined('PATH_typo3conf')) {
	die('Script cannot be accessed directly!');
}

/**
 * AJAX controller for the 'egovapi' extension.
 *
 * @category    Ajax
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_controller_pi2_Ajax extends tslib_pibase {

	public $extKey   = 'egovapi';
	public $prefixId = 'tx_egovapi_pi2';

	/**
	 * @var array
	 */
	public $conf;


	/**
	 * Default action.
	 *
	 * @return array
	 */
	public function main() {
		$this->init();

		/** @var tx_egovapi_domain_repository_communityRepository $communityRepository */
		$communityRepository = tx_egovapi_domain_repository_factory::getRepository('community');
		$community = $communityRepository->findById($this->conf['eCHcommunityID']);

		if (!$community) {
			throw new Exception('Invalid community "' . $this->conf['eCHcommunityID'] . '"', 1306143897);
		}

		$data = array();

		switch (t3lib_div::_GET('action')) {
			case 'organizations':
				/** @var tx_egovapi_domain_repository_organizationRepository $organizationRepository */
				$organizationRepository = tx_egovapi_domain_repository_factory::getRepository('organization');

				$organizations = $organizationRepository->findByCommunity($community);
				foreach ($organizations as $organization) {
					$data[] = array(
						'id' => $organization->getId(),
						'name' => $organization->getName(),
					);
				}
				break;
			default:
				throw new Exception('Invalid action ' . t3lib_div::_GET('action'), 1306143638);
		}

		return $data;
	}

	/**
	 * Initializes this eID class.
	 *
	 * @return void
	 */
	protected function init() {
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		tslib_eidtools::connectDB();

			// Initialize plugin configuration
		$pid = t3lib_div::_GET('id');
		if (!$pid) {
			$pid = 0;
		}
		$settings = $this->loadTS($pid);
		$settings = $settings['plugin.'][$this->prefixId . '.'];

			// Initialize default values based on extension TS
		$this->conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		if (!is_array($this->conf)) {
			$this->conf = array();
		}

			// Base configuration is equal to the plugin's TS setup
		$this->conf = t3lib_div::array_merge_recursive_overrule($this->conf, $settings, FALSE, FALSE);

			// Basically process stdWrap over all global parameters
		$this->conf = tx_egovapi_utility_ts::preprocessConfiguration($this->cObj, $this->conf);

			// Define additional parameters for the webservice
		$language = t3lib_div::_GET('language');
		if (!t3lib_div::inList('DE,EN,FR,IT,RM', $language)) {
			throw new Exception('Invalid or missing parameter "language"', 1306143038);
		}
		$this->conf['eCHlanguageID'] = $language;

		$community = t3lib_div::_GET('community');
		if (!$community) {
			throw new Exception('Missing parameter "community"', 1306143047);
		}
		$this->conf['eCHcommunityID'] = $community;

			// $organization may be empty when retrieving the list of them
		$this->conf['organizationID'] = t3lib_div::_GET('organization');

		if (!$this->conf['wsdl']) {
			throw new Exception('Plugin ' . $this->prefixId . ' is not configured properly!', 1306143131);
		}

		/** @var $dao tx_egovapi_dao_dao */
		$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $this->conf);
		tx_egovapi_domain_repository_factory::injectDao($dao);
	}

	/**
	 * Loads the TypoScript configuration for a given page.
	 *
	 * @param integer $pageUid
	 * @return array
	 */
	protected function loadTS($pageUid) {
		/** @var $sysPageObj t3lib_pageSelect */
		$sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sysPageObj->getRootLine($pageUid);
		/** @var $TSObj t3lib_tsparser_ext */
		$TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();
		return $TSObj->setup;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi2/Ajax.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi2/Ajax.php']);
}

/** @var $output tx_egovapi_controller_pi2_Ajax */
$output = t3lib_div::makeInstance('tx_egovapi_controller_pi2_Ajax');

$ret = array(
	'success' => TRUE,
	'data' => array(),
	'errors' => array(),
);
try {
	$ret['data'] = $output->main();
} catch (Exception $e) {
	$ret['success'] = FALSE;
	$ret['errors'][] = 'Error ' . $e->getCode() . ': ' . $e->getMessage();
}

$ajaxData = json_encode($ret);

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate( "D, d M Y H:i:s" ) . 'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Length: ' . strlen($ajaxData));
header('Content-Type: application/json');

echo $ajaxData;
?>