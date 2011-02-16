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
 * An eGov Service.
 *
 * @category    Domain\Model
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_service extends tx_egovapi_domain_model_abstractEntity {

	/**
	 * The parent topic.
	 *
	 * @var tx_egovapi_domain_model_topic
	 */
	protected $topic = null;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var integer
	 */
	protected $versionId;

	/**
	 * @var string
	 */
	protected $versionName;

	/**
	 * @var string
	 */
	protected $communityId;

	/**
	 * @var integer
	 */
	protected $release;

	/**
	 * @var string
	 */
	protected $comments;

	/**
	 * @var string
	 */
	protected $provider;

	/**
	 * @var string
	 */
	protected $customer;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $action;

	/**
	 * @var string
	 */
	protected $status;

	/**
	 * @var tx_egovapi_domain_model_block_generalInformation
	 */
	protected $generalInformation;

	/**
	 * @var tx_egovapi_domain_model_block_prerequisites
	 */
	protected $prerequisites;

	/**
	 * @var tx_egovapi_domain_model_block_procedure
	 */
	protected $procedure;

	/**
	 * @var tx_egovapi_domain_model_block_forms
	 */
	protected $forms;

	/**
	 * @var tx_egovapi_domain_model_block_documentsRequired
	 */
	protected $documentsRequired;

	/**
	 * @var tx_egovapi_domain_model_block_serviceProvided
	 */
	protected $serviceProvided;

	/**
	 * @var tx_egovapi_domain_model_block_fee
	 */
	protected $fee;

	/**
	 * @var tx_egovapi_domain_model_block_legalRegulation
	 */
	protected $legalRegulation;

	/**
	 * @var tx_egovapi_domain_model_block_documentsOther
	 */
	protected $documentsOther;

	/**
	 * @var tx_egovapi_domain_model_block_remarks
	 */
	protected $remarks;

	/**
	 * @var tx_egovapi_domain_model_block_approval
	 */
	protected $approval;

	/**
	 * @var tx_egovapi_domain_model_block_contact
	 */
	protected $contact;

	/**
	 * Gets the parent topic.
	 *
	 * @return tx_egovapi_domain_model_topic
	 */
	public function getTopic() {
		return $this->topic;
	}

	/**
	 * Sets the parent topic.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setTopic(tx_egovapi_domain_model_topic $topic) {
		$this->topic = $topic;
		return $this;
	}

	/**
	 * Gets the name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name.
	 *
	 * @param string $name
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Gets the description.
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description.
	 *
	 * @param string $description
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * Gets the version id.
	 *
	 * @return integer
	 */
	public function getVersionId() {
		return $this->versionId;
	}

	/**
	 * Sets the version id.
	 *
	 * @param integer $versionId
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setVersionId($versionId) {
		$this->versionId = intval($versionId);
		return $this;
	}

	/**
	 * Gets the version name.
	 *
	 * @return string
	 */
	public function getVersionName() {
		return $this->versionName;
	}

	/**
	 * Sets the version name.
	 *
	 * @param string $versionName
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setVersionName($versionName) {
		$this->versionName = $versionName;
		return $this;
	}

	/**
	 * Gets the community id.
	 *
	 * @return string
	 */
	public function getCommunityId() {
		return $this->communityId;
	}

	/**
	 * Sets the community id.
	 *
	 * @param string $communityId
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setCommunityId($communityId) {
		$this->communityId = $communityId;
		return $this;
	}

	/**
	 * Gets the release.
	 *
	 * @return integer
	 */
	public function getRelease() {
		return $this->release;
	}

	/**
	 * Sets the release.
	 *
	 * @param integer $release
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setRelease($release) {
		$this->release = intval($release);
		return $this;
	}

	/**
	 * Gets the comments.
	 *
	 * @return string
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Sets the comments.
	 *
	 * @param string $comments
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setComments($comments) {
		$this->comments = $comments;
		return $this;
	}

	/**
	 * Gets the provider.
	 *
	 * @return string
	 */
	public function getProvider() {
		return $this->provider;
	}

	/**
	 * Sets the provider.
	 *
	 * @param string $provider
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setProvider($provider) {
		$this->provider = $provider;
		return $this;
	}

	/**
	 * Gets the customer.
	 *
	 * @return string
	 */
	public function getCustomer() {
		return $this->customer;
	}

	/**
	 * Sets the customer.
	 *
	 * @param string $customer
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setCustomer($customer) {
		$this->customer = $customer;
		return $this;
	}

	/**
	 * Gets the type.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the type.
	 *
	 * @param string $type
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * Gets the action.
	 *
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Sets the action.
	 *
	 * @param string $action
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setAction($action) {
		$this->action = $action;
		return $this;
	}

	/**
	 * Gets the status.
	 *
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Sets the status.
	 *
	 * @param string $status
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}

	/**
	 * Gets the general information.
	 *
	 * @return tx_egovapi_domain_model_block_generalInformation
	 * @lazy
	 */
	public function getGeneralInformation() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->generalInformation;
	}

	/**
	 * Sets the general information.
	 *
	 * @param tx_egovapi_domain_model_block_generalInformation $generalInformation
	 * @return tx_egovapi_domain_model_topic the current Service to allow method chaining
	 */
	public function setGeneralInformation(tx_egovapi_domain_model_block_generalInformation $generalInformation) {
		$this->generalInformation = $generalInformation;
		return $this;
	}

	/**
	 * Gets the prerequisites.
	 *
	 * @return tx_egovapi_domain_model_block_prerequisites
	 * @lazy
	 */
	public function getPrerequisites() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->prerequisites;
	}

	/**
	 * Sets the prerequisites.
	 *
	 * @param tx_egovapi_domain_model_block_prerequisites $prerequisites
	 * @return tx_egovapi_domain_model_service the current Service to allow method chaining
	 */
	public function setPrerequisites(tx_egovapi_domain_model_block_prerequisites $prerequisites) {
		$this->prerequisites = $prerequisites;
		return $this;
	}

	/**
	 * Gets the procedure.
	 *
	 * @return tx_egovapi_domain_model_block_procedure
	 * @lazy
	 */
	public function getProcedure() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->procedure;
	}

	/**
	 * Sets the procedure.
	 *
	 * @param tx_egovapi_domain_model_block_procedure $procedure
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setProcedure(tx_egovapi_domain_model_block_procedure $procedure) {
		$this->procedure = $procedure;
		return $this;
	}

	/**
	 * Gets the forms.
	 *
	 * @return tx_egovapi_domain_model_block_forms
	 * @lazy
	 */
	public function getForms() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->forms;
	}

	/**
	 * Sets the forms.
	 *
	 * @param tx_egovapi_domain_model_block_forms $forms
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setForms(tx_egovapi_domain_model_block_forms $forms) {
		$this->forms = $forms;
		return $this;
	}

	/**
	 * Gets the documents required.
	 *
	 * @return tx_egovapi_domain_model_block_documentsRequired
	 * @lazy
	 */
	public function getDocumentsRequired() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->documentsRequired;
	}

	/**
	 * Sets the documents required.
	 *
	 * @param tx_egovapi_domain_model_block_documentsRequired $documentsRequired
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setDocumentsRequired(tx_egovapi_domain_model_block_documentsRequired $documentsRequired) {
		$this->documentsRequired = $documentsRequired;
		return $this;
	}

	/**
	 * Gets the service provided.
	 *
	 * @return tx_egovapi_domain_model_block_serviceProvided
	 * @lazy
	 */
	public function getServiceProvided() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->serviceProvided;
	}

	/**
	 * Sets the service provided.
	 *
	 * @param tx_egovapi_domain_model_block_serviceProvided $serviceProvided
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setServiceProvided(tx_egovapi_domain_model_block_serviceProvided $serviceProvided) {
		$this->serviceProvided = $serviceProvided;
		return $this;
	}

	/**
	 * Gets the fee.
	 *
	 * @return tx_egovapi_domain_model_block_fee
	 * @lazy
	 */
	public function getFee() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->fee;
	}

	/**
	 * Sets the fee.
	 *
	 * @param tx_egovapi_domain_model_block_fee $fee
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setFee(tx_egovapi_domain_model_block_fee $fee) {
		$this->fee = $fee;
		return $this;
	}

	/**
	 * Gets the legal regulation.
	 *
	 * @return tx_egovapi_domain_model_block_legalRegulation
	 * @lazy
	 */
	public function getLegalRegulation() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->legalRegulation;
	}

	/**
	 * Sets the legal regulation.
	 *
	 * @param tx_egovapi_domain_model_block_legalRegulation $legalRegulation
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setLegalRegulation(tx_egovapi_domain_model_block_legalRegulation $legalRegulation) {
		$this->legalRegulation = $legalRegulation;
		return $this;
	}

	/**
	 * Gets the documents other.
	 *
	 * @return tx_egovapi_domain_model_block_documentsOther
	 * @lazy
	 */
	public function getDocumentsOther() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->documentsOther;
	}

	/**
	 * Sets the documents other.
	 *
	 * @param tx_egovapi_domain_model_block_documentsOther $documentOther
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setDocumentsOther(tx_egovapi_domain_model_block_documentsOther $documentsOther) {
		$this->documentsOther = $documentsOther;
		return $this;
	}

	/**
	 * Gets the remarks.
	 *
	 * @return tx_egovapi_domain_model_block_remarks
	 * @lazy
	 */
	public function getRemarks() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->remarks;
	}

	/**
	 * Sets the remarks.
	 *
	 * @param tx_egovapi_domain_model_block_remarks $remarks
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setRemarks(tx_egovapi_domain_model_block_remarks $remarks) {
		$this->remarks = $remarks;
		return $this;
	}

	/**
	 * Gets the approval.
	 *
	 * @return tx_egovapi_domain_model_block_approval
	 * @lazy
	 */
	public function getApproval() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->approval;
	}

	/**
	 * Sets the approval.
	 *
	 * @param tx_egovapi_domain_model_block_approval $approval
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setApproval(tx_egovapi_domain_model_block_approval $approval) {
		$this->approval = $approval;
		return $this;
	}

	/**
	 * Gets the contact.
	 *
	 * @return tx_egovapi_domain_model_block_contact
	 * @lazy
	 */
	public function getContact() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->contact;
	}

	/**
	 * Sets the contact.
	 *
	 * @param tx_egovapi_domain_model_block_contact $contact
	 * @return tx_egovapi_domain_model_service the current service to allow method chaining
	 */
	public function setContact(tx_egovapi_domain_model_block_contact $contact) {
		$this->contact = $contact;
		return $this;
	}

	/**
	 * Asks the repository to inject details of this service.
	 *
	 * @return void
	 */
	protected function injectDetails() {
		/** @var tx_egovapi_domain_repository_serviceRepository $serviceRepository */
		$serviceRepository = tx_egovapi_domain_repository_factory::getRepository('service');
		$serviceRepository->injectDetails($this);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Service.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Service.php']);
}

?>