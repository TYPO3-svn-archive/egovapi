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
 * Helper functions for TypoScript.
 *
 * @category    Helpers
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_helpers_ts {

	/**
	 * @var string
	 */
	protected static $prefixId = 'tx_egovapi_pi1';

	/**
	 * Processes the global parameters by applying stdWrap if needed.
	 *
	 * @param tslib_cObj $cObj
	 * @param array $settings
	 * @return array
	 */
	public static function preprocessConfiguration(tslib_cObj $cObj, array $settings) {

			// Preprocess the global parameters
		$globalParameters = array(
			'eCHlanguageID', 'eCHcommunityID', 'eCHmunicipalityID',
			'wsdl', 'cacheLifetime', 'useFluid', 'enableDebug', 'showRenderTime',
			'includeCHServices', 'displayLevels', 'level', 'mode',
			'audiences', 'views', 'domains', 'topics', 'services',
		);

		foreach ($globalParameters as $parameter) {
			if (substr($parameter, -1) === '.') {
				$parameter = substr($parameter, 0, strlen($parameter) - 1);
			}
			if (isset($settings[$parameter . '.'])) {
				$settings[$parameter] = $cObj->stdWrap($settings[$parameter], $settings[$parameter . '.']);
				unset($settings[$parameter . '.']);
			}
		}

			// Preprocess the display blocks
		$levelsDisplayBlocks = array('domain', 'topic', 'service');
		foreach ($levelsDisplayBlocks as $level) {
			if (substr($level, -1) === '.') {
				$level = substr($level, 0, strlen($level) - 1);
			}
			if (isset($settings['displayBlocks.'][$level . '.'])) {
				$settings['displayBlocks.'][$level] = $cObj->stdWrap($settings['displayBlocks.'][$level], $settings['displayBlocks.'][$level . '.']);
				unset($settings['displayBlocks.'][$level . '.']);
			}
		}

			// Preprocess the service versions
		foreach ($settings['versions.'] as $serviceId => $value) {
			if (substr($serviceId, -1) === '.') {
				$serviceId = substr($serviceId, 0, strlen($serviceId) - 1);
			}
			if (isset($settings['versions.'][$serviceId . '.'])) {
				$settings['versions.'][$serviceId] = $cObj->stdWrap($settings['versions.'][$serviceId], $settings['versions.'][$serviceId . '.']);
				unset($settings['versions.'][$serviceId . '.']);
			}
		}

		return $settings;
	}

	/**
	 * Processes all configuration options, global settings, TypoScript, flexform values
	 * and local override of TypoScript and merge them all.
	 *
	 * @param array $settings
	 * @param array $parameters
	 * @param array $globalSetup
	 * @return array
	 */
	public static function getMergedConfiguration(array $settings, array $parameters, array $globalSetup) {
			// Business processing of configuration
		$settings = self::processAUDIENCE($settings);
		$settings = self::processVIEW($settings, $parameters);
		$settings = self::processDOMAIN($settings, $parameters);
		$settings = self::processTOPIC($settings, $parameters);
		$settings = self::processSERVICE($settings, $parameters);
		$settings = self::processBlocksOverrides($settings);
		$settings = self::processTemplateOverrides($settings);
		$settings = self::processVersionOverrides($settings);
		$settings = self::processTargetOverrides($settings);

			// Load full setup to allow references to outside definitions in 'myTS'
		//$localSetup = array('plugin.' => array(self::$prefixId . '.' => $settings));
		//$setup = t3lib_div::array_merge_recursive_overrule($globalSetup, $localSetup);

			// Override configuration with TS from FlexForm itself
		$flexFormTypoScript = $settings['myTS'];
		unset($settings['myTS']);
		if ($flexFormTypoScript) {
			require_once(PATH_t3lib . 'class.t3lib_tsparser.php');
			/** @var t3lib_tsparser $tsparser */
			$tsparser = t3lib_div::makeInstance('t3lib_tsparser');

			// WITH GLOBAL CONTEXT [begin]
			/*
			// Copy settings into existing setup
			$tsparser->setup = $setup;
			// Parse the new TypoScript
			// BEWARE: Problem here!!! only first TS line is properly prefixed!!!
			$tsparser->parse('plugin.' . self::$prefixId . '.' . $flexFormTypoScript);
			// Copy the resulting setup back into settings
			$settings = $tsparser->setup['plugin.'][self::$prefixId . '.'];
			*/
			// WITH GLOBAL CONTEXT [end]

			// WITH LOCAL CONTEXT [begin]
			// Copy settings into existing setup
			$tsparser->setup = $settings;
			// Parse the new TypoScript
			$tsparser->parse($flexFormTypoScript);
			// Copy the resulting setup back into settings
			$settings = $tsparser->setup;
			// WITH LOCAL CONTEXT [end]
		}

		return $settings;
	}

	/**
	 * Business initialization when display level = AUDIENCE.
	 *
	 * @param array $settings
	 * @return array
	 */
	protected static function processAUDIENCE(array $settings) {
		$audiences = t3lib_div::intExplode(',', $settings['audiences'], TRUE);
		if (!$audiences) {
			// Explicitly list all audiences, this really will ease sub-level processing

			/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
			$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');
			$allAudiences = $audienceRepository->findAll();
			$audiences = array();
			foreach ($allAudiences as $audience) {
				$audiences[] = $audience->getId();
			}
		}
		$settings['audiences'] = implode(',', $audiences);

		return $settings;
	}

	/**
	 * Business initialization when display level = VIEW.
	 *
	 * @param array $settings
	 * @param array $parameters
	 * @return array
	 */
	protected static function processVIEW(array $settings, array $parameters) {
		$allowedAudiences = t3lib_div::intExplode(',', $settings['audiences'], TRUE);
		$views = t3lib_div::trimExplode(',', $settings['views'], TRUE);

		if (!$views) {
			// Explicitly list all views, this really will ease sub-level processing

			/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
			$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');
			/** @var tx_egovapi_domain_repository_viewRepository $viewRepository */
			$viewRepository = tx_egovapi_domain_repository_factory::getRepository('view');
			$views = array();
			foreach ($allowedAudiences as $id) {
				$audience = $audienceRepository->findById($id);
				if ($audience) {
					$allViews = $viewRepository->findAll($audience);
					foreach ($allViews as $view) {
						$views[] = $view->getId();
					}
				}
			}
		}
		$settings['views'] = implode(',', $views);

		// Set the parent audience
		$audience = isset($parameters['audience']) ? intval($parameters['audience']) : '';
		if ($audience && !t3lib_div::inArray($allowedAudiences, $audience)) {
			$audience = '';
		}
		if (!$audience) {
			// No audience from URL, take it from TS
			$audience = isset($settings['audience']) ? intval($settings['audience']) : '';
		}
		$settings['audience'] = $audience;

		return $settings;
	}

	/**
	 * Business initialization when display level = DOMAIN.
	 *
	 * @param array $settings
	 * @param array $parameters
	 * @return array
	 */
	protected static function processDOMAIN(array $settings, array $parameters) {
		$allowedViews = t3lib_div::trimExplode(',', $settings['views'], TRUE);
		$domains = t3lib_div::trimExplode(',', $settings['domains'], TRUE);
		if (!$domains) {
			// Explicitly list all domains, this really will ease sub-level processing

			/** @var tx_egovapi_domain_repository_viewRepository $viewRepository */
			$viewRepository = tx_egovapi_domain_repository_factory::getRepository('view');
			/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
			$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');
			$domains = array();
			foreach ($allowedViews as $id) {
				$view = $viewRepository->findById($id);
				if ($view) {
					$allDomains = $domainRepository->findAll($view);
					foreach ($allDomains as $domain) {
						$domains[] = $domain->getId();
					}
				}
			}
		}
		$settings['domains'] = implode(',', $domains);

		// Set the parent view
		$view = isset($parameters['view']) ? $parameters['view'] : '';
		if ($view && !t3lib_div::inArray($allowedViews, $view)) {
			$view = '';
		}
		if (!$view) {
			// No view from URL, take it from TS
			$view = isset($settings['view']) ? $settings['view'] : '';
		}
		$settings['view'] = $view;

		return $settings;
	}

	/**
	 * Business initialization when display level = TOPIC.
	 *
	 * @param array $settings
	 * @param array $parameters
	 * @return array
	 */
	protected static function processTOPIC(array $settings, array $parameters) {
		$allowedDomains = t3lib_div::trimExplode(',', $settings['domains'], TRUE);
		$topics = t3lib_div::trimExplode(',', $settings['topics'], TRUE);
		if (!$topics) {
			// Explicitly list all topics, this really will ease sub-level processing

			/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
			$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');
			/** @var tx_egovapi_domain_repository_topicRepository $topicRepository */
			$topicRepository = tx_egovapi_domain_repository_factory::getRepository('topic');
			$topics = array();
			foreach ($allowedDomains as $id) {
				$domain = $domainRepository->findById($id);
				if ($domain) {
					$allTopics = $topicRepository->findAll($domain);
					foreach ($allTopics as $topic) {
						$topics[] = $topic->getId();
					}
				}
			}
		}
		$settings['topics'] = implode(',', $topics);

		// Set the parent domain
		$domain = isset($parameters['domain']) ? $parameters['domain'] : '';
		if ($domain && !t3lib_div::inArray($allowedDomains, $domain)) {
			$domain = '';
		}
		if (!$domain) {
			// No domain from URL, take it from TS
			$domain = isset($settings['domain']) ? $settings['domain'] : '';
		}
		$settings['domain'] = $domain;

		return $settings;
	}

	/**
	 * Business initialization when display level = SERVICE.
	 *
	 * @param array $settings
	 * @param array $parameters
	 * @return array
	 */
	protected static function processSERVICE(array $settings, array $parameters) {
		$allowedTopics = t3lib_div::trimExplode(',', $settings['topics'], TRUE);
		$services = t3lib_div::trimExplode(',', $settings['services'], TRUE);
		if (!$services) {
			// Explicitly list all services, this really will ease sub-level processing

			/** @var tx_egovapi_domain_repository_topicRepository $topicRepository */
			$topicRepository = tx_egovapi_domain_repository_factory::getRepository('topic');
			/** @var tx_egovapi_domain_repository_serviceRepository $serviceRepository */
			$serviceRepository = tx_egovapi_domain_repository_factory::getRepository('service');
			$services = array();
			foreach ($allowedTopics as $id) {
				$topic = $topicRepository->findById($id);
				if ($topic) {
					$allServices = $serviceRepository->findAll($topic);
					foreach ($allServices as $service) {
						$services[] = $service->getId();
					}
				}
			}
		}
		$settings['services'] = implode(',', $services);

		// Set the parent topic
		$topic = isset($parameters['topic']) ? $parameters['topic'] : '';
		if ($topic && !t3lib_div::inArray($allowedTopics, $topic)) {
			$topic = '';
		}
		if (!$topic) {
			// No topic from URL, take it from TS
			$topic = isset($settings['topic']) ? $settings['topic'] : '';
		}
		$settings['topic'] = $topic;

		return $settings;
	}

	/**
	 * Processes override of the blocks to show.
	 *
	 * @param array $settings
	 * @return void
	 */
	protected static function processBlocksOverrides(array $settings) {
		$levels = array('domain', 'topic', 'service');

		foreach ($levels as $level) {
			$key = 'blocks_' . $level;
			$blocks = isset($settings[$key]) ? $settings[$key] : '';
			if ($blocks) {
				$settings['displayBlocks.'][$level] = $blocks;
				unset($settings['displayBlocks.'][$level . '.']);
			}
			unset($settings[$key]);
		}

		return $settings;
	}

	/**
	 * Processes override of the service versions to use.
	 *
	 * @param array $settings
	 * @return void
	 */
	protected static function processVersionOverrides(array $settings) {
		$versions = $settings['versions_flex'];
		if ($versions) {
			$versions = (array) json_decode(str_replace("'", '"', $versions));
		} else {
			$versions = array();
		}

		foreach ($versions as $serviceId => $version) {
			$settings['versions.'][$serviceId] = $version;
		}

		unset($settings['versions_flex']);
		return $settings;
	}

	/**
	 * Processes override of the templates to use.
	 *
	 * @param array $settings
	 * @return array
	 */
	protected static function processTemplateOverrides(array $settings) {
		$levels = array('audience', 'view', 'domain', 'topic', 'service');
		$modes = array('list', 'single');

		foreach ($levels as $level) {
			foreach ($modes as $mode) {
				$key = 'templates_' . $level . strtoupper($mode);
				$template = isset($settings[$key]) ? $settings[$key] : '';
				if ($template) {
					$settings['templates.'][$mode . '.'][$level . '.']['fluid.']['file'] = $template;
					unset($settings['templates.'][$mode . '.'][$level . '.']['fluid.']['file.']);
				}
				unset($settings[$key]);
			}
		}

		return $settings;
	}

	/**
	 * Processes override of the targets to use.
	 *
	 * @param array $settings
	 * @return array
	 */
	protected static function processTargetOverrides(array $settings) {
		$levels = array('audience', 'view', 'domain', 'topic', 'service');
		$modes = array('list', 'single');

		foreach ($levels as $level) {
			foreach ($modes as $mode) {
				$key = 'targets_' . $level . strtoupper($mode);
				$target = isset($settings[$key]) ? $settings[$key] : '';
				if ($target) {
					$settings['targets.'][$level . '.'][$mode . 'Pid'] = intval($target);
					unset($settings['targets.'][$level . '.'][$mode . 'Pid.']);
				}
				unset($settings[$key]);
			}
		}

		return $settings;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Helpers/TypoScript.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Helpers/TypoScript.php']);
}

?>