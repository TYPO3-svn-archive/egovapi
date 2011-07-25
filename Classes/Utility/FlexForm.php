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
 * Flexform helper functions.
 *
 * @category    Utility
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_utility_flexform {

	/**
	 * @var string
	 */
	protected static $extKey = 'egovapi';

	/**
	 * @var string
	 */
	protected static $prefixId = 'tx_egovapi_pi1';

	/**
	 * @var array
	 */
	protected static $globalTS;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var tx_egovapi_dao_dao
	 */
	protected $dao;

	/**
	 * Returns the list of available audiences.
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getAudiences(array $settings) {
		$this->initialize($settings);

		if ($this->dao) {
			/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
			$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');
			$audiences = $audienceRepository->findAll();

			$items = array();
			foreach ($audiences as $audience) {
				$items[] = array(sprintf('%s - %s', $audience->getId(), $audience->getName()), $audience->getId());
			}
			$settings['items'] = array_merge($settings['items'], $items);
		}
		return $settings;
	}

	/**
	 * Returns the list of available views.
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getViews(array $settings) {
		$this->initialize($settings);

		if ($this->dao && $this->settings['audiences']) {
			/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
			$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');
			/** @var tx_egovapi_domain_repository_viewRepository $viewRepository */
			$viewRepository = tx_egovapi_domain_repository_factory::getRepository('view');

			$allViews = array();
			$ids = t3lib_div::intExplode(',', $this->settings['audiences'], TRUE);
			foreach ($ids as $id) {
				$audience = $audienceRepository->findById($id);
				if (!$audience) {
					continue;
				}

				$views = $viewRepository->findAll($audience);
				foreach ($views as $view) {
					$allViews[] = $view;
				}
			}

			// Sort by view name
			tx_egovapi_utility_objects::sort($allViews, 'name');

			$items = array();
			foreach ($allViews as $view) {
				$items[] = array(sprintf('%s - %s', $view->getId(), $view->getName()), $view->getId());
			}
			$settings['items'] = array_merge($settings['items'], $items);
		}
		return $settings;
	}

	/**
	 * Returns the list of available domains.
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getDomains(array $settings) {
		$this->initialize($settings);

		if ($this->dao && $this->settings['views']) {
			/** @var tx_egovapi_domain_repository_viewRepository $viewRepository */
			$viewRepository = tx_egovapi_domain_repository_factory::getRepository('view');
			/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
			$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');

			$allDomains = array();
			$ids = t3lib_div::trimExplode(',', $this->settings['views'], TRUE);
			foreach ($ids as $id) {
				$view = $viewRepository->findById($id);
				if (!$view) {
					continue;
				}

				$domains = $domainRepository->findAll($view);
				foreach ($domains as $domain) {
					$allDomains[] = $domain;
				}
			}

			// Sort by domain name
			tx_egovapi_utility_objects::sort($allDomains, 'name');

			$items = array();
			foreach ($allDomains as $domain) {
				$items[] = array(sprintf('%s - %s', $domain->getId(), $domain->getName()), $domain->getId());
			}
			$settings['items'] = array_merge($settings['items'], $items);
		}

		return $settings;
	}

	/**
	 * Returns the list of available topics.
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getTopics(array $settings) {
		$this->initialize($settings);

		if ($this->dao && $this->settings['domains']) {
			/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
			$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');
			/** @var tx_egovapi_domain_repository_topicRepository $topicRepository */
			$topicRepository = tx_egovapi_domain_repository_factory::getRepository('topic');

			$allTopics = array();
			$ids = t3lib_div::trimExplode(',', $this->settings['domains'], TRUE);
			foreach ($ids as $id) {
				$domain = $domainRepository->findById($id);
				if (!$domain) {
					continue;
				}

				$topics = $topicRepository->findAll($domain);
				foreach ($topics as $topic) {
					$allTopics[] = $topic;
				}
			}

			// Sort by topic name
			tx_egovapi_utility_objects::sort($allTopics, 'name');

			$items = array();
			foreach ($allTopics as $topic) {
				$items[] = array(sprintf('%s - %s', $topic->getId(), $topic->getName()), $topic->getId());
			}
			$settings['items'] = array_merge($settings['items'], $items);
		}

		return $settings;
	}

	/**
	 * Returns the list of available services.
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getServices(array $settings) {
		$this->initialize($settings);

		if ($this->dao && $this->settings['topics']) {
			/** @var tx_egovapi_domain_repository_topicRepository $topicRepository */
			$topicRepository = tx_egovapi_domain_repository_factory::getRepository('topic');
			/** @var tx_egovapi_domain_repository_serviceRepository $serviceRepository */
			$serviceRepository = tx_egovapi_domain_repository_factory::getRepository('service');

			$allServices = array();
			$ids = t3lib_div::trimExplode(',', $this->settings['topics'], TRUE);
			foreach ($ids as $id) {
				$topic = $topicRepository->findById($id);
				if (!$topic) {
					continue;
				}

				$services = $serviceRepository->findAll($topic);
				foreach ($services as $service) {
					$allServices[] = $service;
				}

			}

			// Sort by service name
			tx_egovapi_utility_objects::sort($allServices, 'name');

			$items = array();
			foreach ($allServices as $service) {
				$items[] = array(sprintf('%s - %s', $service->getId(), $service->getName()), $service->getId());
			}
			$settings['items'] = array_merge($settings['items'], $items);
		}

		return $settings;
	}

	/**
	 * Creates a form wizard to choose special service versions to be used.
	 *
	 * @param array $PA
	 * @param t3lib_TCEforms $pObj
	 * @return string
	 */
	public function getVersionWizard(array $PA, t3lib_TCEforms $pObj) {
		$this->initialize($PA);
		$output = '';

		// No real ID before TYPO3 4.5
		$mappingFieldId = isset($PA['itemFormElID']) ? $PA['itemFormElID'] : $PA['itemFormElName'];

		$GLOBALS['LANG']->includeLLFile('EXT:egovapi/Resources/Private/Language/locallang.xml');

		if ($this->dao && $this->settings['topics']) {
			$output .= '
				<script type="text/javascript">
				function egovapi_updateVersionMapping(serviceId, versionId) {
					var mappingField = document.getElementById("' . $mappingFieldId . '");
					var mapping = {};
					if (mappingField.value.length > 0) {
						mapping = JSON.parse(mappingField.value.replace(/\'/g, "\\""));
					}
					if (parseInt(versionId) > 0) {
						mapping[serviceId] = versionId;
					} else {
						delete mapping[serviceId];
					}

					mappingField.value = JSON.stringify(mapping);
					' . $PA['fieldChangeFunc']['TBE_EDITOR_fieldChanged'] . '
				}
				</script>
				<table class="typo3-dblist">
					<thead>
						<tr class="c-headLine">
							<th>' . $GLOBALS['LANG']->getLL('pi_flexform.versions.serviceId') . '</th>
							<th>' . $GLOBALS['LANG']->getLL('pi_flexform.versions.version') . '</th>
							<th>' . $GLOBALS['LANG']->getLL('pi_flexform.versions.useVersion') . '</th>
							<th>' . $GLOBALS['LANG']->getLL('pi_flexform.versions.description') . '</th>
						</tr>
					</thead>
					<tbody>
				';

			/** @var tx_egovapi_domain_repository_topicRepository $topicRepository */
			$topicRepository = tx_egovapi_domain_repository_factory::getRepository('topic');
			/** @var tx_egovapi_domain_repository_serviceRepository $serviceRepository */
			$serviceRepository = tx_egovapi_domain_repository_factory::getRepository('service');

			$mapping = $PA['itemFormElValue'];
			if ($mapping) {
				$mapping = (array) json_decode(str_replace("'", '"', $mapping));
			} else {
				$mapping = array();
			}

			$allServices = array();
			$ids = t3lib_div::trimExplode(',', $this->settings['topics'], TRUE);
			foreach ($ids as $id) {
				$topic = $topicRepository->findById($id);
				if (!$topic) {
					continue;
				}

				$serviceIds = t3lib_div::trimExplode(',', $this->settings['services'], TRUE);
				foreach ($serviceIds as $serviceId) {
					$service = $serviceRepository->getByTopicAndIdAndVersion($topic, $serviceId);
					if ($service) {
						$allServices[] = $service;
					}
				}
			}

			// Sort by service name
			tx_egovapi_utility_objects::sort($allServices, 'name');

			foreach ($allServices as $service) {
				$version = isset($mapping[$service->getId()]) ? $mapping[$service->getId()] : '';

				$output .= '<tr class="db_list_normal">';
				$output .= '<td>' . $service->getId() . '</td>';
				$output .= '<td>' . $service->getVersionId() . '</td>';
				$output .= '<td><input type="text" value="' . $version . '" onchange="egovapi_updateVersionMapping(\'' . $service->getId() . '\', this.value)" size="6" maxlength="6" /></td>';
				$output .= '<td>' . $service->getName() . '</td>';
				$output .= '</tr>';
			}

			$output .= '
				</tbody>
			</table>';
		}

			// Create the hidden field that holds the actual mapping configuration
		$attributes = array(
			'id'       => $mappingFieldId,
			'name'     => $PA['itemFormElName'],
			'value'    => str_replace('"', '\'', $PA['itemFormElValue']),
		);

		$tag = '<input type="hidden" ';
		foreach ($attributes as $attribute => $value) {
			$tag .= $attribute . '="' . $value . '" ';
		}
		$tag .= '/>';

		$output .= $tag;

		return $output;
	}

	/**
	 * Initializes the configuration.
	 *
	 * @param array $settings
	 * @return void
	 */
	protected function initialize(array $settings) {
		$this->settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][self::$extKey]);
		if (!is_array($this->settings)) {
			$this->settings = array();
		}

			// Set webService language to backend user's language
		$this->settings['eCHlanguageID'] = $GLOBALS['BE_USER']->uc['lang'] ? strtoupper($GLOBALS['BE_USER']->uc['lang']) : 'EN';

			// Override configuration with TypoScript
		if (!self::$globalTS) {
			self::$globalTS = self::loadTS($settings['row']['pid']);
		}
		$ts = self::$globalTS['plugin.'][self::$prefixId . '.'];

		foreach ($ts as $key => $value) {
			if ($value) {
				$this->settings[$key] = $value;
			}
		}

			// BEWARE! do not use isset() in place of @ prefix hereafter
		$field = @$settings['field'] ? $settings['field'] : 'pi_flexform';
		$flexForm = $settings['row'][$field];
		if ($flexForm) {
			$flexForm = t3lib_div::xml2array($flexForm);
		}
		if ($flexForm) {
			$multiValueKeys = array('audiences', 'views', 'domains', 'topics', 'services');
			foreach ($flexForm['data'] as $sheet => $data) {
				foreach ($data as $lang => $value) {
					foreach ($value as $key => $val) {
						$value = $this->getFFvalue($flexForm, $key, $sheet);
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
						if (trim($value) !== '' || !isset($this->settings[$key])) {
							$this->settings[$key] = $value;
						}
					}
				}
			}
		}

		if ($this->settings['wsdl']) {
			$this->dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $this->settings);
			tx_egovapi_domain_repository_factory::injectDao($this->dao);
		}

			// Merge configuration with business logic and local override TypoScript (myTS)
		$this->settings = tx_egovapi_utility_ts::getMergedConfiguration($this->settings, array(), self::$globalTS);

		// Updating the settings does not make sense here as the lists of items
		// have already been prepared by TYPO3...
		/*
		if ($this->settings['wsdl']) {
			$dao = tx_egovapi_domain_repository_factory::getDao();
			$dao->updateSettings($this->settings);
		}
		*/
	}

	/**
	 * Returns the value from somewhere inside a FlexForm structure.
	 *
	 * @param array	FlexForm data
	 * @param string Field name to extract. Can be given like "test/el/2/test/el/field_templateObject" where each part will dig a level deeper in the FlexForm data.
	 * @param string Sheet pointer, eg. "sDEF"
	 * @param string Language pointer, eg. "lDEF"
	 * @param string Value pointer, eg. "vDEF"
	 * @return string
	 */
	protected function getFFvalue(array $T3FlexForm_array, $fieldName, $sheet = 'sDEF', $lang = 'lDEF', $value = 'vDEF') {
		$sheetArray = $T3FlexForm_array['data'][$sheet][$lang];
		if (is_array($sheetArray)) {
			return $this->getFFvalueFromSheetArray($sheetArray, explode('/', $fieldName), $value);
		}
	}

	/**
	 * Returns part of $sheetArray pointed to by the keys in $fieldNameArray.
	 *
	 * @param array Multidimensiona array, typically FlexForm contents
	 * @param array Array where each value points to a key in the FlexForms content - the input array will have the value returned pointed to by these keys. All integer keys will not take their integer counterparts, but rather traverse the current position in the array an return element number X (whether this is right behavior is not settled yet...)
	 * @param string Value for outermost key, typ. "vDEF" depending on language.
	 * @return mixed The value, typ. string.
	 * @see getFFvalue()
	 */
	protected function getFFvalueFromSheetArray(array $sheetArray, array $fieldNameArr, $value) {
		$tempArr = $sheetArray;
		foreach ($fieldNameArr as $k => $v) {
			$version = class_exists('t3lib_utility_VersionNumber')
				? t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version)
				: t3lib_div::int_from_ver(TYPO3_version);
			$vIsInt = ($version < 4006000) ? t3lib_div::testInt($v) : t3lib_utility_Math::canBeInterpretedAsInteger($v);
			if ($vIsInt) {
				if (is_array($tempArr)) {
					$c = 0;
					foreach ($tempArr as $values) {
						if ($c == $v) {
							$tempArr = $values;
							break;
						}
						$c++;
					}
				}
			} else {
				$tempArr = $tempArr[$v];
			}
		}
		return $tempArr[$value];
	}

	/**
	 * Returns the frontend TypoScript of a given page uid.
	 *
	 * @param integer $pageUid
	 * @return array
	 */
	protected static function loadTS($pageUid) {
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


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Utility/FlexForm.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Utility/FlexForm.php']);
}

?>