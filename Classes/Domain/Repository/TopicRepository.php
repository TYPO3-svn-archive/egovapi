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
 * An eGov Topic repository.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_topicRepository extends tx_egovapi_domain_repository_abstractRepository {

	const PATTERN_ID = '/^[1-9][0-9]{2}-[0-9]{2}-[0-9]{3}-[0-9]{3}$/';

	/**
	 * @var array
	 */
	protected static $topicsByDomain = array();

	/**
	 * Finds all topics associated to a given domain.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @return tx_egovapi_domain_model_topic[]
	 */
	public function findAll(tx_egovapi_domain_model_domain $domain = NULL) {
		if ($domain === NULL) {
			throw new InvalidArgumentException('Domain cannot be null. This may be related to the use of an unsupported language for the web service.', 1299746830);
		}
		$id = $domain->getId();
		if (isset(self::$topicsByDomain[$id])) {
			return self::$topicsByDomain[$id];
		}

		self::$topicsByDomain[$id] = array();
		$topicsDao = $this->dao->getTopics($id);
		foreach ($topicsDao as $topicDao) {
			/** @var tx_egovapi_domain_model_topic $topic */
			$topic = t3lib_div::makeInstance('tx_egovapi_domain_model_topic', $topicDao['id']);

			$topic
				->setDomain($domain)
				->setAuthor($topicDao['author'])
				->setCreationDate(strtotime($topicDao['dateCreation']))
				->setLastModificationDate(strtotime($topicDao['dateLastModification']))
				->setName($topicDao['name'])
				->setDescription($topicDao['description'])
				->setIsParent($topicDao['isParent'] == '1' ? TRUE : FALSE)
				->setVersionId(intval($topicDao['versionId']))
				->setVersionName($topicDao['versionName'])
				->setCommunityId($topicDao['communityId'])
				->setRelease(intval($topicDao['release']))
				->setRemarks($topicDao['remarks'])
				->setStatus($topicDao['status'])
			;
			self::$topicsByDomain[$id][$topic->getId()] = $topic;
		}

		return self::$topicsByDomain[$id];
	}

	/**
	 * Finds a topic identified by its id.
	 *
	 * @param string $id Format XXX-XX-XXX-YYY where XXX-XX-XXX is a domain id
	 * @return tx_egovapi_domain_model_topic
	 */
	public function findById($id) {
		$topic = null;

		if (!preg_match(self::PATTERN_ID, $id)) {
			return $topic;
		}

		$domainId = substr($id, 0, 10);
		/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
		$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');
		$domain = $domainRepository->findById($domainId);

		if ($domain) {
			$topics = $this->findAll($domain);
			$topic = isset($topics[$id]) ? $topics[$id] : null;
		}

		return $topic;
	}

	/**
	 * Injects the details of a given topic into the topic object itself.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @return void
	 */
	public function injectDetails(tx_egovapi_domain_model_topic $topic) {
		$detailsDao = $this->dao->getTopicDetails(
			$topic->getId(),
			$topic->getVersionId(),
			$topic->isParent()
		);

		if (isset($detailsDao['generalInformationBlock']) && $detailsDao['generalInformationBlock']) {
			/** @var tx_egovapi_domain_model_block_generalInformation $generalInformation */
			$generalInformation = t3lib_div::makeInstance('tx_egovapi_domain_model_block_generalInformation');
			$generalInformation->setContent($detailsDao['generalInformationBlock']);
			$topic->setGeneralInformation($generalInformation);
		}
		if (isset($detailsDao['newsBlock']) && $detailsDao['newsBlock']) {
			/** @var tx_egovapi_domain_model_block_news $news */
			$news = t3lib_div::makeInstance('tx_egovapi_domain_model_block_news');
			$news->setContent(strip_tags($detailsDao['newsBlock']));
			$topic->setNews($news);
		}
		if (isset($detailsDao['subtopicBlock']) && is_array($detailsDao['subtopicBlock'])) {
			/** @var tx_egovapi_domain_model_block_subtopics $subtopics */
			$subtopics = t3lib_div::makeInstance('tx_egovapi_domain_model_block_subtopics');
			foreach ($detailsDao['subtopicBlock'] as $itemDao) {
				/** @var tx_egovapi_domain_model_block_subtopic $subtopic */
				$subtopic = t3lib_div::makeInstance('tx_egovapi_domain_model_block_subtopic');
				$subtopic->setContent(strip_tags($itemDao));
				$subtopics->addItem($subtopic);
			}
			$topic->setSubtopics($subtopics);
		}
		if (isset($detailsDao['descriptorBlock']) && $detailsDao['descriptorBlock']) {
			/** @var tx_egovapi_domain_model_block_descriptor $descriptor */
			$descriptor = t3lib_div::makeInstance('tx_egovapi_domain_model_block_descriptor');
			$descriptor->setContent(strip_tags($detailsDao['descriptorBlock']));
			$topic->setDescriptor($descriptor);
		}
		if (isset($detailsDao['synonymBlock']) && $detailsDao['synonymBlock']) {
			/** @var tx_egovapi_domain_model_block_synonym $synonym */
			$synonym = t3lib_div::makeInstance('tx_egovapi_domain_model_block_synonym');
			$synonym->setContent(strip_tags($detailsDao['synonymBlock']));
			$topic->setSynonym($synonym);
		}

		$topic->setHasDetails();
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/TopicRepository.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/TopicRepository.php']);
}

?>