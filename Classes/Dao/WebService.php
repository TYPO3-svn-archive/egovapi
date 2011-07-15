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
 * Library to connect to the E-Government webservice.
 *
 * @category    DAO
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_dao_webService {

	const DEVLOG_OK      = -1;
	const DEVLOG_WARNING = 2;
	const DEVLOG_FATAL   = 3;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var mixed (string/boolean)
	 */
	protected $username;

	/**
	 * @var mixed (string/boolean)
	 */
	protected $password;

	/**
	 * @var tx_em_Connection_Soap
	 */
	protected $soap;

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

		$options = array(
        	'wsdl' => $this->settings['wsdl'],
			'soapoptions' => array(
				'trace' => 1,
				'exceptions' => $this->debug ? 1 : 0,
			),
        );
        $this->username = FALSE;
        $this->password = FALSE;

		$version = class_exists('t3lib_utility_VersionNumber')
				? t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version)
				: t3lib_div::int_from_ver(TYPO3_version);
		if ($version >= 4005000) {
			$this->soap = t3lib_div::makeInstance('tx_em_connection_soap');
		} else {
			$pathEmMod = PATH_typo3 . 'mod/tools/em/';
			if (!defined('SOAP_1_2')) {
				require_once($pathEmMod . 'class.nusoap.php');
			}
			require_once($pathEmMod . 'class.em_soap.php');

			$this->soap = t3lib_div::makeInstance('em_soap');
		}

        try {
        	$this->soap->init($options, $this->username, $this->password);
        } catch (Exception $e) {
        	die('Could not initialize SOAP.');
        }
	}

	/**
	 * Returns the list of audiences.
	 *
	 * @return array
	 */
	public function getAudiences() {
		$audiences = array();
		$publicList = $this->callEGovApi('GetPublicList');
		if (is_array($publicList) && is_array($publicList['eCHpublicList'])) {
			if (is_array($publicList['eCHpublicList']['publicInfo']) && !isset($publicList['eCHpublicList']['publicInfo'][0])) {
				$publicList['eCHpublicList']['publicInfo'] = array($publicList['eCHpublicList']['publicInfo']);
			}
			foreach ($publicList['eCHpublicList']['publicInfo'] as $audience) {
				$id = $this->getValue($audience, 'eCHpublicID');
				if (!$id) {
					continue;
				}
				$audiences[] = array(
					'id' => $id,
					'name' => $this->getValue($audience, 'eCHpublic'),
					'author' => $this->getValue($audience, 'author'),
					'dateCreation' => $this->getValue($audience, 'createdDate'),
					'dateLastModification' => $this->getValue($audience, 'lastModificationDate'),
				);
			}

			if (!$audiences && $this->debug) {
				t3lib_div::devLog('Could not process audiences', 'egovapi', self::DEVLOG_WARNING, $publicList);
			}
		}

			// Sort audiences by (localized) name
		$this->sort($audiences, 'name');

		return $audiences;
	}

	/**
	 * Returns the list of views for a given audience.
	 *
	 * @param string $audienceId
	 * @return array
	 */
	public function getViews($audienceId) {
		$views = array();
		$viewList = $this->callEgovApi('GetViewList', array(
			'eCHpublicID' => $audienceId,
		));
		if (is_array($viewList) && is_array($viewList['eCHviewList'])) {
			if (is_array($viewList['eCHviewList']['viewInfo']) && !isset($viewList['eCHviewList']['viewInfo'][0])) {
				$viewList['eCHviewList']['viewInfo'] = array($viewList['eCHviewList']['viewInfo']);
			}
			if (isset($viewList['eCHviewList']['viewInfo'])) {
				foreach ($viewList['eCHviewList']['viewInfo'] as $view) {
					$id = $this->getValue($view, 'eCHviewID');
					if (!$id) {
						continue;
					}
					$views[] = array(
						'id' => $id,
						'name' => $this->getValue($view, 'eCHview'),
						'author' => $this->getValue($view, 'author'),
						'dateCreation' => $this->getValue($view, 'createdDate'),
						'dateLastModification' => $this->getValue($view, 'lastModificationDate'),
					);
				}
			}

			if (!$views && $this->debug) {
				t3lib_div::devLog('Could not process views for audience "' . $audienceId . '"', 'egovapi', self::DEVLOG_WARNING, $viewList);
			}
		}

			// Sort views by (localized) name
		$this->sort($views, 'name');

		return $views;
	}

	/**
	 * Returns the list of domains for a given view.
	 *
	 * @param string $viewId
	 * @return array
	 */
	public function getDomains($viewId) {
		if ($this->settings['includeCHServices'] && $this->settings['eCHcommunityID'] !== '00-00') {
			$domainsCommunity = $this->_getDomains($viewId, FALSE);
			$domains = $domainsCommunity;
			$domainsCH = $this->_getDomains($viewId, TRUE);

			if (is_array($domainsCH)) {
				foreach ($domainsCH as $domain) {
						// Key used to remove duplicates
					$domains[$domain['id']] = $domain;
				}
			}
		} else {
			$domains = $this->_getDomains($viewId, FALSE);
		}

		$domains = is_array($domains) ? $domains : array();

			// Sort topics by (localized) name
		$this->sort($domains, 'name');

		return $domains;
	}

	/**
	 * Returns the list of domains for a given view.
	 *
	 * @param string $viewId
	 * @param boolean $forceCHLevel
	 * @return array
	 */
	protected function _getDomains($viewId, $forceCHLevel) {
		$domains = array();
		$domainList = $this->callEgovApi('GetDomainList', array(
			'eCHviewID' => $viewId,
		), $forceCHLevel);
		if (is_array($domainList) && is_array($domainList['eCHdomainList'])) {
			if (is_array($domainList['eCHdomainList']['domainInfo']) && !isset($domainList['eCHdomainList']['domainInfo'][0])) {
				$domainList['eCHdomainList']['domainInfo'] = array($domainList['eCHdomainList']['domainInfo']);
			}
			if (isset($domainList['eCHdomainList']['domainInfo'])) {
				foreach ($domainList['eCHdomainList']['domainInfo'] as $domain) {
					$id = $this->getValue($domain, 'eCHdomainID');
					if (!$id) {
						continue;
					}
					$domains[] = array(
						'id' => $id,
						'name' => $this->getValue($domain, 'eCHdomain'),
						'description' => $this->getValue($domain, 'description'),
						'isParent' => $this->getValue($domain, 'isParent'),
						'versionId' => $this->getValue($domain, 'eCHdomainVersionID'),
						'versionName' => $this->getValue($domain, 'eCHdomainVersionName'),
						'communityId' => $this->getValue($domain, 'eCHcommunityID'),
						'release' => $this->getValue($domain, 'release'),
						'remarks' => $this->getValue($domain, 'remark'),
						'status' => $this->getValue($domain, 'eCHstatus'),
						'author' => $this->getValue($domain, 'author'),
						'dateCreation' => $this->getValue($domain, 'createdDate'),
						'dateLastModification' => $this->getValue($domain, 'lastModificationDate'),
					);
				}
			}

			if (!$domains && $this->debug) {
				t3lib_div::devLog('Could not process domains for view "' . $viewId . '"', 'egovapi', self::DEVLOG_WARNING, $domainList);
			}
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
		$details = array();
		$domainDetails = $this->callEgovApi('GetDomainDetail', array(
			'eCHdomainID' => $domainId,
			'eCHdomainVersionID' => $versionId,
			'isParent' => $isParent ? 1 : 0,
			'eCHdomainBlock' => implode(',', $this->array_range(2, 13)),
		));
		if (is_array($domainDetails['eCHdomainDetail'])) {
			$domainDetails = $domainDetails['eCHdomainDetail'];
			$blocks = array(
				'generalInformationBlock' => 'generalInformation',
				'newsBlock'               => 'news',
				'subdomainBlock'          => 'domainList',
				'descriptorBlock'         => 'descriptor',
				'synonymBlock'            => 'synonym',
			);
			foreach ($blocks as $block => $key) {
				if (isset($domainDetails[$block])) {
					$details[$block] = @$domainDetails[$block][$key];
				}
			}
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
		if ($this->settings['includeCHServices'] && $this->settings['eCHcommunityID'] !== '00-00') {
			$topicsCommunity = $this->_getTopics($domainId, FALSE);
			$topics = $topicsCommunity;
			$topicsCH = $this->_getTopics($domainId, TRUE);

			if (is_array($topicsCH)) {
				foreach ($topicsCH as $topic) {
						// Key used to remove duplicates
					$topics[$topic['id']] = $topic;
				}
			}
		} else {
			$topics = $this->_getTopics($domainId, FALSE);
		}

		$topics = is_array($topics) ? $topics : array();

			// Sort topics by (localized) name
		$this->sort($topics, 'name');

		return $topics;
	}

	/**
	 * Returns the list of topics for a given domain.
	 *
	 * @param string $domainId
	 * @param boolean $forceCHLevel
	 * @return array
	 */
	protected function _getTopics($domainId, $forceCHLevel) {
		$topics = array();
		$topicList = $this->callEgovApi('GetTopicList', array(
			'eCHdomainID' => $domainId,
		), $forceCHLevel);
		if (is_array($topicList) && is_array($topicList['eCHtopicList'])) {
			if (is_array($topicList['eCHtopicList']['topicInfo']) && !isset($topicList['eCHtopicList']['topicInfo'][0])) {
				$topicList['eCHtopicList']['topicInfo'] = array($topicList['eCHtopicList']['topicInfo']);
			}
			if (isset($topicList['eCHtopicList']['topicInfo'])) {
				foreach ($topicList['eCHtopicList']['topicInfo'] as $topic) {
					$id = $this->getValue($topic, 'eCHtopicID');
					if (!$id) {
						continue;
					}
					$topics[] = array(
						'id' => $id,
						'name' => $this->getValue($topic, 'eCHtopic'),
						'description' => $this->getValue($topic, 'description'),
						'isParent' => $this->getValue($topic, 'isParent'),
						'versionId' => $this->getValue($topic, 'eCHtopicVersionID'),
						'versionName' => $this->getValue($topic, 'eCHdomainVersionName'),
						'communityId' => $this->getValue($topic, 'eCHcommunityID'),
						'release' => $this->getValue($topic, 'release'),
						'remarks' => $this->getValue($topic, 'remark'),
						'status' => $this->getValue($topic, 'eCHstatus'),
						'author' => $this->getValue($topic, 'author'),
						'dateCreation' => $this->getValue($topic, 'createdDate'),
						'dateLastModification' => $this->getValue($topic, 'lastModificationDate'),
					);
				}
			}

			if (!$topics && $this->debug) {
				t3lib_div::devLog('Could not process topics for domain "' . $domainId . '"', 'egovapi', self::DEVLOG_WARNING, $topicList);
			}
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
		$details = array();
		$topicDetails = $this->callEgovApi('GetTopicDetail', array(
			'eCHtopicID' => $topicId,
			'eCHtopicVersionID' => $versionId,
			'isParent' => $isParent ? 1 : 0,
			'eCHtopicBlock' => implode(',', $this->array_range(2, 13)),
		));
		if (is_array($topicDetails['eCHtopicDetail'])) {
			$topicDetails = $topicDetails['eCHtopicDetail'];
			$blocks = array(
				'generalInformationBlock' => 'generalInformation',
				'newsBlock'               => 'news',
				'subtopicBlock'           => 'topicList',
				'descriptorBlock'         => 'descriptor',
				'synonymBlock'            => 'synonym',
			);
			foreach ($blocks as $block => $key) {
				if (isset($topicDetails[$block])) {
					$details[$block] = @$topicDetails[$block][$key];
				}
			}
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
		if ($this->settings['includeCHServices'] && $this->settings['eCHcommunityID'] !== '00-00') {
			$servicesCommunity = $this->_getServices($topicId, FALSE);
			$services = $servicesCommunity;
			$servicesCH = $this->_getServices($topicId, TRUE);
			if (is_array($servicesCH)) {
				foreach ($servicesCH as $service) {
						// Key used to removed duplicates
					$services[$service['id']] = $service;
				}
			}
		} else {
			$services = $this->_getServices($topicId, FALSE);
		}

		$services = is_array($services) ? $services : array();

			// Sort services by (localized) name
		$this->sort($services, 'name');

		return $services;
	}

	/**
	 * Returns the list of services for a given topic.
	 *
	 * @param  $topicId
	 * @param  $forceCHLevel
	 * @return array
	 */
	protected function _getServices($topicId, $forceCHLevel) {
		$services = array();
		$serviceList = $this->callEgovApi('GetServiceList', array(
			'eCHtopicID' => $topicId,
		), $forceCHLevel);
		if (is_array($serviceList) && is_array($serviceList['eCHserviceList'])) {
			if (is_array($serviceList['eCHserviceList']['serviceInfo']) && !isset($serviceList['eCHserviceList']['serviceInfo'][0])) {
				$serviceList['eCHserviceList']['serviceInfo'] = array($serviceList['eCHserviceList']['serviceInfo']);
			}
			if (isset($serviceList['eCHserviceList']['serviceInfo'])) {
				foreach ($serviceList['eCHserviceList']['serviceInfo'] as $service) {
					$id = $this->getValue($service, 'eCHserviceID');
					if (!$id) {
						continue;
					}
					$services[] = array(
						'id' => $id,
						'name' => $this->getValue($service, 'eCHservice'),
						'description' => $this->getValue($service, 'description'),
						'versionId' => $this->getValue($service, 'eCHserviceVersionID'),
						'versionName' => $this->getValue($service, 'eCHserviceVersionName'),
						'communityId' => $this->getValue($service, 'eCHcommunityID'),
						'release' => $this->getValue($service, 'release'),
						'remarks' => $this->getValue($service, 'remark'),
						'provider' => $this->getValue($service, 'eCHserviceProvider'),
						'customer' => $this->getValue($service, 'eCHcustomer'),
						'type' => $this->getValue($service, 'eCHserviceType'),
						'action' => $this->getValue($service, 'eCHaction'),
						'status' => $this->getValue($service, 'eCHstatus'),
						'author' => $this->getValue($service, 'author'),
						'dateCreation' => $this->getValue($service, 'createdDate'),
						'dateLastModification' => $this->getValue($service, 'lastModificationDate'),
					);
				}
			}

			if (!$services && $this->debug) {
				t3lib_div::devLog('Could not process services for topic "' . $topicId . '"', 'egovapi', self::DEVLOG_WARNING, $serviceList);
			}
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
		$details = array();
		$serviceDetails = $this->callEgovApi('GetServiceDetail', array(
			'eCHserviceID' => $serviceId,
			'eCHserviceVersionID' => $versionId,
			'eCHserviceBlock' => implode(',', $this->array_range(1, 13)),
		));
		if (is_array($serviceDetails['eCHserviceDetail'])) {
			$serviceDetails = $serviceDetails['eCHserviceDetail'];
			$blocks = array(
				'infoBlock'               => 'serviceInfo',
				'generalInformationBlock' => 'generalInformation',
				'prerequisiteBlock'       => 'prerequisite',
				'procedureBlock'          => 'procedure',
				'formularBlock'           => 'formular',
				'documentRequiredBlock'   => 'document',
				'resultBlock'             => 'result',
				'feeBlock'                => 'feeInfo',
				'legalRegulationBlock'    => 'legalRegulation',
				'documentOtherBlock'      => 'document',
				'remarkBlock'             => 'remark',
				'approbationBlock'        => 'approbation',
				'contactBlock'            => 'contact',
			);

			foreach ($blocks as $block => $key) {
				if (isset($serviceDetails[$block])) {
					$details[$block] = @$serviceDetails[$block][$key];
				}
			}

			$info = array(
				'id' => $this->getValue($details['infoBlock'], 'eCHserviceID'),
				'name' => $this->getValue($details['infoBlock'], 'eCHservice'),
				'description' => $this->getValue($details['infoBlock'], 'description'),
				'versionId' => $this->getValue($details['infoBlock'], 'eCHserviceVersionID'),
				'versionName' => $this->getValue($details['infoBlock'], 'eCHserviceVersionName'),
				'communityId' => $this->getValue($details['infoBlock'], 'eCHcommunityID'),
				'release' => $this->getValue($details['infoBlock'], 'release'),
				'remarks' => $this->getValue($details['infoBlock'], 'remark'),
				'provider' => $this->getValue($details['infoBlock'], 'eCHserviceProvider'),
				'customer' => $this->getValue($details['infoBlock'], 'eCHcustomer'),
				'type' => $this->getValue($details['infoBlock'], 'eCHserviceType'),
				'action' => $this->getValue($details['infoBlock'], 'eCHaction'),
				'status' => $this->getValue($details['infoBlock'], 'eCHstatus'),
				'author' => $this->getValue($details['infoBlock'], 'author'),
				'dateCreation' => $this->getValue($details['infoBlock'], 'createdDate'),
				'dateLastModification' => $this->getValue($details['infoBlock'], 'lastModificationDate'),
			);
			$details['infoBlock'] = $info;

			if (is_array($details['prerequisiteBlock']) && !isset($details['prerequisiteBlock'][0])) {
				$details['prerequisiteBlock'] = array($details['prerequisiteBlock']);
			}
			if (is_array($details['procedureBlock']) && !isset($details['procedureBlock'][0])) {
				$details['procedureBlock'] = array($details['procedureBlock']);
			}
			if (is_array($details['formularBlock']) && !isset($details['formularBlock'][0])) {
				$details['formularBlock'] = array($details['formularBlock']);
			}
			if (is_array($details['documentRequiredBlock']) && !isset($details['documentRequiredBlock'][0])) {
				$details['documentRequiredBlock'] = array($details['documentRequiredBlock']);
			}
			if (is_array($details['legalRegulationBlock']) && !isset($details['legalRegulationBlock'][0])) {
				$details['legalRegulationBlock'] = array($details['legalRegulationBlock']);
			}
			if (is_array($details['documentOtherBlock']) && !isset($details['documentOtherBlock'][0])) {
				$details['documentOtherBlock'] = array($details['documentOtherBlock']);
			}
		}

		return $details;
	}

	/**
	 * Returns the versions available for given service.
	 *
	 * @param string $serviceId
	 * @return array
	 */
	public function getVersions($serviceId) {
		$versions = array();
		$versionList = $this->callEgovApi('GetServiceVersions', array(
			'eCHserviceID' => $serviceId,
		));

		if (is_array($versionList) && is_array($versionList['serviceVersionList'])) {
			if (is_array($versionList['serviceVersionList']['serviceVersion']) && !isset($versionList['serviceVersionList']['serviceVersion'][0])) {
				$versionList['serviceVersionList']['serviceVersion'] = array($versionList['serviceVersionList']['serviceVersion']);
			}
			if (isset($versionList['serviceVersionList']['serviceVersion'])) {
				foreach ($versionList['serviceVersionList']['serviceVersion'] as $version) {
					$id = $this->getValue($version, 'eCHserviceVersionID');
					if (!$id) {
						continue;
					}
					$versions[] = array(
						'id' => $id,
						'name' => $this->getValue($version, 'eCHserviceVersionName'),
						'status' => $this->getValue($version, 'statusID'),
						'communityId' => $this->getValue($version, 'eCHcommunityID'),
						'isDefault' => $this->getValue($version, 'isDefault'),
					);
				}
			}
		}

		return $versions;
	}

	/**
	 * Returns the list of changes for a given community since a given timestamp.
	 *
	 * @param string $communityId
	 * @param integer $since
	 * @param string $language
	 * @return null|array
	 */
	public function getLatestChanges($communityId, $since, $language) {
			// Backup settings
		$backupSettings = $this->settings;

			// Override some settings
		$this->settings['eCHcommunityID'] = $communityId;
		$this->settings['eCHlanguageID'] = $language;

		$changes = array();
		$changeList = $this->callEGovApi('GetLatestChanges', array(
			'since' => date('Y-m-d', $since),
			'includeDomain' => '1',
			'includeTopic' => '1',
			'includeService' => '1',
		));

		if (!(
			isset($changeList['changedDomainList']) &&
			isset($changeList['changedTopicList']) &&
			isset($changeList['changedServiceList'])
		)) {
			$changes = NULL;
		} else {
				// Prepare list of domain changes
			$changes['domains'] = array();
			if (isset($changeList['changedDomainList']['changedDomain']) && is_array($changeList['changedDomainList']['changedDomain'])) {
				if (!isset($changeList['changedDomainList']['changedDomain'][0])) {
					$changeList['changedDomainList']['changedDomain'] = array($changeList['changedDomainList']['changedDomain']);
				}
				foreach ($changeList['changedDomainList']['changedDomain'] as $changedDomain) {
					$changes['domains'][] = array(
						'id' => $changedDomain['eCHdomainID'],
						'version' => $changedDomain['eCHdomainVersionID'],
						'archived' => $changedDomain['archived'],
					);
				}
			}

				// Prepare list of topic changes
			$changes['topics'] = array();
			if (isset($changeList['changedTopicList']['changedTopic']) && is_array($changeList['changedTopicList']['changedTopic'])) {
				if (!isset($changeList['changedTopicList']['changedTopic'][0])) {
					$changeList['changedTopicList']['changedTopic'] = array($changeList['changedTopicList']['changedTopic']);
				}
				foreach ($changeList['changedTopicList']['changedTopic'] as $changedTopic) {
					$changes['topics'][] = array(
						'id' => $changedTopic['eCHtopicID'],
						'version' => $changedTopic['eCHtopicVersionID'],
						'archived' => $changedTopic['archived'],
					);
				}
			}

				// Prepare list of service changes
			$changes['services'] = array();
			if (isset($changeList['changedServiceList']['changedService']) && is_array($changeList['changedServiceList']['changedService'])) {
				if (!isset($changeList['changedServiceList']['changedService'][0])) {
					$changeList['changedServiceList']['changedService'] = array($changeList['changedServiceList']['changedService']);
				}
				foreach ($changeList['changedServiceList']['changedService'] as $changedService) {
					$changes['services'][] = array(
						'id' => $changedService['eCHserviceID'],
						'version' => $changedService['eCHserviceVersionID'],
						'archived' => $changedService['archived'],
					);
				}
			}
		}

			// Restore settings
		$this->settings = $backupSettings;

		return $changes;
	}

	/**
	 * Returns an array with values from $lbound to $ubound.
	 *
	 * @param integer $lbound
	 * @param integer $ubound
	 * @return array
	 */
	protected function array_range($lbound, $ubound) {
		$ret = array();
		for ($i = $lbound; $i <= $ubound; $i++) {
			$ret[] = $i;
		}
		return $ret;
	}

	/**
	 * Alphabetically sorts an array by a given sorting key.
	 *
	 * @param array $data
	 * @param string $sortingKey
	 * @return void
	 */
	protected function sort(array &$data, $sortingKey) {
		$keyValues = array();
		foreach ($data as $key => $row) {
			$keyValues[$key] = $row[$sortingKey];
		}

		array_multisort($keyValues, SORT_ASC, $data);
	}

	/**
	 * Returns the value corresponding to a given key.
	 *
	 * @param array $arr
	 * @param string $key
	 * @return string
	 */
	protected function getValue(array $arr, $key) {
		if (!isset($arr[$key])) {
			$value = NULL;
		} elseif (is_array($arr[$key])) {
			$value = isset($arr[$key]['content']) ? $arr[$key]['content'] : NULL;
		} else {
			$value = $arr[$key];
		}
		return $value;
	}

	/**
	 * Performs a SOAP call to the E-Government webservice.
	 *
	 * @param string $method
	 * @param array $additionalParameters
	 * @param boolean $forceCHLevel if TRUE, then communityId will be forced to be 00-00
	 * @return mixed
	 */
	protected function callEGovApi($method, array $additionalParameters = array(), $forceCHLevel = FALSE) {
		$communityId = ($forceCHLevel ? '00-00' : $this->settings['eCHcommunityID']);
		$parameters = array(
			'eCHapiFormat' => 'xml',
			'eCHapiEncode' => 'utf-8',
			'eCHapiMethod' => $method,
			'eCHlanguageID' => strtoupper($this->settings['eCHlanguageID']),
			'eCHcommunityID' => $communityId,
			'organisationID' => $this->settings['organizationID'],
		);
		$parameters = array_merge($parameters, $additionalParameters);

		if ($this->debug) {
			t3lib_div::devLog('Call "' . $method . '"', 'egovapi', self::DEVLOG_OK, $parameters);
		}

		try {
			$ret = $this->soap->call($method, array('parameters' => $parameters));

			if ($this->debug) {
				t3lib_div::devLog('Result "' . $method . '"', 'egovapi', self::DEVLOG_OK, $ret);
			}
		} catch (SoapFault $e) {
			if ($this->debug) {
				t3lib_div::devLog('Error while invoking web service: ' . $e->getMessage(), 'egovapi', self::DEVLOG_FATAL);
			}
			$ret = array();
		}

		return $ret;
	}
}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Dao/WebService.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Dao/WebService.php']);
}

?>