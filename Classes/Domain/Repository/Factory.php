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
 * Factory repository for eGov API.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_factory {

	/**
	 * @var tx_egovapi_dao_dao
	 */
	protected static $dao;

	/**
	 * @var boolean
	 */
	protected static $stripTags;

	/**
	 * Injects DAO.
	 *
	 * @param tx_egovapi_dao_dao $dao
	 * @return void
	 */
	public static function injectDao(tx_egovapi_dao_dao $dao) {
		self::$dao = $dao;
	}

	/**
	 * Returns the current DAO.
	 *
	 * @return tx_egovapi_dao_dao
	 */
	public static function getDao() {
		return self::$dao;
	}

	/**
	 * Sets the stripTags flag.
	 *
	 * @param boolean $stripTags
	 * @return void
	 */
	public static function setStripTags($stripTags) {
		self::$stripTags = ($stripTags ? TRUE : FALSE);
	}

	/**
	 * Gets the stripTags flag.
	 *
	 * @return boolean
	 */
	public static function getStripTags() {
		return self::$stripTags;
	}

	/**
	 * Returns a repository.
	 *
	 * @param string $name
	 * @return tx_egovapi_domain_repository_abstractRepository
	 */
	public static function getRepository($name) {
		$classPattern = 'tx_egovapi_domain_repository_%sRepository';

		/** @var $repository tx_egovapi_domain_repository_abstractRepository */
		$repository = t3lib_div::makeInstance(sprintf($classPattern, $name));
		$repository->injectDao(self::$dao);
		$repository->setStripTags(self::$stripTags);

		return $repository;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/Factory.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/Factory.php']);
}

?>