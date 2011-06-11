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
 * Abstract repository for eGov API.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
abstract class tx_egovapi_domain_repository_abstractRepository implements t3lib_Singleton {

	/**
	 * @var string
	 */
	protected static $extKey = 'egovapi';

	/**
	 * @var tx_egovapi_dao_dao
	 */
	protected $dao;

	/**
	 * @var boolean
	 */
	protected $stripTags;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * Injects the DAO.
	 *
	 * @param tx_egovapi_dao_dao $dao
	 * @return void
	 */
	public function injectDao(tx_egovapi_dao_dao $dao = NULL) {
		$this->dao = $dao;
		$this->settings = $dao ? $dao->getSettings() : array();
	}

	/**
	 * Sets the stripTags flag.
	 *
	 * @param boolean $stripTags
	 * @return void
	 */
	public function setStripTags($stripTags) {
		$this->stripTags = ($stripTags ? TRUE : FALSE);
	}

	/**
	 * Gets the stripTags flag.
	 *
	 * @return boolean
	 */
	public function getStripTags() {
		return $this->stripTags;
	}

	/**
	 * Returns the absolute file name of a given TYPO3 file reference.
	 *
	 * @param string $fileName
	 * @return string
	 */
	protected function getFileName($fileName) {
		if (substr($fileName, 0, 4) === 'EXT:') {
			$newFile = '';
			list($extKey, $script) = explode('/', substr($fileName, 4), 2);
			if ($extKey && t3lib_extMgm::isLoaded($extKey)) {
				$extPath = t3lib_extMgm::extPath($extKey);
				$newFile = substr($extPath, strlen(PATH_site)) . $script;
			}
			$fileName = $newFile;
			if (@is_file(PATH_site . $newFile)) {
				$fileName = PATH_site . $newFile;
			}
		}

		return $fileName;
	}

}

?>