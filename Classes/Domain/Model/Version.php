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
 * An eGov Service Version.
 *
 * @category    Domain\Model
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_version extends tx_egovapi_domain_model_abstractEntity {

	/**
	 * @var string
	 */
	protected $communityId;

	/**
	 * @var tx_egovapi_domain_model_service
	 */
	protected $service;

	/**
	 * @var name
	 */
	protected $name;

	/**
	 * @var integer
	 * @see tx_egovapi_domain_model_constants
	 */
	protected $status;

	/**
	 * @var boolean
	 */
	protected $isDefault;

	/**
	 * Gets the community ID.
	 *
	 * @return string
	 */
	public function getCommunityId() {
		return $this->communityId;
	}

	/**
	 * Sets the community ID.
	 *
	 * @param string $communityId
	 * @return tx_egovapi_domain_model_version the current Version to allow method chaining
	 */
	public function setCommunityId($communityId) {
		$this->communityId = $communityId;
		return $this;
	}

	/**
	 * Gets the service.
	 *
	 * @return tx_egovapi_domain_model_service
	 */
	public function getService() {
		return $this->service;
	}

	/**
	 * Sets the service.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return tx_egovapi_domain_model_version the current Version to allow method chaining
	 */
	public function setService(tx_egovapi_domain_model_service $service) {
		$this->service = $service;
		return $this;
	}

	/**
	 * Gets the name.
	 *
	 * @return name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name.
	 *
	 * @param string $name
	 * @return tx_egovapi_domain_model_version the current Version to allow method chaining
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Returns the status.
	 *
	 * @return integer
	 * @see tx_egovapi_domain_model_constants
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Sets the status.
	 *
	 * @param integer $status
	 * @return tx_egovapi_domain_model_version the current Version to allow method chaining
	 * @see tx_egovapi_domain_model_constants
	 */
	public function setStatus($status) {
		$this->status = (int) $status;
		return $this;
	}

	/**
	 * Gets the is default flag.
	 *
	 * @return boolean
	 */
	public function getIsDefault() {
		return $this->isDefault;
	}

	/**
	 * Returns whether the version is the default one.
	 *
	 * @return boolean
	 */
	public function isDefault() {
		return $this->isDefault();
	}

	/**
	 * Sets the is default flag.
	 *
	 * @param boolean $isDefault
	 * @return tx_egovapi_domain_model_version the current Version to allow method chaining
	 */
	public function setIsDefault($isDefault) {
		$this->isDefault = $isDefault ? TRUE : FALSE;
		return $this;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Version.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Version.php']);
}

?>