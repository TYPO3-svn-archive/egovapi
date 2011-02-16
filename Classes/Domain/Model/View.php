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
 * An eGov View.
 *
 * @category    Domain\Model
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_model_view extends tx_egovapi_domain_model_abstractEntity {

	/**
	 * The parent audience.
	 *
	 * @var tx_egovapi_domain_model_audience
	 */
	protected $audience = null;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * The domains.
	 *
	 * @var tx_egovapi_domain_model_domain[]
	 */
	protected $domains = null;

	/**
	 * Gets the parent audience.
	 *
	 * @return tx_egovapi_domain_model_audience
	 */
	public function getAudience() {
		return $this->audience;
	}

	/**
	 * Sets the parent audience.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @return tx_egovapi_domain_model_view the current View to allow method chaining
	 */
	public function setAudience(tx_egovapi_domain_model_audience $audience) {
		$this->audience = $audience;
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
	 * @return tx_egovapi_domain_model_view the current View to allow method chaining
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Gets the domains.
	 *
	 * @return tx_egovapi_domain_model_domain[]
	 */
	public function getDomains() {
		if ($this->domains == null) {
			/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
			$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');
			$this->domains = $domainRepository->findAll($this);
		}
		return $this->domains;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/View.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Model/View.php']);
}

?>