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
	 * @var tx_egovapi_service_latestChangesCleanupTask
	 */
	protected $task;

	/**
	 * @var t3lib_cache_frontend_VariableFrontend
	 */
	protected $webServiceCache;

	/**
	 * Default constructor.
	 *
	 * @param tx_egovapi_service_latestChangesCleanupTask $task
	 */
	public function __construct(tx_egovapi_service_latestChangesCleanupTask $task) {
		$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		$config['data.']['communities'] = 'EXT:egovapi/Resources/Private/Data/communities.csv';

		$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $config);
		tx_egovapi_domain_repository_factory::injectDao($dao);

		$this->task = $task;
	}

	/**
	 * Cleans up deprecated cache entries according to the list
	 * of latest changes in eGov API.
	 *
	 * @return boolean TRUE if cleanup succeeded, otherwise FALSE
	 */
	public function cleanup() {
		// @deprecated: Can be removed when TYPO3 4.5 is not supported anymore
		$version = class_exists('t3lib_utility_VersionNumber')
				? t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version)
				: t3lib_div::int_from_ver(TYPO3_version);
		if ($version < 4006000 && !TYPO3_UseCachingFramework) {
			return TRUE;
		}

			// Initialize the cache
		$this->initializeCache();

		/** @var tx_egovapi_domain_repository_communityRepository $communityRepository */
		$communityRepository = tx_egovapi_domain_repository_factory::getRepository('community');

		if ($this->task->allCommunities) {
			$communities = $communityRepository->findAll();
		} else {
			$communities = array();
			$communities[] = $communityRepository->findById($this->task->community);
		}

		try {
			foreach ($communities as $community) {
				$latestChanges = $communityRepository->getLatestChanges($community, $this->task->lastRun);

				foreach ($latestChanges['domains'] as $domain) {
					$this->cleanupDomain($domain);
				}
				foreach ($latestChanges['topics'] as $topic) {
					$this->cleanupTopic($topic);
				}
				foreach ($latestChanges['services'] as $service) {
					$this->cleanupService($service, $community);
				}
			}
		} catch (RuntimeException $exception) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Cleans up deprecated cache entries related to a given domain.
	 *
	 * @param array $domain
	 * @return void
	 */
	protected function cleanupDomain(array $domain) {
		$tags = array(
			sprintf('domain-%s', $domain['id']),
			sprintf('domain-%s-%s', $domain['id'], $domain['version']),
		);

		$this->flushByTags($tags);
	}

	/**
	 * Cleans up deprecated cache entries related to a given topic.
	 *
	 * @param array $topic
	 * @return void
	 */
	protected function cleanupTopic(array $topic) {
		$tags = array(
			sprintf('topic-%s', $topic['id']),
			sprintf('topic-%s-%s', $topic['id'], $topic['version']),
		);

		$this->flushByTags($tags);
	}

	/**
	 * Cleans up deprecated cache entries related to a given service.
	 *
	 * @param array $service
	 * @param tx_egovapi_domain_model_community $community
	 * @return void
	 */
	protected function cleanupService(array $service, tx_egovapi_domain_model_community $community) {
		$tags = array(
			sprintf('service-%s', $service['id']),
			sprintf('service-%s-%s', $service['id'], $service['version']),
			sprintf('ajax-community-%s', $community->getId()),
		);

		$this->flushByTags($tags);
	}

	/**
	 * Flushes cache entries by a set of tags.
	 *
	 * @param array $tags
	 * @return void
	 */
	protected function flushByTags(array $tags) {
		foreach ($tags as $tag) {
			$this->webServiceCache->flushByTag($tag);
		}
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

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Service/LatestChangesCleanup.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Service/LatestChangesCleanup.php']);
}

?>