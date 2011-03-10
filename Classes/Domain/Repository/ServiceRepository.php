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
	 * @return tx_egovapi_domain_model_service[]
	 */
	public function findAll(tx_egovapi_domain_model_topic $topic = NULL, $withKey = FALSE) {
		if ($topic === NULL) {
			throw new InvalidArgumentException('Topic cannot be null. This may be related to the use of an unsupported language for the web service.', 1299746874);
		}
		$id = $topic->getId();
		if (isset(self::$servicesByTopic[$id])) {
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
	 * Gets a service identified by its id.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @param string $id Format XXXXX
	 * @param integer $version Set to 0 to use default version
	 * @return tx_egovapi_domain_model_service
	 * @throws Exception
	 */
	public function getByTopicAndIdAndVersion(tx_egovapi_domain_model_topic $topic, $id, $version = 0) {
		// TODO: should $version be taken into account for findAll()?
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
				$prerequisite->setDescription(strip_tags($itemDao['description']));
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
				$procedureItem->setDescription(strip_tags($itemDao['description']));
				$procedure->addItem($procedureItem);
			}
			$service->setProcedure($procedure);
		}
		if (isset($detailsDao['formularBlock']) && is_array($detailsDao['formularBlock'])) {
			/** @var tx_egovapi_domain_model_block_forms $forms */
			$forms = t3lib_div::makeInstance('tx_egovapi_domain_model_block_forms');
			foreach ($detailsDao['formularBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_form $form */
				$form = t3lib_div::makeInstance('tx_egovapi_domain_model_block_form');
				$form->setName(strip_tags($itemDao['formularName']));
				$form->setDescription(strip_tags($itemDao['description']));
				$form->setUri(strip_tags($itemDao['uri']));
				$forms->addItem($form);
			}
			$service->setForms($forms);
		}
		if (isset($detailsDao['documentRequiredBlock']) && is_array($detailsDao['documentRequiredBlock'])) {
			/** @var tx_egovapi_domain_model_block_documentsRequired $documentsRequired */
			$documentsRequired = t3lib_div::makeInstance('tx_egovapi_domain_model_block_documentsRequired');
			foreach ($detailsDao['documentRequiredBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_document $document */
				$document = t3lib_div::makeInstance('tx_egovapi_domain_model_block_document');
				$document->setName(strip_tags($itemDao['documentName']));
				$document->setDescription(strip_tags($itemDao['description']));
				$documentsRequired->addItem($document);
			}
			$service->setDocumentsRequired($documentsRequired);
		}
		if (isset($detailsDao['resultBlock']) && $detailsDao['resultBlock']) {
			/** @var tx_egovapi_domain_model_block_serviceProvided $serviceProvided */
			$serviceProvided = t3lib_div::makeInstance('tx_egovapi_domain_model_block_serviceProvided');
			$serviceProvided->setContent(strip_tags($detailsDao['resultBlock']));
			$service->setServiceProvided($serviceProvided);
		}
		if (isset($detailsDao['feeBlock']) && $detailsDao['feeBlock']) {
			/** @var tx_egovapi_domain_model_block_fee $fee */
			$fee = t3lib_div::makeInstance('tx_egovapi_domain_model_block_fee');
			$fee->setContent(strip_tags($detailsDao['feeBlock']));
			$service->setFee($fee);
		}
		if (isset($detailsDao['legalRegulationBlock']) && is_array($detailsDao['legalRegulationBlock'])) {
			/** @var tx_egovapi_domain_model_block_legalRegulation $legalRegulation */
			$legalRegulation = t3lib_div::makeInstance('tx_egovapi_domain_model_block_legalRegulation');
			foreach ($detailsDao['legalRegulationBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_legalRegulationItem $legalRegulationItem */
				$legalRegulationItem = t3lib_div::makeInstance('tx_egovapi_domain_model_block_legalRegulationItem');
				$legalRegulationItem->setName(strip_tags($itemDao['legalRegulationName']));
				$legalRegulationItem->setDescription(strip_tags($itemDao['description']));
				$legalRegulationItem->setUri(strip_tags($itemDao['uri']));
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
				$document->setName(strip_tags($itemDao['documentName']));
				$document->setDescription(strip_tags($itemDao['description']));
				$documentsOther->addItem($document);
			}
			$service->setDocumentsOther($documentsOther);
		}
		if (isset($detailsDao['remarkBlock']) && $detailsDao['remarkBlock']) {
			/** @var tx_egovapi_domain_model_block_remarks $remarks */
			$remarks = t3lib_div::makeInstance('tx_egovapi_domain_model_block_remarks');
			$remarks->setContent(strip_tags($detailsDao['remarkBlock']));
			$service->setRemarks($remarks);
		}
		if (isset($detailsDao['approbationBlock']) && $detailsDao['approbationBlock']) {
			/** @var tx_egovapi_domain_model_block_approval $approval */
			$approval = t3lib_div::makeInstance('tx_egovapi_domain_model_block_approval');
			$approval->setContent(strip_tags($detailsDao['approbationBlock']));
			$service->setApproval($approval);
		}
		if (isset($detailsDao['contactBlock']) && is_array($detailsDao['contactBlock'])) {
			$contactBlock = $detailsDao['contactBlock'];
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
				->setPostalCase($contactBlock['postalCase'])
				->setPostalCode($contactBlock['postalCode'])
				->setMunicipality($contactBlock['municipality'])
				->setPerson($contactBlock['person'])
				->setPhone1($contactBlock['phone1'])
				->setPhone2($contactBlock['phone2'])
				->setFax($contactBlock['fax'])
				->setEmail($contactBlock['email'])
				->setPublicKey($contactBlock['publicKey'])
				->setLogo($contactBlock['logoUrl'])
				->setBanner($contactBlock['bannerUrl'])
				->setOpeningHours($contactBlock['openingHours']);

			$service->setContact($contact);
		}

		$service->setHasDetails();
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/ServiceRepository.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/ServiceRepository.php']);
}

?>