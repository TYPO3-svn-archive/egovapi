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
 * An eGov Audience repository.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_audienceRepository extends tx_egovapi_domain_repository_abstractRepository {

	const PATTERN_ID = '/^[1-9][0-9]{2}$/';

	/**
	 * @var tx_egovapi_domain_model_audience[]
	 */
	protected static $audiences = array();

	/**
	 * Finds all audiences.
	 *
	 * @return tx_egovapi_domain_model_audience[]
	 */
	public function findAll() {
		if (self::$audiences) {
			return self::$audiences;
		}

		self::$audiences = array();
		$audiencesDao = $this->dao->getAudiences();
		foreach ($audiencesDao as $audienceDao) {
			/**
			 * @var tx_egovapi_domain_model_audience $audience
			 */
			$audience = t3lib_div::makeInstance('tx_egovapi_domain_model_audience', $audienceDao['id']);

			$audience
				->setAuthor($audienceDao['author'])
				->setCreationDate(strtotime($audienceDao['dateCreation']))
				->setLastModificationDate(strtotime($audienceDao['dateLastModification']))
				->setName($audienceDao['name']);

			self::$audiences[$audience->getId()] = $audience;
		}

		return self::$audiences;
	}

	/**
	 * Finds an audience identified by its id.
	 *
	 * @param integer $id Format XXX
	 * @return tx_egovapi_domain_model_audience
	 */
	public function findById($id) {
		$audience = null;

		if (is_integer($id)) {
			if ($id < 100 || $id > 999) {
				return $audience;
			}
		} else {
			if (!preg_match(self::PATTERN_ID, $id)) {
				return $audience;
			}
		}

		$audiences = $this->findAll();
		$audience = isset($audiences[$id]) ? $audiences[$id] : null;

		return $audience;
	}
}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/AudienceRepository.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/AudienceRepository.php']);
}

?>