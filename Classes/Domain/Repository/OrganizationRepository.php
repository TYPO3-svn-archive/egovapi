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
 * An eGov Organization repository.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_organizationRepository extends tx_egovapi_domain_repository_abstractRepository {

	/**
	 * @var tx_egovapi_domain_model_organization[]
	 */
	protected static $organizations = array();

	/**
	 * @var tx_egovapi_domain_model_organization[]
	 */
	protected static $organizationsByCommunity = array();

	/**
	 * Finds all available organizations.
	 *
	 * @return tx_egovapi_domain_model_organization[]
	 */
	public function findAll() {
		if (!count(self::$organizations)) {
			/** @var $communityRepository tx_egovapi_domain_repository_communityRepository */
			$communityRepository = tx_egovapi_domain_repository_factory::getRepository('community');

			/** @var $contentObj tslib_cObj */
			$contentObj = t3lib_div::makeInstance('tslib_cObj');
			$data = $contentObj->fileResource($this->settings['data.']['organizations']);

			$lines = explode("\n", $data);
			// Remove header line
			array_shift($lines);

			self::$organizations = array();
			foreach ($lines as $line) {
				if (trim($line) === '') {
					continue;
				}
				$fields = explode(';', $line);
				$canton = $fields[0];
				$communityId = substr($fields[1], 0, 2) . '-' . substr($fields[1], 2, 2);
				$community = $communityRepository->findById($communityId);
				if (!$community) {
					throw new Exception(
						sprintf(
							'Cannot find community "%s". This typically means you have an inconsistency '
							. 'between community file (%s) and organization file (%s).',
							$communityId,
							$this->settings['data.']['communities'],
							$this->settings['data.']['organizations']
						), 1307389302
					);
				}
				$id = $fields[2];
				$name = $fields[3];

				/** @var $organization tx_egovapi_domain_model_organization */
				$organization = t3lib_div::makeInstance('tx_egovapi_domain_model_organization', $id, $name, $canton, $community);
				if (isset($fields[4]) && isset($fields[5])) {
					$organization
						->setLatitude((float) $fields[4])
						->setLongitude((float) $fields[5]);
				}
				self::$organizations[] = $organization;
			}
		}
		return self::$organizations;
	}

	/**
	 * Finds all organizations of a given community.
	 *
	 * @param tx_egovapi_domain_model_community $community
	 * @return tx_egovapi_domain_model_organization[]
	 */
	public function findByCommunity(tx_egovapi_domain_model_community $community) {
		$communityId = $community->getId();

		if (!isset(self::$organizationsByCommunity[$communityId])) {
			self::$organizationsByCommunity[$communityId] = array();

			$organizations = $this->findAll();
			foreach ($organizations as $organization) {
				$organizationCommunityId = $organization->getCommunity()->getId();

				if (substr($communityId, -3) === '-00') {
					// Canton selected => return all organizations for it
					if (substr($organizationCommunityId, 0, 3) === substr($communityId, 0, 3)) {
						self::$organizationsByCommunity[$communityId][] = $organization;
					}
				} elseif ($organizationCommunityId === $communityId) {
					self::$organizationsByCommunity[$communityId][] = $organization;
				}
			}
		}
		return self::$organizationsByCommunity[$communityId];
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/OrganizationRepository.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/OrganizationRepository.php']);
}

?>