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

	const DEVLOG_OK     = -1;
	const DEVLOG_NOTICE = 1;

	public $prefixId = 'tx_egovapi_pi2';

	/**
	 * @var t3lib_cache_frontend_VariableFrontend
	 */
	protected $ajaxCache;

	/**
	 * @var array
	 */
	public $conf;

	/**
	 * @var boolean
	 */
	protected $debug;

	/**
	 * Default action.
	 *
	 * @return array
	 */
	public function main() {
		$this->initTSFE();
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->init();

		if (TYPO3_UseCachingFramework) {
			$this->initializeCache();
		}

		$community = NULL;
		if (t3lib_div::_GET('action') !== 'nearest') {
			if ($this->conf['eCHcommunityID']) {
				/** @var tx_egovapi_domain_repository_communityRepository $communityRepository */
				$communityRepository = tx_egovapi_domain_repository_factory::getRepository('community');
				$community = $communityRepository->findById($this->conf['eCHcommunityID']);
			}
			if (!$community) {
				throw new Exception('Invalid community "' . $this->conf['eCHcommunityID'] . '"', 1306143897);
			}
		}

		$action = t3lib_div::_GET('action');
		switch ($action) {
			case 'organizations':
				$data = $this->getOrganizations($community);
				break;
			case 'services':
				$data = $this->getServices();
				break;
			case 'versions':
				$data = $this->getVersions();
				break;
			case 'url':
				$data = $this->getParametrizedUri();
				break;
			case 'nearest':
				$data = $this->getNearestOrganization();
				break;
			default:
				throw new Exception('Invalid action ' . t3lib_div::_GET('action'), 1306143638);
		}

			// Hook for post-processing the data
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['ajaxHook'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['ajaxHook'] as $classRef) {
				/** @var tx_egovapi_interfaces_ajaxHook $hookObject */
				$hookObject = t3lib_div::getUserObj($classRef);

				if (!($hookObject instanceof tx_egovapi_interfaces_ajaxHook)) {
					throw new UnexpectedValueException('$hookObject must implement interface tx_egovapi_interfaces_ajaxHook', 1308590835);
				}

				$hookObject->postProcessData($action, $data, $this);
			}
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
			$item = array(
				'id' => $organization->getId(),
				'name' => $organization->getName(),
			);
			if (isset($this->conf['coordinates']) && (bool)$this->conf['coordinates']) {
				$item['latitude'] = $organization->getLatitude();
				$item['longitude'] = $organization->getLongitude();
			}
			$data[] = $item;
		}

		return $data;
	}

	/**
	 * Gets the available services.
	 *
	 * @return array
	 */
	protected function getServices() {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'            => 'getServices',
			'includeCHServices' => $this->conf['includeCHServices'],
			'language'          => $this->conf['eCHlanguageID'],
			'community'         => $this->conf['eCHcommunityID'],
			'organization'      => $this->conf['organizationID'],
		));
		$data = $this->getCacheData($cacheKey);
		if ($data) {
			return $data;
		}
		$data = array();
		$services = $this->getDomainServices();

		foreach ($services as $service) {
			$data[] = array(
				'id' => $service->getId(),
				'provider' => $service->getProvider(),
				'version' => $service->getVersionId(),
				'name' => $service->getName(),
			);
		}

		$tags = array(
			sprintf('ajax-community-%s', $this->conf['eCHcommunityID']),
			strtoupper($this->conf['eCHlanguageID']),
		);
		$this->storeCacheData($cacheKey, $data, $tags);

		return $data;
	}

	/**
	 * Gets the available versions of a given service.
	 *
	 * @return array
	 */
	protected function getVersions() {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'            => 'getVersions',
			'includeCHServices' => $this->conf['includeCHServices'],
			'language'          => $this->conf['eCHlanguageID'],
			'community'         => $this->conf['eCHcommunityID'],
			'organization'      => $this->conf['organizationID'],
			'service'           => $this->conf['service'],
		));
		$data = $this->getCacheData($cacheKey);
		if ($data) {
			return $data;
		}
		$data = array();
		$services = $this->getDomainServices();

		foreach ($services as $service) {
			if ($service->getId() === $this->conf['service']) {
				$versions = $service->getVersions();
				foreach ($versions as $version) {
					$data[] = array(
						'id' => $version->getId(),
						'name' => $version->getName(),
						'status' => $version->getStatus(),
						'is_default' => $version->isDefault() ? '1' : '0',
					);
				}
				break;
			}
		}

		$tags = array(
			'ajax',
			sprintf('service-%s', $this->conf['service']),
			strtoupper($this->conf['eCHlanguageID']),
		);
		$this->storeCacheData($cacheKey, $data, $tags);

		return $data;
	}

	/**
	 * Returns available services as domain model objects.
	 *
	 * @param boolean $cache
	 * @return tx_egovapi_domain_model_service[]
	 */
	protected function getDomainServices($cache = TRUE) {
		if ($cache) {
			$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
				'method'            => 'getDomainServices',
				'includeCHServices' => $this->conf['includeCHServices'],
				'language'          => $this->conf['eCHlanguageID'],
				'community'         => $this->conf['eCHcommunityID'],
				'organization'      => $this->conf['organizationID'],
			));
			$services = $this->getCacheData($cacheKey);
			if ($services) {
				return $services;
			}
		}

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

			// Sort services by provider and then by name
		tx_egovapi_utility_objects::sort($services, 'provider');
		$providersServices = array();
		foreach ($services as $service) {
			$provider = $service->getProvider();
			if (!isset($providersServices[$provider])) {
				$providersServices[$provider] = array();
			}
			$providersServices[$provider][] = $service;
		}
		$buffer = array();
		foreach ($providersServices as $provider => &$services) {
			tx_egovapi_utility_objects::sort($services, 'name');
			$buffer = array_merge($buffer, $services);
		}
		$services = $buffer;

		if ($cache) {
				// Cache the list of services
			$tags = array(
				sprintf('ajax-community-%s', $this->conf['eCHcommunityID']),
				strtoupper($this->conf['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $services, $tags);
		}

		return $services;
	}

	/**
	 * Gets the parametrized URI's.
	 *
	 * @return array
	 */
	protected function getParametrizedUri() {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'            => 'getServices',
			'includeCHServices' => $this->conf['includeCHServices'],
			'language'          => $this->conf['eCHlanguageID'],
			'community'         => $this->conf['eCHcommunityID'],
			'organization'      => $this->conf['organizationID'],
			'GET'               => t3lib_div::_GET(),
			'parametrizedUrl'   => $this->conf['parametrizedUrl'],
			'parametrizedUrl.'  => $this->conf['parametrizedUrl.'],
		));
		$data = $this->getCacheData($cacheKey);
		if ($data) {
			return $data;
		}

		$providersDataServices = array();
		$services = $this->getDomainServices();
		foreach ($services as $service) {
			$dataService = NULL;
			$provider = $service->getProvider();
			if ($this->conf['service']) {
				if ($service->getId() === $this->conf['service']) {
					$dataService = $this->extractData($service);
						// Override version with the one given as parameter
					$dataService['versionId'] = $this->conf['version'];
				}
			} else {
				$dataService = $this->extractData($service);
			}
			if ($dataService) {
				if (!isset($providersDataServices[$provider])) {
					$providersDataServices[$provider] = array();
				}
				$providersDataServices[$provider][] = $dataService;
			}
		}

		$data = array();
		foreach ($providersDataServices as $provider => $dataServices) {
			foreach ($dataServices as $dataService) {
				$this->cObj->start($dataService);
				$url = $this->cObj->cObjGetSingle($this->conf['parametrizedUrl'], $this->conf['parametrizedUrl.']);
					// "," looks better than "%2C" in generated URL
				$url = str_replace('%2C', ',', $url);
				$data[] = array(
					'provider' => $provider,
					'url' => $url,
				);
			}
		}

			// Backup configuration
		$backupConfig = $this->conf;

			// Change cache lifetime
		$this->conf['cacheLifetime'] = 7200;	// 2 hours

		$this->storeCacheData($cacheKey, $data, array('ajax-url'));

			// Restore configuration
		$this->conf = $backupConfig;

		return $data;
	}

	/**
	 * Get the nearest organization.
	 *
	 * @return array
	 */
	protected function getNearestOrganization() {
		$data = array();
		$shortestDistance = 9999;
		/** @var $nearestOrganization tx_egovapi_domain_model_organization */
		$nearestOrganization = NULL;

		if ($this->conf['latitude'] && $this->conf['longitude']) {
			/** @var tx_egovapi_domain_repository_organizationRepository $organizationRepository */
			$organizationRepository = tx_egovapi_domain_repository_factory::getRepository('organization');

			foreach ($organizationRepository->findAll() as $organization) {
				$lat = $organization->getLatitude();
				$lng = $organization->getLongitude();

				if ($lat && $lng) {
					$distance = $this->getSphericalDistance($this->conf['latitude'], $this->conf['longitude'], $lat, $lng);
					if ($distance < $shortestDistance) {
						$shortestDistance = $distance;
						$nearestOrganization = $organization;
					}
				}
			}
		}

		if ($nearestOrganization) {
			$community = $nearestOrganization->getCommunity();
			$data = array(
				'community' => array(
					'id' => $community->getId(),
					'name' => $community->getName(),
				),
				'organization' => array(
					'id' => $nearestOrganization->getId(),
					'name' => $nearestOrganization->getName(),
				),
			);
		}

		return $data;
	}

	/**
	 * Gets the distance between two points using the spherical law of cosines.
	 *
	 * @param float $lat1
	 * @param float $long1
	 * @param float $lat2
	 * @param float $long2
	 * @return float
	 * @see http://www.movable-type.co.uk/scripts/latlong.html
	 */
	protected function getSphericalDistance($lat1, $long1, $lat2, $long2) {
		$lat1  = deg2rad($lat1);
		$long1 = deg2rad($long1);
		$lat2  = deg2rad($lat2);
		$long2 = deg2rad($long2);

		$R = 6371; // km
		$d = acos(
			sin($lat1) * sin($lat2) +
			cos($lat1) * cos($lat2) *
			cos($long2 - $long1)
		) * $R;

		return $d;
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
	 * Gets data from cache (if available).
	 *
	 * @param string $cacheKey
	 * @return array
	 */
	protected function getCacheData($cacheKey) {
		$data = array();
		if ($this->ajaxCache) {
			if ($this->ajaxCache->has($cacheKey)) {
				$data = $this->ajaxCache->get($cacheKey);

				if ($this->debug) {
					t3lib_div::devLog('Cache hit for key "' . $cacheKey . '"', 'egovapi', self::DEVLOG_OK, $data);
				}
			}
		}
		return $data;
	}

	/**
	 * Stores data in cache (if available).
	 *
	 * @param string $cacheKey
	 * @param array $data
	 * @param array $tags
	 * @return void
	 */
	protected function storeCacheData($cacheKey, array $data, array $tags = array()) {
		if ($this->ajaxCache && $data) {
			try {
				$this->ajaxCache->set($cacheKey, $data, $tags, intval($this->conf['cacheLifetime']));
			} catch (t3lib_cache_Exception $e) {
				if ($this->debug) {
					t3lib_div::devLog($e->getMessage(), 'egovapi', self::DEVLOG_NOTICE);
				}
			}
		}
	}

	/**
	 * Initializes the AJAX cache.
	 *
	 * @return void
	 */
	protected function initializeCache() {
		tx_egovapi_utility_cache::initializeCachingFramework();

		try {
			$this->ajaxCache = $GLOBALS['typo3CacheManager']->getCache(
				'egovapi'
			);
		} catch (t3lib_cache_exception_NoSuchCache $e) {
			tx_egovapi_utility_cache::initWebServiceCache();

			$this->ajaxCache = $GLOBALS['typo3CacheManager']->getCache(
				'egovapi'
			);
		}
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
		$this->conf['eCHcommunityID'] = $community;

			// $organization may be empty when retrieving the list of them
		$this->conf['organizationID'] = t3lib_div::_GET('organization');

		if (!$this->conf['wsdlVersion']) {
			throw new Exception('Plugin ' . $this->prefixId . ' is not configured properly!', 1306143131);
		}

		/** @var $dao tx_egovapi_dao_dao */
		$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $this->conf);
		tx_egovapi_domain_repository_factory::injectDao($dao);

		$this->debug = $this->conf['enableDebug'];
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

header('Content-Length: ' . strlen($ajaxData));
header('Content-Type: application/json');

echo $ajaxData;
?>