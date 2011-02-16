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
 * An eGov View repository.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_viewRepository extends tx_egovapi_domain_repository_abstractRepository {

	const PATTERN_ID = '/^[1-9][0-9]{2}-[0-9]{2}$/';

	/**
	 * @var array
	 */
	protected static $viewsByAudience = array();

	/**
	 * Finds all views associated to a given audience.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @return tx_egovapi_domain_model_view[]
	 */
	public function findAll(tx_egovapi_domain_model_audience $audience) {
		$id = $audience->getId();
		if (isset(self::$viewsByAudience[$id])) {
			return self::$viewsByAudience[$id];
		}

		self::$viewsByAudience[$id] = array();
		$viewsDao = $this->dao->getViews($id);
		foreach ($viewsDao as $viewDao) {
			/** @var tx_egovapi_domain_model_view $view */
			$view = t3lib_div::makeInstance('tx_egovapi_domain_model_view', $viewDao['id']);

			$view
				->setAudience($audience)
				->setAuthor($viewDao['author'])
				->setCreationDate(strtotime($viewDao['dateCreation']))
				->setLastModificationDate(strtotime($viewDao['dateLastModification']))
				->setName($viewDao['name'])
			;
			self::$viewsByAudience[$id][$view->getId()] = $view;
		}

		return self::$viewsByAudience[$id];
	}

	/**
	 * Finds a view identified by its id.
	 *
	 * @param string $id Format XXX-YY where XXX is an audience id
	 * @return tx_egovapi_domain_model_view
	 */
	public function findById($id) {
		$view = null;

		if (!preg_match(self::PATTERN_ID, $id)) {
			return $view;
		}

		$audienceId = intval(substr($id, 0, 3));
		/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
		$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');
		$audience = $audienceRepository->findById($audienceId);

		if ($audience) {
			$views = $this->findAll($audience);
			$view = isset($views[$id]) ? $views[$id] : null;
		}

		return $view;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/ViewRepository.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/ViewRepository.php']);
}

?>