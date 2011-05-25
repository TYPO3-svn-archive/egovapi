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
 * An eGov Domain.
 *
 * @category    Domain\Model
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_domain extends tx_egovapi_domain_model_abstractEntity {

	/**
	 * The parent view.
	 *
	 * @var tx_egovapi_domain_model_view
	 */
	protected $view = null;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var boolean
	 */
	protected $isParent;

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
	protected $remarks;

	/**
	 * @var string
	 */
	protected $status;

	/**
	 * @var tx_egovapi_domain_model_block_generalInformation
	 */
	protected $generalInformation;

	/**
	 * @var tx_egovapi_domain_model_block_news
	 */
	protected $news;

	/**
	 * @var tx_egovapi_domain_model_block_subdomains
	 */
	protected $subdomains;

	/**
	 * @var tx_egovapi_domain_model_block_descriptor
	 */
	protected $descriptor;

	/**
	 * @var tx_egovapi_domain_model_block_synonym
	 */
	protected $synonym;

	/**
	 * The topics.
	 *
	 * @var tx_egovapi_domain_model_topic[]
	 */
	protected $topics = null;

	/**
	 * Gets the parent view.
	 *
	 * @return tx_egovapi_domain_model_view
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * Sets the parent view.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @return tx_egovapi_domain_model_domain the current View to allow method chaining
	 */
	public function setView(tx_egovapi_domain_model_view $view) {
		$this->view = $view;
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
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
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
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * Gets the is parent flag.
	 *
	 * @return boolean
	 */
	public function getIsParent() {
		return $this->isParent;
	}

	/**
	 * Returns whether the domain has the is parent flag.
	 *
	 * @return boolean
	 */
	public function isParent() {
		return $this->isParent;
	}

	/**
	 * Sets the is parent flag.
	 *
	 * @param boolean $isParent
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
	 */
	public function setIsParent($isParent) {
		$this->isParent = $isParent ? TRUE : FALSE;
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
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
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
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
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
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
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
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
	 */
	public function setRelease($release) {
		$this->release = intval($release);
		return $this;
	}

	/**
	 * Gets the remarks.
	 *
	 * @return string
	 */
	public function getRemarks() {
		return $this->remarks;
	}

	/**
	 * Sets the remarks.
	 *
	 * @param string $remarks
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
	 */
	public function setRemarks($remarks) {
		$this->remarks = $remarks;
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
	 * @return tx_egovapi_domain_model_domain the current Domain to allow method chaining
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
	 * @return tx_egovapi_domain_model_domain the current domain to allow method chaining
	 */
	public function setGeneralInformation(tx_egovapi_domain_model_block_generalInformation $generalInformation) {
		$this->generalInformation = $generalInformation;
		return $this;
	}

	/**
	 * Gets the news.
	 *
	 * @return tx_egovapi_domain_model_block_news
	 * @lazy
	 */
	public function getNews() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->news;
	}

	/**
	 * Sets the news.
	 *
	 * @param tx_egovapi_domain_model_block_news $news
	 * @return tx_egovapi_domain_model_domain the current domain to allow method chaining
	 */
	public function setNews(tx_egovapi_domain_model_block_news $news) {
		$this->news = $news;
		return $this;
	}

	/**
	 * Gets the subdomains.
	 *
	 * @return tx_egovapi_domain_model_block_subdomains
	 * @lazy
	 */
	public function getSubdomains() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->subdomains;
	}

	/**
	 * Sets the subdomains.
	 *
	 * @param tx_egovapi_domain_model_block_subdomains $subdomains
	 * @return tx_egovapi_domain_model_domain the current domain to allow method chaining
	 */
	public function setSubdomains(tx_egovapi_domain_model_block_subdomains $subdomains) {
		$this->subdomains = $subdomains;
		return $this;
	}

	/**
	 * Gets the descriptor.
	 *
	 * @return tx_egovapi_domain_model_block_descriptor
	 * @lazy
	 */
	public function getDescriptor() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->descriptor;
	}

	/**
	 * Sets the descriptor.
	 *
	 * @param tx_egovapi_domain_model_block_descriptor $descriptor
	 * @return tx_egovapi_domain_model_domain the current domain to allow method chaining
	 */
	public function setDescriptor(tx_egovapi_domain_model_block_descriptor $descriptor) {
		$this->descriptor = $descriptor;
		return $this;
	}

	/**
	 * Gets the synonym.
	 *
	 * @return tx_egovapi_domain_model_block_synonym
	 * @lazy
	 */
	public function getSynonym() {
		if (!$this->hasDetails) {
			$this->injectDetails();
		}
		return $this->synonym;
	}

	/**
	 * Sets the synonym.
	 *
	 * @param tx_egovapi_domain_model_block_synonym $synonym
	 * @return tx_egovapi_domain_model_domain the current domain to allow method chaining
	 */
	public function setSynonym(tx_egovapi_domain_model_block_synonym $synonym) {
		$this->synonym = $synonym;
		return $this;
	}

	/**
	 * Gets the topics.
	 *
	 * @param boolean $cache
	 * @return tx_egovapi_domain_model_topic[]
	 */
	public function getTopics($cache = TRUE) {
		if ($this->topics == null) {
			/** @var tx_egovapi_domain_repository_topicRepository $topicRepository */
			$topicRepository = tx_egovapi_domain_repository_factory::getRepository('topic');
			$this->topics = $topicRepository->findAll($this, $cache);
		}
		return $this->topics;
	}

	/**
	 * Asks the repository to inject details of this domain.
	 *
	 * @return void
	 */
	protected function injectDetails() {
		/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
		$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');
		$domainRepository->injectDetails($this);
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Domain.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Domain.php']);
}

?>