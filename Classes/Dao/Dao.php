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
 * Library to connect to retrieve information from the E-Government web service
 * and caching them to avoid unnecessary traffic.
 *
 * @category    DAO
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_dao_dao implements t3lib_Singleton {

	const DEVLOG_OK     = -1;
	const DEVLOG_NOTICE = 1;

	/**
	 * @var t3lib_cache_frontend_VariableFrontend
	 */
	protected $webServiceCache;

	/**
	 * @var tx_egovapi_dao_webService
	 */
	protected $webService;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var boolean
	 */
	protected $debug;

	/**
	 * Default constructor.
	 *
	 * @param array $settings
	 */
	public function __construct(array $settings) {
		$this->settings = $settings;
		$this->debug = $settings['enableDebug'];

		if (TYPO3_UseCachingFramework) {
			$this->initializeCache();
		}
	}

	/**
	 * Updates the DAO settings.
	 *
	 * @param array $settings
	 * @return void
	 */
	public function updateSettings(array $settings) {
		if ($this->settings['eCHlanguageID'] === $settings['eCHlanguageID']
			&& $this->settings['eCHcommunityID'] === $settings['eCHcommunityID']
			&& $this->settings['organizationID'] === $settings['organizationID']) {

				return;
		}

		$this->settings = $settings;
		$this->webService = null;
	}

	/**
	 * Returns the settings.
	 *
	 * @return array
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * Initializes the web service cache.
	 *
	 * @return void
	 */
	protected function initializeCache() {
		tx_egovapi_utility_cache::initializeCachingFramework();

		try {
			$this->webServiceCache = $GLOBALS['typo3CacheManager']->getCache(
				'egovapi'
			);
		} catch (t3lib_cache_exception_NoSuchCache $e) {
			tx_egovapi_utility_cache::initWebServiceCache();

			$this->webServiceCache = $GLOBALS['typo3CacheManager']->getCache(
				'egovapi'
			);
		}
	}

	/**
	 * Returns the web service DAO.
	 *
	 * @return tx_egovapi_dao_webService
	 */
	protected function getWebService() {
		if (!$this->webService) {
			$this->webService = t3lib_div::makeInstance('tx_egovapi_dao_webService', $this->settings);
		}
		return $this->webService;
	}

	/**
	 * Returns available audiences.
	 *
	 * @return tx_egovapi_domain_model_audience[]
	 */
	public function getAudiences() {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'       => 'audiences',
			'language'     => strtoupper($this->settings['eCHlanguageID']),
			'community'    => $this->settings['eCHcommunityID'],
			'organization' => $this->settings['organizationID'],
		));

		$audiences = $this->getCacheData($cacheKey);
		if (!$audiences) {
			$audiences = $this->getWebService()->getAudiences();

			$tags = array(
				'audiences',
				strtoupper($this->settings['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $audiences, $tags);
		}

		return $audiences;
	}

	/**
	 * Returns the views for a given audience.
	 *
	 * @param string $audienceId
	 * @return array
	 */
	public function getViews($audienceId) {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'       => 'views',
			'audience'     => $audienceId,
			'language'     => strtoupper($this->settings['eCHlanguageID']),
			'community'    => $this->settings['eCHcommunityID'],
			'organization' => $this->settings['organizationID'],
		));

		$views = $this->getCacheData($cacheKey);
		if (!$views) {
			$views = $this->getWebService()->getViews($audienceId);

			$tags = array(
				'views',
				strtoupper($this->settings['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $views, $tags);
		}

		return $views;
	}

	/**
	 * Returns the domains for a given view.
	 *
	 * @param string $viewId
	 * @return array
	 */
	public function getDomains($viewId) {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'       => 'domains',
			'view'         => $viewId,
			'language'     => strtoupper($this->settings['eCHlanguageID']),
			'community'    => $this->settings['eCHcommunityID'],
			'organization' => $this->settings['organizationID'],
		));

		$domains = $this->getCacheData($cacheKey);
		if (!$domains) {
			$domains = $this->getWebService()->getDomains($viewId);

			$tags = array(
				'domains',
				strtoupper($this->settings['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $domains, $tags);
		}

		return $domains;
	}

	/**
	 * Returns the details of a given domain.
	 *
	 * @param string $domainId
	 * @param integer $versionId
	 * @param boolean $isParent
	 * @return array
	 */
	public function getDomainDetails($domainId, $versionId, $isParent) {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'       => 'domainDetails',
			'domain'       => $domainId,
			'version'      => $versionId,
			'isParent'     => $isParent,
			'language'     => strtoupper($this->settings['eCHlanguageID']),
			'community'    => $this->settings['eCHcommunityID'],
			'organization' => $this->settings['organizationID'],
		));

		$details = $this->getCacheData($cacheKey);
		if (!$details) {
			$details = $this->getWebService()->getDomainDetails($domainId, $versionId, $isParent);

			$tags = array(
				'domainDetails',
				strtoupper($this->settings['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $details, $tags);
		}

		return $details;
	}

	/**
	 * Returns the list of topics for a given domain.
	 *
	 * @param string $domainId
	 * @return array
	 */
	public function getTopics($domainId) {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'       => 'topics',
			'domain'       => $domainId,
			'language'     => strtoupper($this->settings['eCHlanguageID']),
			'community'    => $this->settings['eCHcommunityID'],
			'organization' => $this->settings['organizationID'],
		));

		$topics = $this->getCacheData($cacheKey);
		if (!$topics) {
			$topics = $this->getWebService()->getTopics($domainId);

			$tags = array(
				'topics',
				strtoupper($this->settings['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $topics, $tags);
		}

		return $topics;
	}

	/**
	 * Returns the details of a given topic.
	 *
	 * @param string $topicId
	 * @param integer $versionId
	 * @param boolean $isParent
	 * @return array
	 */
	public function getTopicDetails($topicId, $versionId, $isParent) {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'       => 'topicDetails',
			'topic'        => $topicId,
			'version'      => $versionId,
			'isParent'     => $isParent,
			'language'     => strtoupper($this->settings['eCHlanguageID']),
			'community'    => $this->settings['eCHcommunityID'],
			'organization' => $this->settings['organizationID'],
		));

		$details = $this->getCacheData($cacheKey);
		if (!$details) {
			$details = $this->getWebService()->getTopicDetails($topicId, $versionId, $isParent);

			$tags = array(
				'topicDetails',
				strtoupper($this->settings['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $details, $tags);
		}

		return $details;
	}

	/**
	 * Returns the list of services for a given topic.
	 *
	 * @param string $topicId
	 * @return array
	 */
	public function getServices($topicId) {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'       => 'services',
			'topic'        => $topicId,
			'language'     => strtoupper($this->settings['eCHlanguageID']),
			'community'    => $this->settings['eCHcommunityID'],
			'organization' => $this->settings['organizationID'],
		));

		$services = $this->getCacheData($cacheKey);
		if (!$services) {
			$services = $this->getWebService()->getServices($topicId);

			$tags = array(
				'services',
				strtoupper($this->settings['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $services, $tags);
		}

		return $services;
	}

	/**
	 * Returns the details of a given service.
	 *
	 * @param string $serviceId
	 * @param integer $versionId
	 * @return array
	 */
	public function getServiceDetails($serviceId, $versionId) {
		$cacheKey = tx_egovapi_utility_cache::getCacheKey(array(
			'method'       => 'serviceDetails',
			'service'      => $serviceId,
			'version'      => $versionId,
			'language'     => strtoupper($this->settings['eCHlanguageID']),
			'community'    => $this->settings['eCHcommunityID'],
			'organization' => $this->settings['organizationID'],
		));

		$details = $this->getCacheData($cacheKey);
		if (!$details) {
			$details = $this->getWebService()->getServiceDetails($serviceId, $versionId);

			$tags = array(
				'serviceDetails',
				strtoupper($this->settings['eCHlanguageID']),
			);
			$this->storeCacheData($cacheKey, $details, $tags);
		}

		return $details;
	}

	/**
	 * Returns the versions available for a given service.
	 *
	 * @param string $serviceId
	 * @return array
	 */
	public function getVersions($serviceId) {
		return $this->getWebService()->getVersions($serviceId);
	}

	/**
	 * Gets data from cache (if available).
	 *
	 * @param string $cacheKey
	 * @return array
	 */
	protected function getCacheData($cacheKey) {
		$data = array();
		if ($this->webServiceCache) {
			if ($this->webServiceCache->has($cacheKey)) {
				$data = $this->webServiceCache->get($cacheKey);

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
		if ($this->webServiceCache && $data) {
			try {
				$this->webServiceCache->set($cacheKey, $data, $tags, intval($this->settings['cacheLifetime']));
			} catch (t3lib_cache_Exception $e) {
				if ($this->debug) {
					t3lib_div::devLog($e->getMessage(), 'egovapi', self::DEVLOG_NOTICE);
				}
			}
		}
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Dao/Dao.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Dao/Dao.php']);
}

?>