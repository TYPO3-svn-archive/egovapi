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
class tx_egovapi_controller_pi2_Ajax extends tx_egovapi_pibase {

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
		$this->initTSFE();
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->init();

		/** @var tx_egovapi_domain_repository_communityRepository $communityRepository */
		$communityRepository = tx_egovapi_domain_repository_factory::getRepository('community');
		$community = $communityRepository->findById($this->conf['eCHcommunityID']);

		if (!$community) {
			throw new Exception('Invalid community "' . $this->conf['eCHcommunityID'] . '"', 1306143897);
		}

		switch (t3lib_div::_GET('action')) {
			case 'organizations':
				$data = $this->getOrganizations($community);
				break;
			case 'services':
				$data = $this->getServices();
				break;
			case 'url':
				$data = $this->getParametrizedUri();
				break;
			default:
				throw new Exception('Invalid action ' . t3lib_div::_GET('action'), 1306143638);
		}

		return $data;
	}

	/**
	 * Gets the organizations for a given community.
	 *
	 * @param tx_egovapi_domain_model_community $community
	 * @return array
	 */
	protected function getOrganizations(tx_egovapi_domain_model_community $community) {
		$data = array();

		/** @var tx_egovapi_domain_repository_organizationRepository $organizationRepository */
		$organizationRepository = tx_egovapi_domain_repository_factory::getRepository('organization');

		$organizations = $organizationRepository->findByCommunity($community);

			// Sort organizations by name
		tx_egovapi_utility_objects::sort($organizations, 'name');

		foreach ($organizations as $organization) {
			$data[] = array(
				'id' => $organization->getId(),
				'name' => $organization->getName(),
			);
		}

		return $data;
	}

	/**
	 * Gets the available services.
	 *
	 * @return array
	 */
	protected function getServices() {
		$data = array();
		$services = $this->getDomainServices();

		foreach ($services as $service) {
			$data[] = array(
				'id' => $service->getId(),
				'version' => $service->getVersionId(),
				'name' => $service->getName(),
			);
		}

		return $data;
	}

	/**
	 * Returns available services as domain model objects.
	 *
	 * @param boolean $cache
	 * @return tx_egovapi_domain_model_service[]
	 */
	protected function getDomainServices($cache = TRUE) {
		$services = array();
		$serviceIds = array();

		/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
		$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');

		$audiences = $audienceRepository->findAll($cache);
		foreach ($audiences as $audience) {
			foreach ($audience->getViews($cache) as $view) {
				foreach ($view->getDomains($cache) as $domain) {
					foreach ($domain->getTopics($cache) as $topic) {
						foreach ($topic->getServices() as $service) {
							$serviceId = $service->getId();
							if (!in_array($serviceId, $serviceIds)) {
								$services[] = $service;
								$serviceIds[] = $serviceId;
							}
						}
					}
				}
			}
		}

		if (!count($services) && $this->conf['eCHcommunityID'] !== '00-00' && $this->conf['includeCHServices']) {
				// Community is not yet configured, retry with Confederation itself as community
			$this->conf['eCHcommunityID'] = '00-00';
			tx_egovapi_domain_repository_factory::getDao()->updateSettings($this->conf);

			return $this->getDomainServices(FALSE);
		}

			// Sort services by name
		tx_egovapi_utility_objects::sort($services, 'name');

		return $services;
	}

	/**
	 * Gets the parametrized URI's.
	 *
	 * @return array
	 */
	protected function getParametrizedUri() {
		$dataServices = array();
		$services = $this->getDomainServices();
		foreach ($services as $service) {
			if ($this->conf['service']) {
				if ($service->getId() === $this->conf['service']) {
					$dataService = $this->extractData($service);
						// Override version with the one given as parameter
					$dataService['versionId'] = $this->conf['version'];
					$dataServices[] = $dataService;
				}
			} else {
				$dataServices[] = $this->extractData($service);
			}
		}

		$data = array();
		foreach ($dataServices as $dataService) {
			$this->cObj->start($dataService);
			$url = $this->cObj->cObjGetSingle($this->conf['parametrizedUrl'], $this->conf['parametrizedUrl.']);
				// "+" looks better than "%20" in generated URL
			$url = str_replace('%20', '+', $url);
			$data[] = array('url' => $url);
		}

		return $data;
	}

	/**
	 * Extracts data from a service to be used with cObjects.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return array
	 */
	protected function extractData(tx_egovapi_domain_model_service $service) {
		$data = array(
			'id'               => $service->getId(),
			'name'             => $service->getName(),
			'description'      => $service->getDescription(),
			'versionId'        => $service->getVersionId(),
			'versionName'      => $service->getVersionName(),
			'communityId'      => $service->getCommunityId(),
			'release'          => $service->getRelease(),
			'comments'         => $service->getComments(),
			'provider'         => $service->getProvider(),
			'customer'         => $service->getCustomer(),
			'type'             => $service->getType(),
			'action'           => $service->getAction(),
			'status'           => $service->getStatus(),
			'author'           => $service->getAuthor(),
			'creationDate'     => $service->getCreationDate(),
			'lastModification' => $service->getLastModificationDate(),
		);

		return $data;
	}

	/**
	 * Initializes this eID class.
	 *
	 * @return void
	 */
	protected function init() {
		$settings = $GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->prefixId . '.'];

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
		if (!t3lib_div::inList('de,en,fr,it,rm', $language)) {
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
	 * Initializes TSFE and sets $GLOBALS['TSFE'].
	 *
	 * @return void
	 */
	protected function initTSFE() {
		$GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], t3lib_div::_GP('id'), '');
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->initFEuser();
		$GLOBALS['TSFE']->checkAlternativeIdMethods();
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->getCompressedTCarray();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();

			// Get linkVars, absRefPrefix, etc
		TSpagegen::pagegenInit();
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