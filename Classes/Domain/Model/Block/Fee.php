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
 * A fee block.
 *
 * @category    Domain\Model\Block
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_block_fee {

	// ---- VERSION 1.0 [begin]----

	/**
	 * @var string
	 */
	protected $content;

	// ---- VERSION 1.0 [end]----

	// ---- VERSION 2.0 [begin]----

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var tx_egovapi_domain_model_block_pricing[]
	 */
	protected $pricings;

	// ---- VERSION 2.0 [end]----

	/**
	 * Default constructor.
	 */
	public function __construct() {
		$this->content = '';
		$this->pricings = array();
	}

	/**
	 * Gets the content.
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Sets the content.
	 *
	 * @param string $content
	 * @return tx_egovapi_domain_model_block_fee
	 */
	public function setContent($content) {
		$this->content = $content;
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
	 * @return tx_egovapi_domain_model_block_fee
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * Gets the prices.
	 *
	 * @return tx_egovapi_domain_model_block_pricing[]
	 */
	public function getPricings() {
		return $this->pricings;
	}

	/**
	 * Sets the pricings.
	 *
	 * @param tx_egovapi_domain_model_block_pricing[] $pricings
	 * @return tx_egovapi_domain_model_block_fee
	 */
	public function setPricings(array $pricings) {
		$this->pricings = $pricings;
		return $this;
	}

	/**
	 * Adds a pricing.
	 *
	 * @param tx_egovapi_domain_model_block_pricing $pricing
	 * @return tx_egovapi_domain_model_block_fee
	 */
	public function addPricing(tx_egovapi_domain_model_block_pricing $pricing) {
		$this->pricings[] = $pricing;
		return $this;
	}

	/**
	 * Helper method to cast this object to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		if ($this->content) {
				// Version 1.0
			return $this->content;
		}

		$output = $this->description;
		if (count($this->pricings)) {
			$output .= sprintf(' <ul><li>%s</li></ul>', implode('</li><li>', $this->pricings));
		};
		return $output;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Block/Fee.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Block/Fee.php']);
}

?>