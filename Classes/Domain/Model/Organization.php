<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011-2012 Xavier Perseguers <xavier@causal.ch>
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
 * An eGov Organization.
 *
 * @category    Domain\Model
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_organization extends tx_egovapi_domain_model_abstractEntity {

	/**
	 * @var string
	 */
	protected $canton;

	/**
	 * @var tx_egovapi_domain_model_community
	 */
	protected $community;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var float
	 */
	protected $latitude = 0.0;

	/**
	 * @var float
	 */
	protected $longitude = 0.0;

	/**
	 * @var string
	 */
	protected $website = '';

	/**
	 * Default constructor.
	 *
	 * @param string $id
	 * @param string $name
	 * @param string $canton
	 * @param tx_egovapi_domain_model_community $community
	 */
	public function __construct($id, $name = '', $canton = '', tx_egovapi_domain_model_community $community = NULL) {
		parent::__construct($id);
		$this->canton = $canton;
		$this->community = $community;
		$this->name = $name;
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
	 * @return tx_egovapi_domain_model_organization the current Organization to allow method chaining
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Returns the canton.
	 *
	 * @return string
	 */
	public function getCanton() {
		return $this->canton;
	}

	/**
	 * Sets the canton.
	 *
	 * @param string $canton
	 * @return tx_egovapi_domain_model_organization the current Organization to allow method chaining
	 */
	public function setCanton($canton) {
		$this->canton = $canton;
		return $this;
	}

	/**
	 * Returns the community.
	 *
	 * @return tx_egovapi_domain_model_community
	 */
	public function getCommunity() {
		return $this->community;
	}

	/**
	 * Sets the community.
	 *
	 * @param tx_egovapi_domain_model_community $community
	 * @return tx_egovapi_domain_model_organization the current Organization to allow method chaining
	 */
	public function setCommunity(tx_egovapi_domain_model_community $community) {
		$this->community = $community;
		return $this;
	}

	/**
	 * Returns the latitude.
	 *
	 * @return float
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * Sets the latitude.
	 *
	 * @param float $latitude
	 * @return tx_egovapi_domain_model_organization the current Organization to allow method chaining
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
		return $this;
	}

	/**
	 * Returns the longitude.
	 *
	 * @return float
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * Sets the longitude.
	 *
	 * @param float $longitude
	 * @return tx_egovapi_domain_model_organization the current Organization to allow method chaining
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
		return $this;
	}

	/**
	 * Returns the website.
	 *
	 * @return string
	 */
	public function getWebsite() {
		return $this->website;
	}

	/**
	 * Sets the website.
	 *
	 * @param string $website
	 * @return tx_egovapi_domain_model_organization the current Organization to allow method chaining
	 */
	public function setWebsite($website) {
		$this->website = $website;
		return $this;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Organization.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Organization.php']);
}

?>