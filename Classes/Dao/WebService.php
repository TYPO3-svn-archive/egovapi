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

if (t3lib_div::int_from_ver(TYPO3_version) < 4005000) {
	$pathEmMod = PATH_typo3 . 'mod/tools/em/';

	if (!defined('SOAP_1_2')) {
		require_once($pathEmMod . 'class.nusoap.php');
	}
	require_once($pathEmMod . 'class.em_soap.php');
}

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


	/*
	const BLOCK_INFORMATION         =  1;
	const BLOCK_GENERAL_INFORMATION =  2;
	const BLOCK_PREREQUISITE        =  3;
	const BLOCK_PROCEDURE           =  4;
	const BLOCK_FORM                =  5;
	const BLOCK_DOCUMENT_REQUIRED   =  6;
	const BLOCK_RESULT              =  7;
	const BLOCK_FEE                 =  8;
	const BLOCK_LEGAL_REGULATION    =  9;
	const BLOCK_OTHER_DOCUMENT      = 10;
	const BLOCK_REMARKS             = 11;
	const BLOCK_APPROVAL            = 12;
	const BLOCK_CONTACT             = 13;
	*/

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
	 * @var tx_em_connection_soap
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

		if (t3lib_div::int_from_ver(TYPO3_version) >= 4005000) {
			$this->soap = t3lib_div::makeInstance('tx_em_connection_soap');
		} else {
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
		$domains = array();
		$domainList = $this->callEgovApi('GetDomainList', array(
			'eCHviewID' => $viewId,
		));
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

			// Sort domains by (localized) name
		$this->sort($domains, 'name');

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
		$topics = array();
		$topicList = $this->callEgovApi('GetTopicList', array(
			'eCHdomainID' => $domainId,
		));
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

			// Sort topics by (localized) name
		$this->sort($topics, 'name');

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
		if ($this->settings['eCHcommunityID'] !== '00-00') {
			$servicesCommunity = $this->_getServices($topicId, FALSE);
			$servicesCH = $this->_getServices($topicId, TRUE);
			$services = $servicesCommunity;
			foreach ($servicesCH as $service) {
				$services[] = $service;
			}
		} else {
			$services = $this->_getServices($topicId, FALSE);
		}

			// Sort services by (localized) name
		$this->sort($services, 'name');

		return $services;
	}

	protected function _getServices($topicId, $forceCHLevel) {
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
			'eCHserviceBlock' => implode(',', $this->array_range(2, 13)),
		));
		if (is_array($serviceDetails['eCHserviceDetail'])) {
			$serviceDetails = $serviceDetails['eCHserviceDetail'];
			$blocks = array(
				'generalInformationBlock' => 'generalInformation',
				'prerequisiteBlock'       => 'prerequisite',
				'procedureBlock'          => 'procedure',
				'formularBlock'           => 'formular',
				'documentRequiredBlock'   => 'document',
				'resultBlock'             => 'result',
				'feeBlock'                => 'fee',
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
			if (is_array($details['documentOtherBlock']) && !isset($details['documentOtherBlock'][0])) {
				$details['documentOtherBlock'] = array($details['documentOtherBlock']);
			}
		}

		return $details;
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
		return $arr[$key]['content'];
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
			'eCHmunicipalityID' => $this->settings['eCHmunicipalityID'],
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


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Dao/WebService.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Dao/WebService.php']);
}

?>