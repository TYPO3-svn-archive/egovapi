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
 * An eGov Community repository.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_communityRepository extends tx_egovapi_domain_repository_abstractRepository {

	/**
	 * @var tx_egovapi_domain_model_community[]
	 */
	protected static $communities = array();

	/**
	 * Finds all available communities.
	 *
	 * @return tx_egovapi_domain_model_community[]
	 */
	public function findAll() {
		if (!count(self::$communities)) {
			$fileName = $this->getFileName($this->settings['data.']['communities']);
			if (!$fileName) {
				return array();
			}
			$data = t3lib_div::getURL($fileName);

			$lines = explode("\n", $data);
			// Remove header line
			array_shift($lines);

			self::$communities = array();
			foreach ($lines as $line) {
				if (trim($line) === '') {
					continue;
				}
				$fields = explode(';', $line);
				$id = substr($fields[0], 0, 2) . '-' . substr($fields[0], 2, 2);
				$name = $fields[1];

				$community = t3lib_div::makeInstance('tx_egovapi_domain_model_community', $id, $name);
				self::$communities[$id] = $community;
			}
		}
		return self::$communities;
	}

	/**
	 * Finds a community given its identifier.
	 *
	 * @param string $id
	 * @return tx_egovapi_domain_model_community
	 */
	public function findById($id) {
		$communities = $this->findAll();
		return isset($communities[$id]) ? $communities[$id] : NULL;
	}

	/**
	 * Returns the list of changes for a given community since a given timestamp.
	 *
	 * @param tx_egovapi_domain_model_community $community
	 * @param integer $since Timestamp
	 * @return boolean
	 * @throws RuntimeException
	 */
	public function getLatestChanges(tx_egovapi_domain_model_community $community, $since) {
		$latestChanges = array();
		foreach (t3lib_div::trimExplode(',', 'DE,EN,FR,IT,RM') as $language) {
			$changes = $this->dao->getLatestChanges($community->getId(), $since, $language);
			if (!is_array($changes)) {
				throw new RuntimeException('Method getLatestChanges failed.', 1308921853);
			}
			$latestChanges = array_merge($latestChanges, $changes);
		}

		return $latestChanges;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/CommunityRepository.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/CommunityRepository.php']);
}

?>