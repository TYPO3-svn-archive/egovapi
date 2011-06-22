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

/**
 * This class provides a garbage collector for the web service
 * caching framework.
 *
 * @category    Service
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_service_latestChangesCleanup {

	/**
	 * @var string
	 */
	protected $extKey = 'egovapi';

	/**
	 * Default constructor.
	 */
	public function __construct() {
		$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		$config['data.']['communities'] = 'EXT:egovapi/Resources/Private/Data/communities.csv';

		$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $config);
		tx_egovapi_domain_repository_factory::injectDao($dao);
	}

	/**
	 * Cleans up deprecated cache entries according to the list
	 * of latest changes in eGov API.
	 *
	 * @return boolean TRUE if cleanup succeeded, otherwise FALSE
	 */
	public function cleanup() {
		/** @var tx_egovapi_domain_repository_communityRepository $communityRepository */
		$communityRepository = tx_egovapi_domain_repository_factory::getRepository('community');
		/** @var tx_egovapi_domain_model_community[] $communities */
		$communities = $communityRepository->findAll();

		t3lib_utility_Debug::debug($communities, 'communities');

		return TRUE;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Service/LatestChangesCleanup.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Service/LatestChangesCleanup.php']);
}

?>