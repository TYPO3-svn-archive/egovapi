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
 * A subdomains block.
 *
 * @category    Domain\Model\Block
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_block_subdomains {

	/**
	 * @var tx_egovapi_domain_model_block_subdomain[]
	 */
	protected $items;

	/**
	 * Default constructor.
	 */
	public function __construct() {
		$this->items = array();
	}

	/**
	 * Gets the items.
	 *
	 * @return tx_egovapi_domain_model_block_subdomain[]
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * Sets the items.
	 *
	 * @param tx_egovapi_domain_model_block_subdomain[] $items
	 * @return tx_egovapi_domain_model_block_subdomains
	 */
	public function setItems(array $items) {
		$this->items = $items;
		return $this;
	}

	/**
	 * Adds an item.
	 *
	 * @param tx_egovapi_domain_model_block_subdomain $item
	 * @return tx_egovapi_domain_model_block_subdomains
	 */
	public function addItem(tx_egovapi_domain_model_block_subdomain $item) {
		$this->items[] = $item;
		return $this;
	}

	/**
	 * Helper method to cast this object to a string.
	 *
	 * @return string
	 */
	public function __toString() {
		return sprintf('<ul><li>%s</li></ul>', implode('</li><li>', $this->items));
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Block/Subdomains.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/Block/Subdomains.php']);
}

?>