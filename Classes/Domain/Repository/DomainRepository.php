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
 * An eGov Domain repository.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_domainRepository extends tx_egovapi_domain_repository_abstractRepository {

	const PATTERN_ID = '/^[1-9][0-9]{2}-[0-9]{2}-[0-9]{3}$/';

	/**
	 * @var array
	 */
	protected static $domainsByView = array();

	/**
	 * Finds all domains associated to a given view.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @return tx_egovapi_domain_model_domain[]
	 */
	public function findAll(tx_egovapi_domain_model_view $view = NULL) {
		if ($view === NULL) {
			throw new InvalidArgumentException('View cannot be null. This may be related to the use of an unsupported language for the web service.', 1299746714);
		}
		$id = $view->getId();
		if (isset(self::$domainsByView[$id])) {
			return self::$domainsByView[$id];
		}

		self::$domainsByView[$id] = array();
		$domainsDao = $this->dao->getDomains($id);
		foreach ($domainsDao as $domainDao) {
			/** @var tx_egovapi_domain_model_domain $domain */
			$domain = t3lib_div::makeInstance('tx_egovapi_domain_model_domain', $domainDao['id']);

			$domain
				->setView($view)
				->setAuthor($domainDao['author'])
				->setCreationDate(strtotime($domainDao['dateCreation']))
				->setLastModificationDate(strtotime($domainDao['dateLastModification']))
				->setName($domainDao['name'])
				->setDescription($domainDao['description'])
				->setIsParent($domainDao['isParent'] == '1' ? TRUE : FALSE)
				->setVersionId(intval($domainDao['versionId']))
				->setVersionName($domainDao['versionName'])
				->setCommunityId($domainDao['communityId'])
				->setRelease(intval($domainDao['release']))
				->setRemarks($domainDao['remarks'])
				->setStatus($domainDao['status'])
			;
			self::$domainsByView[$id][$domain->getId()] = $domain;
		}

		return self::$domainsByView[$id];
	}

	/**
	 * Finds a domain identified by its id.
	 *
	 * @param string $id Format XXX-XX-YYY where XXX-XX is a view id
	 * @return tx_egovapi_domain_model_domain
	 */
	public function findById($id) {
		$domain = null;

		if (!preg_match(self::PATTERN_ID, $id)) {
			return $domain;
		}

		$viewId = substr($id, 0, 6);
		/** @var tx_egovapi_domain_repository_viewRepository $viewRepository */
		$viewRepository = tx_egovapi_domain_repository_factory::getRepository('view');
		$view = $viewRepository->findById($viewId);

		if ($view) {
			$domains = $this->findAll($view);
			$domain = isset($domains[$id]) ? $domains[$id] : null;
		}

		return $domain;
	}

	/**
	 * Injects the details of a given domain into the domain object itself.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @return void
	 */
	public function injectDetails(tx_egovapi_domain_model_domain $domain) {
		$detailsDao = $this->dao->getDomainDetails(
			$domain->getId(),
			$domain->getVersionId(),
			$domain->isParent()
		);

		if (isset($detailsDao['generalInformationBlock']) && $detailsDao['generalInformationBlock']) {
			/** @var tx_egovapi_domain_model_block_generalInformation $generalInformation */
			$generalInformation = t3lib_div::makeInstance('tx_egovapi_domain_model_block_generalInformation');
			$generalInformation->setContent($detailsDao['generalInformationBlock']);
			$domain->setGeneralInformation($generalInformation);
		}
		if (isset($detailsDao['newsBlock']) && $detailsDao['newsBlock']) {
			/** @var tx_egovapi_domain_model_block_news $news */
			$news = t3lib_div::makeInstance('tx_egovapi_domain_model_block_news');

			if ($this->stripTags) {
				$news->setContent(strip_tags($detailsDao['newsBlock']));
			} else {
				$news->setContent($detailsDao['newsBlock']);
			}

			$domain->setNews($news);
		}
		if (isset($detailsDao['subdomainBlock']) && is_array($detailsDao['subdomainBlock'])) {
			/** @var tx_egovapi_domain_model_block_subdomains $subdomains */
			$subdomains = t3lib_div::makeInstance('tx_egovapi_domain_model_block_subdomains');
			foreach ($detailsDao['subdomainBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_subdomain $subdomain */
				$subdomain = t3lib_div::makeInstance('tx_egovapi_domain_model_block_subdomain');

				if ($this->stripTags) {
					$subdomain->setContent(strip_tags($itemDao));
				} else {
					$subdomain->setContent($itemDao);
				}

				$subdomains->addItem($subdomain);
			}
			$domain->setSubdomains($subdomains);
		}
		if (isset($detailsDao['descriptorBlock']) && $detailsDao['descriptorBlock']) {
			/** @var tx_egovapi_domain_model_block_descriptor $descriptor */
			$descriptor = t3lib_div::makeInstance('tx_egovapi_domain_model_block_descriptor');

			if ($this->stripTags) {
				$descriptor->setContent(strip_tags($detailsDao['descriptorBlock']));
			} else {
				$descriptor->setContent($detailsDao['descriptorBlock']);
			}

			$domain->setDescriptor($descriptor);
		}
		if (isset($detailsDao['synonymBlock']) && $detailsDao['synonymBlock']) {
			/** @var tx_egovapi_domain_model_block_synonym $synonym */
			$synonym = t3lib_div::makeInstance('tx_egovapi_domain_model_block_synonym');

			if ($this->stripTags) {
				$synonym->setContent(strip_tags($detailsDao['synonymBlock']));
			} else {
				$synonym->setContent($detailsDao['synonymBlock']);
			}

			$domain->setSynonym($synonym);
		}

		$domain->setHasDetails();
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/DomainRepository.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/DomainRepository.php']);
}

?>