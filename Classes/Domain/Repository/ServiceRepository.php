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
 * An eGov Service repository.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_serviceRepository extends tx_egovapi_domain_repository_abstractRepository {

	const PATTERN_ID = '/^[0-9]{5}$/';

	/**
	 * @var array
	 */
	protected static $servicesByTopic = array();

	/**
	 * Finds all services associated to a given topic.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @param boolean $withKey
	 * @param boolean $cache
	 * @return tx_egovapi_domain_model_service[]
	 * @throws InvalidArgumentException
	 */
	public function findAll(tx_egovapi_domain_model_topic $topic = NULL, $withKey = FALSE, $cache = TRUE) {
		if ($topic === NULL) {
			throw new InvalidArgumentException('Topic cannot be null. This may be related to the use of an unsupported language for the web service.', 1299746874);
		}
		$id = $topic->getId();
		if (isset(self::$servicesByTopic[$id]) && $cache) {
			if (!$withKey) {
				$servicesByTopic = array();
				foreach (self::$servicesByTopic[$id] as $serviceKey => $service) {
					if (strpos($serviceKey, '-') === FALSE) {
						$servicesByTopic[] = $service;
					}
				}
				return $servicesByTopic;
			}
			return self::$servicesByTopic[$id];
		}

		self::$servicesByTopic[$id] = array();
		$servicesDao = $this->dao->getServices($id);

		foreach ($servicesDao as $serviceDao) {
			/** @var tx_egovapi_domain_model_service $service */
			$service = t3lib_div::makeInstance('tx_egovapi_domain_model_service', $serviceDao['id']);

			$service
				->setTopic($topic)
				->setAuthor($serviceDao['author'])
				->setCreationDate(strtotime($serviceDao['dateCreation']))
				->setLastModificationDate(strtotime($serviceDao['dateLastModification']))
				->setName($serviceDao['name'])
				->setDescription($serviceDao['description'])
				->setVersionId(intval($serviceDao['versionId']))
				->setVersionName($serviceDao['versionName'])
				->setCommunityId($serviceDao['communityId'])
				->setRelease(intval($serviceDao['release']))
				->setComments($serviceDao['remarks'])
				->setProvider($serviceDao['provider'])
				->setCustomer($serviceDao['customer'])
				->setType($serviceDao['type'])
				->setAction($serviceDao['action'])
				->setStatus($serviceDao['status'])
			;

			$serviceKey = $service->getId() . '-' . $service->getVersionId();
			self::$servicesByTopic[$id][$serviceKey] = $service;
			self::$servicesByTopic[$id][$service->getId()] = $service;
		}

		if (!$withKey) {
			// Take advantage of code at the beginning of this method to remove duplicates
			return $this->findAll($topic, FALSE);
		}
		return self::$servicesByTopic[$id];
	}

	/**
	 * Finds a service by id and version.
	 *
	 * @param string $id
	 * @param integer $version
	 * @return tx_egovapi_domain_model_service
	 */
	protected function findOneByIdAndVersion($id, $version) {
		$details = $this->dao->getServiceDetails($id, $version);

		if (!$details) {
			return NULL;
		}

		$serviceDao = $details['infoBlock'];

		/** @var tx_egovapi_domain_model_service $service */
		$service = t3lib_div::makeInstance('tx_egovapi_domain_model_service', $serviceDao['id']);

		$service
			->setAuthor($serviceDao['author'])
			->setCreationDate(strtotime($serviceDao['dateCreation']))
			->setLastModificationDate(strtotime($serviceDao['dateLastModification']))
			->setName($serviceDao['name'])
			->setDescription($serviceDao['description'])
			->setVersionId(intval($serviceDao['versionId']))
			->setVersionName($serviceDao['versionName'])
			->setCommunityId($serviceDao['communityId'])
			->setRelease(intval($serviceDao['release']))
			->setComments($serviceDao['remarks'])
			->setProvider($serviceDao['provider'])
			->setCustomer($serviceDao['customer'])
			->setType($serviceDao['type'])
			->setAction($serviceDao['action'])
			->setStatus($serviceDao['status'])
		;

		return $service;
	}

	/**
	 * Gets a service identified by its topic, id and possibly version.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @param string $id Format XXXXX
	 * @param integer $version Set to 0 to use default version
	 * @return tx_egovapi_domain_model_service
	 * @throws Exception
	 */
	public function getByTopicAndIdAndVersion(tx_egovapi_domain_model_topic $topic, $id, $version = 0) {
		if ($version > 0) {
			return $this->findOneByIdAndVersion($id, $version);
		}

		$services = $this->findAll($topic, TRUE);
		if ($version) {
			$serviceKey = $id . '-' . $version;
		} else {
			$serviceKey = $id;
		}
		$service = isset($services[$serviceKey]) ? $services[$serviceKey] : null;

		return $service;
	}

	/**
	 * Gets a service identified by its id.
	 *
	 * @param string $id Format XXXXX
	 * @param integer $version Set to 0 to use default version
	 * @return tx_egovapi_domain_model_service
	 * @throws Exception
	 */
	public function getByIdAndVersion($id, $version = 0) {
		return $this->findOneByIdAndVersion($id, $version);
	}

	/**
	 * Injects the details of a given service into the service object itself.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return void
	 */
	public function injectDetails(tx_egovapi_domain_model_service $service) {
		$detailsDao = $this->dao->getServiceDetails(
			$service->getId(),
			$service->getVersionId()
		);

		if (isset($detailsDao['generalInformationBlock']) && $detailsDao['generalInformationBlock']) {
			/** @var tx_egovapi_domain_model_block_generalInformation $generalInformation */
			$generalInformation = t3lib_div::makeInstance('tx_egovapi_domain_model_block_generalInformation');
			$generalInformation->setContent($detailsDao['generalInformationBlock']);
			$service->setGeneralInformation($generalInformation);
		}
		if (isset($detailsDao['prerequisiteBlock']) && is_array($detailsDao['prerequisiteBlock'])) {
			/** @var tx_egovapi_domain_model_block_prerequisites $prerequisites */
			$prerequisites = t3lib_div::makeInstance('tx_egovapi_domain_model_block_prerequisites');
			foreach ($detailsDao['prerequisiteBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_prerequisite $prerequisite */
				$prerequisite = t3lib_div::makeInstance('tx_egovapi_domain_model_block_prerequisite');

				if ($this->stripTags) {
					$prerequisite->setDescription(strip_tags($itemDao['description']));
				} else {
					$prerequisite->setDescription($itemDao['description']);
				}

				$prerequisites->addItem($prerequisite);
			}
			$service->setPrerequisites($prerequisites);
		}
		if (isset($detailsDao['procedureBlock']) && is_array($detailsDao['procedureBlock'])) {
			/** @var tx_egovapi_domain_model_block_procedure $procedure */
			$procedure = t3lib_div::makeInstance('tx_egovapi_domain_model_block_procedure');
			foreach ($detailsDao['procedureBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_procedureItem $procedureItem */
				$procedureItem = t3lib_div::makeInstance('tx_egovapi_domain_model_block_procedureItem');

				if ($this->stripTags) {
					$procedureItem->setDescription(strip_tags($itemDao['description']));
				} else {
					$procedureItem->setDescription($itemDao['description']);
				}

				$procedure->addItem($procedureItem);
			}
			$service->setProcedure($procedure);
		}
		$indexForms = array();
		if (isset($detailsDao['formularBlock']) && is_array($detailsDao['formularBlock'])) {
			/** @var tx_egovapi_domain_model_block_forms $forms */
			$forms = t3lib_div::makeInstance('tx_egovapi_domain_model_block_forms');
			foreach ($detailsDao['formularBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_form $form */
				$form = t3lib_div::makeInstance('tx_egovapi_domain_model_block_form');

				if ($this->stripTags) {
					$form->setName(strip_tags($itemDao['formularName']));
					$form->setUri(strip_tags($itemDao['uri']));
					$form->setDescription(strip_tags($itemDao['description']));
				} else {
					$form->setName($itemDao['formularName']);
					$form->setUri($itemDao['uri']);
					$form->setDescription($itemDao['description']);
				}

				$forms->addItem($form);
				if (isset($itemDao['idx'])) {
					$indexForms[$itemDao['idx']] = $form;
				}
			}
			$service->setForms($forms);
		}
		if (isset($detailsDao['documentRequiredBlock']) && is_array($detailsDao['documentRequiredBlock'])) {
			/** @var tx_egovapi_domain_model_block_documentsRequired $documentsRequired */
			$documentsRequired = t3lib_div::makeInstance('tx_egovapi_domain_model_block_documentsRequired');
			foreach ($detailsDao['documentRequiredBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_document $document */
				$document = t3lib_div::makeInstance('tx_egovapi_domain_model_block_document');

				if ($this->stripTags) {
					$document->setName(strip_tags($itemDao['documentName']));
					$document->setDescription(strip_tags($itemDao['description']));
				} else {
					$document->setName($itemDao['documentName']);
					$document->setDescription($itemDao['description']);
				}

				$documentsRequired->addItem($document);
			}
			$service->setDocumentsRequired($documentsRequired);
		}
		if (isset($detailsDao['resultBlock']) && $detailsDao['resultBlock']) {
			/** @var tx_egovapi_domain_model_block_serviceProvided $serviceProvided */
			$serviceProvided = t3lib_div::makeInstance('tx_egovapi_domain_model_block_serviceProvided');

			if ($this->stripTags) {
				$serviceProvided->setContent(strip_tags($detailsDao['resultBlock']));
			} else {
				$serviceProvided->setContent($detailsDao['resultBlock']);
			}

			$service->setServiceProvided($serviceProvided);
		}
		if (isset($detailsDao['feeBlock']) && $detailsDao['feeBlock']) {
			/** @var tx_egovapi_domain_model_block_fee $fee */
			$fee = t3lib_div::makeInstance('tx_egovapi_domain_model_block_fee');

			switch ($this->getWsdlVersion()) {
				case tx_egovapi_dao_webService::VERSION_10:
					if ($this->stripTags) {
						$fee->setContent(strip_tags($detailsDao['feeBlock']));
					} else {
						$fee->setContent($detailsDao['feeBlock']);
					}
					break;

				default:
					if ($this->stripTags) {
						$fee->setDescription(strip_tags($detailsDao['feeBlock']['description']));
					} else {
						$fee->setDescription($detailsDao['feeBlock']['description']);
					}

					if (!is_array($detailsDao['feeBlock']['pricingList'])) {
							// Most probably a caching issue with data from v1 of the Web Service
						$detailsDao['feeBlock']['pricingList'] = array();
					}
					foreach ($detailsDao['feeBlock']['pricingList'] as $itemDao) {
						/** @var $pricing tx_egovapi_domain_model_block_pricing */
						$pricing = t3lib_div::makeInstance('tx_egovapi_domain_model_block_pricing');

						$pricing->setPrice($itemDao['price']);
						$pricing->setFee($itemDao['fee']);
						$pricing->setHasEPayment((bool) $itemDao['epaymentEnabled']);
						$pricing->setVatCode($itemDao['vatCode']);

						if (isset($indexForms[$itemDao['formId']])) {
							$pricing->setForm($indexForms[$itemDao['formId']]);
						}

						$fee->addPricing($pricing);
					}
					break;
			}

			$service->setFee($fee);
		}
		if (isset($detailsDao['legalRegulationBlock']) && is_array($detailsDao['legalRegulationBlock'])) {
			/** @var tx_egovapi_domain_model_block_legalRegulation $legalRegulation */
			$legalRegulation = t3lib_div::makeInstance('tx_egovapi_domain_model_block_legalRegulation');
			foreach ($detailsDao['legalRegulationBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_legalRegulationItem $legalRegulationItem */
				$legalRegulationItem = t3lib_div::makeInstance('tx_egovapi_domain_model_block_legalRegulationItem');

				if ($this->stripTags) {
					$legalRegulationItem->setName(strip_tags($itemDao['legalRegulationName']));
					$legalRegulationItem->setDescription(strip_tags($itemDao['description']));
					$legalRegulationItem->setUri(strip_tags($itemDao['uri']));
				} else {
					$legalRegulationItem->setName($itemDao['legalRegulationName']);
					$legalRegulationItem->setDescription($itemDao['description']);
					$legalRegulationItem->setUri($itemDao['uri']);
				}

				$legalRegulation->addItem($legalRegulationItem);
			}
			$service->setLegalRegulation($legalRegulation);
		}
		if (isset($detailsDao['documentOtherBlock']) && is_array($detailsDao['documentOtherBlock'])) {
			/** @var tx_egovapi_domain_model_block_documentsOther $documentsOther */
			$documentsOther = t3lib_div::makeInstance('tx_egovapi_domain_model_block_documentsOther');
			foreach ($detailsDao['documentOtherBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_document $document */
				$document = t3lib_div::makeInstance('tx_egovapi_domain_model_block_document');

				if ($this->stripTags) {
					$document->setName(strip_tags($itemDao['documentName']));
					$document->setDescription(strip_tags($itemDao['description']));
				} else {
					$document->setName($itemDao['documentName']);
					$document->setDescription($itemDao['description']);
				}

				$documentsOther->addItem($document);
			}
			$service->setDocumentsOther($documentsOther);
		}
		if (isset($detailsDao['remarkBlock']) && $detailsDao['remarkBlock']) {
			/** @var tx_egovapi_domain_model_block_remarks $remarks */
			$remarks = t3lib_div::makeInstance('tx_egovapi_domain_model_block_remarks');
			$remarks->setContent($detailsDao['remarkBlock']);
			$service->setRemarks($remarks);
		}
		if (isset($detailsDao['approbationBlock']) && $detailsDao['approbationBlock']) {
			/** @var tx_egovapi_domain_model_block_approval $approval */
			$approval = t3lib_div::makeInstance('tx_egovapi_domain_model_block_approval');

			if ($this->stripTags) {
				$approval->setContent(strip_tags($detailsDao['approbationBlock']));
			} else {
				$approval->setContent($detailsDao['approbationBlock']);
			}

			$service->setApproval($approval);
		}
		if (isset($detailsDao['contactBlock']) && is_array($detailsDao['contactBlock'])) {
			$contactBlock = $detailsDao['contactBlock'];
			// Version dev of the WS 2.1 seems to allow multiple contact blocks => take the first one!
			$keys = array_keys($contactBlock);
			if ($keys[0] === 0) {
				$contactBlock = $contactBlock[0];
			}
			foreach ($contactBlock as $key => $info) {
				if (is_array($info) && isset($info['content'])) {
					$contactBlock[$key] = $info['content'];
				}
			}

			/** @var tx_egovapi_domain_model_block_contact $contact */
			$contact = t3lib_div::makeInstance('tx_egovapi_domain_model_block_contact');
			$contact
				->setDepartment($contactBlock['department'])
				->setOffice($contactBlock['office'])
				->setAddress($contactBlock['address'])
				->setPoBox($contactBlock['postalCase'])
				->setPostalCode($contactBlock['postalCode'])
				->setPerson($contactBlock['person'])
				->setFax($contactBlock['fax'])
				->setEmail($contactBlock['email'])
				->setPublicKey($contactBlock['publicKey'])
				->setLogo($contactBlock['logoUrl'])
				->setBanner($contactBlock['bannerUrl'])
				->setOpeningHours($contactBlock['openingHours']);

			switch ($this->getWsdlVersion()) {
				case tx_egovapi_dao_webService::VERSION_10:
					$contact->setLocality($contactBlock['municipality']);
					$contact->setPhone($contactBlock['phone1']);
					break;

				default:
					$contact->setLocality($contactBlock['locality']);
					$contact->setPhone($contactBlock['phone']);
					break;
			}

			$service->setContact($contact);
		}

		$service->setHasDetails();
	}

	/**
	 * Injects the versions available for a given service.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return void
	 */
	public function injectVersions(tx_egovapi_domain_model_service $service) {
		$versionsDao = $this->dao->getVersions(
			$service->getId()
		);

		if (!count($versionsDao)) {
				// A few services don't have any version (!?!) => create one out
				// service's information
			$versionsDao[] = array(
				'id' => $service->getVersionId(),
				'name' => $service->getVersionName(),
				'status' => tx_egovapi_domain_model_constants::VERSION_STATUS_PUBLISHED,
				'communityId' => $service->getCommunityId(),
				'isDefault' => TRUE,
			);
		}

		foreach ($versionsDao as $versionDao) {
			/** @var $version tx_egovapi_domain_model_version */
			$version = t3lib_div::makeInstance('tx_egovapi_domain_model_version', $versionDao['id']);

			$version->setService($service);
			$version->setName($versionDao['name']);
			$version->setStatus($versionDao['status']);
			$version->setCommunityId($versionDao['communityId']);
			$version->setIsDefault($versionDao['isDefault']);

			$service->addVersion($version);
		}
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/ServiceRepository.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/ServiceRepository.php']);
}

?>