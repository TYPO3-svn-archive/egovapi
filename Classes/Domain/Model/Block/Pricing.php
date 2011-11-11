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
 * A pricing block.
 *
 * @category    Domain\Model\Block
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_block_pricing {

	/**
	 * @var float
	 */
	protected $price;

	/**
	 * @var float
	 */
	protected $fee;

	/**
	 * @var boolean
	 */
	protected $hasEPayment;

	/**
	 * @var integer
	 */
	protected $vatCode;

	/**
	 * @var tx_egovapi_domain_model_block_form
	 */
	protected $form;

	/**
	 * Gets the price.
	 *
	 * @return float
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * Sets the price.
	 *
	 * @param float $price
	 * @return tx_egovapi_domain_model_block_pricing
	 */
	public function setPrice($price) {
		$this->price = (float) $price;
		return $this;
	}

	/**
	 * Gets the fee.
	 *
	 * @return float
	 */
	public function getFee() {
		return $this->fee;
	}

	/**
	 * Sets the fee.
	 *
	 * @param float $fee
	 * @return tx_egovapi_domain_model_block_pricing
	 */
	public function setFee($fee) {
		$this->fee = (float) $fee;
		return $this;
	}

	/**
	 * Gets whether e-payment is enabled.
	 *
	 * @return boolean
	 */
	public function getHasEPayment() {
		return $this->hasEPayment;
	}

	/**
	 * Sets whether e-payment is enabled.
	 *
	 * @param boolean $hasEPayment
	 * @return tx_egovapi_domain_model_block_pricing
	 */
	public function setHasEPayment($hasEPayment) {
		$this->hasEPayment = $hasEPayment ? TRUE : FALSE;
		return $this;
	}

	/**
	 * Gets the VAT code.
	 *
	 * @return integer
	 */
	public function getVatCode() {
		return $this->vatCode;
	}

	/**
	 * Sets the VAT code.
	 *
	 * @param integer $vatCode
	 * @return tx_egovapi_domain_model_block_pricing
	 */
	public function setVatCode($vatCode) {
		$this->vatCode = (int) $vatCode;
		return $this;
	}

	/**
	 * Gets the form.
	 *
	 * @return tx_egovapi_domain_model_block_form
	 */
	public function getForm() {
		return $this->form;
	}

	/**
	 * Sets the form.
	 *
	 * @param tx_egovapi_domain_model_block_form $form
	 * @return tx_egovapi_domain_model_block_pricing
	 */
	public function setForm(tx_egovapi_domain_model_block_form $form) {
		$this->form = $form;
		return $this;
	}

	/**
	 * Helper method to cast this object to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		if ($this->form) {
			return sprintf(
				'%s: %s / %s',
				$this->form->getName(),
				money_format('%i', $this->price),
				money_format('%i', $this->fee)
			);
		} else {
			return sprintf(
				'%s / %s',
				money_format('%i', $this->price),
				money_format('%i', $this->fee)
			);
		}
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Block/Pricing.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Block/Pricing.php']);
}

?>