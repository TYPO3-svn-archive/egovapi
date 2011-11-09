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
 * A contact block.
 *
 * @category    Domain\Model\Block
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_block_contact {

	/**
	 * @var string
	 */
	protected $department;

	/**
	 * @var string
	 */
	protected $office;

	/**
	 * @var string
	 */
	protected $address;

	/**
	 * @var string
	 */
	protected $postalCase;

	/**
	 * @var string
	 */
	protected $postalCode;

	/**
	 * @var string
	 */
	protected $locality;

	/**
	 * @var string
	 */
	protected $person;

	/**
	 * @var string
	 */
	protected $phone;

	/**
	 * @var string
	 */
	protected $phone2;

	/**
	 * @var string
	 */
	protected $fax;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $publicKey;

	/**
	 * @var string
	 */
	protected $logo;

	/**
	 * @var string
	 */
	protected $banner;

	/**
	 * @var string
	 */
	protected $openingHours;

	/**
	 * Gets the department.
	 *
	 * @return string
	 */
	public function getDepartment() {
		return $this->department;
	}

	/**
	 * Sets the department.
	 *
	 * @param string $department
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setDepartment($department) {
		$this->department = $department;
		return $this;
	}

	/**
	 * Gets the office.
	 *
	 * @return string
	 */
	public function getOffice() {
		return $this->office;
	}

	/**
	 * Sets the office.
	 *
	 * @param string $office
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setOffice($office) {
		$this->office = $office;
		return $this;
	}

	/**
	 * Gets the address.
	 *
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Sets the address.
	 *
	 * @param string $address
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}

	/**
	 * Gets the postal case.
	 *
	 * @return string
	 */
	public function getPostalCase() {
		return $this->postalCase;
	}

	/**
	 * Sets the postal case.
	 *
	 * @param string $postalCase
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setPostalCase($postalCase) {
		$this->postalCase = $postalCase;
		return $this;
	}

	/**
	 * Gets the postal code.
	 *
	 * @return string
	 */
	public function getPostalCode() {
		return $this->postalCode;
	}

	/**
	 * Sets the postal code.
	 *
	 * @param string $postalCode
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setPostalCode($postalCode) {
		$this->postalCode = $postalCode;
		return $this;
	}

	/**
	 * Gets the locality.
	 *
	 * @return string
	 */
	public function getLocality() {
		return $this->locality;
	}

	/**
	 * Sets the locality.
	 *
	 * @param string $locality
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setLocality($locality) {
		$this->locality = $locality;
		return $this;
	}

	/**
	 * Gets the municipality.
	 *
	 * @return string
	 * @see tx_egovapi_domain_model_block_contact::getLocality()
	 * @deprecated Use getLocality() instead. This method will be removed in egovapi 1.6
	 */
	public function getMunicipality() {
		t3lib_div::logDeprecatedFunction();
		return $this->locality;
	}

	/**
	 * Sets the municipality.
	 *
	 * @param string $municipality
	 * @return tx_egovapi_domain_model_block_contact
	 * @see tx_egovapi_domain_model_block_contact::setLocality()
	 * @deprecated Use setLocality() instead. This method will be removed in egovapi 1.6
	 */
	public function setMunicipality($municipality) {
		t3lib_div::logDeprecatedFunction();
		$this->locality = $municipality;
		return $this;
	}

	/**
	 * Gets the person.
	 *
	 * @return string
	 */
	public function getPerson() {
		return $this->person;
	}

	/**
	 * Sets the person.
	 *
	 * @param string $person
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setPerson($person) {
		$this->person = $person;
		return $this;
	}

	/**
	 * Gets the phone.
	 *
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * Sets the phone.
	 *
	 * @param string $phone
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
		return $this;
	}

	/**
	 * Gets the phone 1.
	 *
	 * @return string
	 * @see tx_egovapi_domain_model_block_contact::getPhone()
	 * @deprecated Use getPhone() instead. This method will be removed in egovapi 1.6
	 */
	public function getPhone1() {
		t3lib_div::logDeprecatedFunction();
		return $this->phone;
	}

	/**
	 * Sets the phone 1.
	 * @param string $phone1
	 * @return tx_egovapi_domain_model_block_contact
	 * @see tx_egovapi_domain_model_block_contact::setPhone()
	 * @deprecated Use setPhone() instead. This method will be removed in egovapi 1.6
	 */
	public function setPhone1($phone1) {
		t3lib_div::logDeprecatedFunction();
		$this->phone = $phone1;
		return $this;
	}

	/**
	 * Gets the phone 2.
	 *
	 * @return string
	 * @deprecated This method will be removed in egovapi 1.6
	 */
	public function getPhone2() {
		t3lib_div::logDeprecatedFunction();
		return $this->phone2;
	}

	/**
	 * Sets the phone 2.
	 *
	 * @param string $phone2
	 * @return tx_egovapi_domain_model_block_contact
	 * @deprecated This method will be removed in egovapi 1.6
	 */
	public function setPhone2($phone2) {
		t3lib_div::logDeprecatedFunction();
		$this->phone2 = $phone2;
		return $this;
	}

	/**
	 * Gets the fax.
	 *
	 * @return string
	 */
	public function getFax() {
		return $this->fax;
	}

	/**
	 * Sets the fax.
	 *
	 * @param string $fax
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setFax($fax) {
		$this->fax = $fax;
		return $this;
	}

	/**
	 * Gets the email.
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets the email.
	 *
	 * @param string $email
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}

	/**
	 * Gets the public key.
	 *
	 * @return string
	 */
	public function getPublicKey() {
		return $this->publicKey;
	}

	/**
	 * Sets the public key.
	 *
	 * @param string $publicKey
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setPublicKey($publicKey){
		$this->publicKey = $publicKey;
		return $this;
	}

	/**
	 * Gets the logo.
	 *
	 * @return string
	 */
	public function getLogo() {
		return $this->logo;
	}

	/**
	 * Sets the logo.
	 *
	 * @param string $logo
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setLogo($logo) {
		$this->logo = $logo;
		return $this;
	}

	/**
	 * Gets the banner.
	 *
	 * @return string
	 */
	public function getBanner() {
		return $this->banner;
	}

	/**
	 * Sets the banner.
	 *
	 * @param string $banner
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setBanner($banner) {
		$this->banner = $banner;
		return $this;
	}

	/**
	 * Gets the opening hours.
	 *
	 * @return string
	 */
	public function getOpeningHours() {
		return $this->openingHours;
	}

	/**
	 * Sets the opening hours.
	 *
	 * @param string $openingHours
	 * @return tx_egovapi_domain_model_block_contact
	 */
	public function setOpeningHours($openingHours) {
		$this->openingHours = $openingHours;
		return $this;
	}

	/**
	 * Helper method to cast this object to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		/** @var tslib_cObj $contentObj */
		$contentObj = t3lib_div::makeInstance('tslib_cObj');
		$emailLink = $contentObj->typoLink($this->email, array('parameter' => $this->email));

		return <<<EOT
			<table>
				<tr>
					<td>
						{$this->department}<br />
						{$this->office}<br />
						{$this->address}<br />
						{$this->postalCode} {$this->locality}<br />
						<br />
						{$this->phone}<br />
						{$this->fax}<br />
						{$emailLink}
					</td>
					<td valign="top">
						{$this->openingHours}
					</td>
				</tr>
			</table>
EOT;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Block/Contact.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Block/Contact.php']);
}

?>