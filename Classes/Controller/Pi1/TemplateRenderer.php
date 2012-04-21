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
 * Template renderer for plugin 'eGov API' for the 'egovapi' extension.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_controller_pi1_templateRenderer extends tx_egovapi_controller_pi1_abstractRenderer {

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var array
	 */
	protected $subparts;

	/**
	 * @var array
	 */
	protected $markers;

	/**
	 * @var array
	 */
	protected $deprecatedMarkers;

	/**
	 * Renders the output of plugin Pi1.
	 *
	 * @return string
	 */
	public function render() {
		$this->initializeRender();

		// Hook for post-processing the subparts and markers
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['renderHook'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['renderHook'] as $classRef) {
				/** @var tx_egovapi_interfaces_template_renderHook $hookObject */
				$hookObject = t3lib_div::getUserObj($classRef);

				if (!($hookObject instanceof tx_egovapi_interfaces_template_renderHook)) {
					throw new UnexpectedValueException('$hookObject must implement interface tx_egovapi_interfaces_template_renderHook', 1296950396);
				}

				$hookObject->postProcessRenderSubpartsMarkers($this->subparts, $this->markers, $this);
			}
		}

		foreach ($this->deprecatedMarkers as $info) {
			if (strpos($this->template, $info['old']) !== FALSE) {
					// Template is using a deprecated marker
				t3lib_div::deprecationLog(
					'Marker ' . $info['old'] . ' is deprecated and will be removed in egovapi '
						. $info['version'] . '. Please use ' . $info['new'] . ' instead.'
				);
			}
		}

		$output = $this->cObj->substituteSubpartArray($this->template, $this->subparts);
		$output = $this->cObj->substituteMarkerArray($output, $this->markers, '###|###');

		return $output;
	}

	/**
	 * Configures the output to render LIST of audiences.
	 *
	 * @param tx_egovapi_domain_model_audience[] $audiences
	 * @return void
	 */
	protected function prepareAudienceList(array $audiences) {
		$audiencesTemplate = $this->cObj->getSubpart($this->template, '###AUDIENCES###');
		$audienceTemplate = $this->cObj->getSubpart($audiencesTemplate, '###AUDIENCE###');

		$output = '';
		foreach ($audiences as $audience) {
			$subparts = array(
				'AUDIENCE_LINK_DETAIL' => $this->getLinkSingleParts($audience),
				'AUDIENCE_LINK_VIEWS'  => $this->getSubLevelLinkParts('audience', 'view', $audience->getId()),
			);
			$markers = $this->getAudienceMarkers($audience);

			// Hook for post-processing the subparts and markers
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareAudienceHook'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareAudienceHook'] as $classRef) {
					/** @var tx_egovapi_interfaces_template_audienceHook $hookObject */
					$hookObject = t3lib_div::getUserObj($classRef);

					if(!($hookObject instanceof tx_egovapi_interfaces_template_audienceHook)) {
						throw new UnexpectedValueException('$hookObject must implement interface tx_egovapi_interfaces_template_audienceHook', 1296950852);
					}

					$hookObject->postProcessAudienceSubpartsMarkers($audience, 'list', $subparts, $markers, $this);
				}
			}

			$ret = $this->cObj->substituteSubpartArray($audienceTemplate, $subparts);
			$ret = $this->cObj->substituteMarkerArray($ret, $markers, '###|###');
			$output .= $ret;
		}

		$this->subparts['###AUDIENCES###'] = $this->cObj->substituteSubpart($audiencesTemplate, '###AUDIENCE###', $output);
	}

	/**
	 * Configures the output to render a SINGLE audience.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @return void
	 */
	protected function prepareAudienceSingle(/* tx_egovapi_domain_model_audience */ $audience) {
		if (!$audience) {
			return;
		}

		$markers = $this->getAudienceMarkers($audience);
		foreach ($markers as $key => $value) {
			$this->markers[$key] = $value;
		}
	}

	/**
	 * Configures the output to render LIST of views.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @param tx_egovapi_domain_model_view[] $views
	 * @return void
	 */
	protected function prepareViewList(/* tx_egovapi_domain_model_audience */ $audience, array $views) {
		$viewsTemplate = $this->cObj->getSubpart($this->template, '###VIEWS###');
		$viewTemplate = $this->cObj->getSubpart($viewsTemplate, '###VIEW###');

		$output = '';
		foreach ($views as $view) {
			$subparts = array(
				'VIEW_LINK_DETAIL'  => $this->getLinkSingleParts($view),
				'VIEW_LINK_DOMAINS' => $this->getSubLevelLinkParts('view', 'domain', $view->getId()),
			);
			$markers = $this->getViewMarkers($view);

			// Hook for post-processing the subparts and markers
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareViewHook'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareViewHook'] as $classRef) {
					/** @var tx_egovapi_interfaces_template_viewHook $hookObject */
					$hookObject = t3lib_div::getUserObj($classRef);

					if(!($hookObject instanceof tx_egovapi_interfaces_template_viewHook)) {
						throw new UnexpectedValueException('$hookObject must implement interface tx_egovapi_interfaces_template_viewHook', 1296951060);
					}

					$hookObject->postProcessViewSubpartsMarkers($view, 'list', $subparts, $markers, $this);
				}
			}

			$ret = $this->cObj->substituteSubpartArray($viewTemplate, $subparts);
			$ret = $this->cObj->substituteMarkerArray($ret, $markers, '###|###');
			$output .= $ret;
		}

		if ($audience) {
			$this->subparts['AUDIENCE_LINK_DETAIL'] = $this->getLinkSingleParts($audience);
			$this->subparts['VIEW_LINK_AUDIENCES'] = $this->getParentLevelLinkParts('audience');
			$this->subparts['VIEW_LINK_AUDIENCE'] = $this->getParentLevelLinkParts('audience', 'audience', $audience->getId(), 'single');

			$audienceMarkers = $this->getAudienceMarkers($audience);
			foreach ($audienceMarkers as $key => $value) {
				$this->markers[$key] = $value;
			}
		} else {
			$this->subparts['VIEW_LINK_AUDIENCES'] = $this->getParentLevelLinkParts('audience');
		}

		$this->subparts['###VIEWS###'] = $this->cObj->substituteSubpart($viewsTemplate, '###VIEW###', $output);

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['audience.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['audience.']['singlePid'] == $GLOBALS['TSFE']->id);
		if (!t3lib_div::inList($this->settings['displayLevels'], 'AUDIENCE') && ($parentListIsCurrentPage || $parentSingleIsCurrentPage)) {
			$this->subparts['###HAS_PARENT###'] = '';
		}
	}

	/**
	 * Configures the output to render a SINGLE view.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @return void
	 */
	protected function prepareViewSingle(/* tx_egovapi_domain_model_view */ $view) {
		if (!$view) {
			return;
		}

		$markers = $this->getViewMarkers($view);
		foreach ($markers as $key => $value) {
			$this->markers[$key] = $value;
		}

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['audience.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['audience.']['singlePid'] == $GLOBALS['TSFE']->id);
		if (!t3lib_div::inList($this->settings['displayLevels'], 'AUDIENCE') && ($parentListIsCurrentPage || $parentSingleIsCurrentPage)) {
			$this->subparts['###HAS_PARENT###'] = '';
		}
	}

	/**
	 * Configures the output to render LIST of domains.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @param tx_egovapi_domain_model_domain[] $domains
	 * @return void
	 */
	protected function prepareDomainList(/* tx_egovapi_domain_model_view */ $view, array $domains) {
		$domainsTemplate = $this->cObj->getSubpart($this->template, '###DOMAINS###');
		$domainTemplate = $this->cObj->getSubpart($domainsTemplate, '###DOMAIN###');

		$output = '';
		foreach ($domains as $domain) {
			$subparts = array(
				'DOMAIN_LINK_DETAIL' => $this->getLinkSingleParts($domain),
				'DOMAIN_LINK_TOPICS' => $this->getSubLevelLinkParts('domain', 'topic', $domain->getId()),
			);
			$markers = $this->getDomainMarkers($domain);

			// Hook for post-processing the subparts and markers
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareDomainHook'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareDomainHook'] as $classRef) {
					/** @var tx_egovapi_interfaces_template_domainHook $hookObject */
					$hookObject = t3lib_div::getUserObj($classRef);

					if(!($hookObject instanceof tx_egovapi_interfaces_template_domainHook)) {
						throw new UnexpectedValueException('$hookObject must implement interface tx_egovapi_interfaces_template_domainHook', 1296951116);
					}

					$hookObject->postProcessDomainSubpartsMarkers($domain, 'list', $subparts, $markers, $this);
				}
			}

			$ret = $this->cObj->substituteSubpartArray($domainTemplate, $subparts);
			$ret = $this->cObj->substituteMarkerArray($ret, $markers, '###|###');
			$output .= $ret;
		}

		if ($view) {
			$this->subparts['VIEW_LINK_DETAIL'] = $this->getLinkSingleParts($view);
			$this->subparts['DOMAIN_LINK_VIEWS'] = $this->getParentLevelLinkParts('view', 'audience', $view->getAudience()->getId());
			$this->subparts['DOMAIN_LINK_VIEW'] = $this->getParentLevelLinkParts('audience', 'audience', $view->getAudience()->getId(), 'single');

			$viewMarkers = $this->getViewMarkers($view);
			foreach ($viewMarkers as $key => $value) {
				$this->markers[$key] = $value;
			}
		} else {
			$this->subparts['DOMAIN_LINK_VIEWS'] = $this->getParentLevelLinkParts('view');
		}

		$this->subparts['###DOMAINS###'] = $this->cObj->substituteSubpart($domainsTemplate, '###DOMAIN###', $output);

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['view.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['view.']['singlePid'] == $GLOBALS['TSFE']->id);
		if (!t3lib_div::inList($this->settings['displayLevels'], 'VIEW') && ($parentListIsCurrentPage || $parentSingleIsCurrentPage)) {
			$this->subparts['###HAS_PARENT###'] = '';
		}

		$blocks = array('LEVEL_INFORMATION', 'GENERAL_INFORMATION', 'NEWS', 'SUBDOMAINS', 'DESCRIPTOR', 'SYNONYM');
		foreach ($blocks as $block) {
			if (!t3lib_div::inList($this->settings['displayBlocks.']['domain'], $block)) {
				$this->subparts['SHOW_' . $block] = '';
			}
		}
	}

	/**
	 * Configures the output to render a SINGLE domain.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @return void
	 */
	protected function prepareDomainSingle(/* tx_egovapi_domain_model_domain */ $domain) {
		if (!$domain) {
			return;
		}

		$markers = $this->getDomainMarkers($domain);
		foreach ($markers as $key => $value) {
			$this->markers[$key] = $value;
		}

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['view.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['view.']['singlePid'] == $GLOBALS['TSFE']->id);
		if (!t3lib_div::inList($this->settings['displayLevels'], 'VIEW') && ($parentListIsCurrentPage || $parentSingleIsCurrentPage)) {
			$this->subparts['###HAS_PARENT###'] = '';
		}

		$blocks = array('LEVEL_INFORMATION', 'GENERAL_INFORMATION', 'NEWS', 'SUBDOMAINS', 'DESCRIPTOR', 'SYNONYM');
		foreach ($blocks as $block) {
			if (!t3lib_div::inList($this->settings['displayBlocks.']['domain'], $block)) {
				$this->subparts['SHOW_' . $block] = '';
			}
		}
	}

	/**
	 * Configures the output to render LIST of topics.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @param tx_egovapi_domain_model_topic[] $topics
	 * @return void
	 */
	protected function prepareTopicList(/* tx_egovapi_domain_model_domain */ $domain, array $topics) {
		$topicsTemplate = $this->cObj->getSubpart($this->template, '###TOPICS###');
		$topicTemplate = $this->cObj->getSubpart($topicsTemplate, '###TOPIC###');

		$output = '';
		foreach ($topics as $topic) {
			$subparts = array(
				'TOPIC_LINK_DETAIL' => $this->getLinkSingleParts($topic),
				'TOPIC_LINK_SERVICES' => $this->getSubLevelLinkParts('topic', 'service', $topic->getId()),
			);
			$markers = $this->getTopicMarkers($topic);

			// Hook for post-processing the subparts and markers
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareTopicHook'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareTopicHook'] as $classRef) {
					/** @var tx_egovapi_interfaces_template_topicHook $hookObject */
					$hookObject = t3lib_div::getUserObj($classRef);

					if(!($hookObject instanceof tx_egovapi_interfaces_template_topicHook)) {
						throw new UnexpectedValueException('$hookObject must implement interface tx_egovapi_interfaces_template_topicHook', 1296951354);
					}

					$hookObject->postProcessTopicSubpartsMarkers($topic, 'list', $subparts, $markers, $this);
				}
			}

			$ret = $this->cObj->substituteSubpartArray($topicTemplate, $subparts);
			$ret = $this->cObj->substituteMarkerArray($ret, $markers, '###|###');
			$output .= $ret;
		}

		if ($domain) {
			$this->subparts['DOMAIN_LINK_DETAIL'] = $this->getLinkSingleParts($domain);
			$this->subparts['TOPIC_LINK_DOMAINS'] = $this->getParentLevelLinkParts('domain', 'view', $domain->getView()->getId());
			$this->subparts['TOPIC_LINK_DOMAIN'] = $this->getParentLevelLinkParts('view', 'view', $domain->getView()->getId(), 'single');

			$domainMarkers = $this->getDomainMarkers($domain);
			foreach ($domainMarkers as $key => $value) {
				$this->markers[$key] = $value;
			}
		} else {
			$this->subparts['TOPIC_LINK_DOMAINS'] = $this->getParentLevelLinkParts('domain');
		}

		$this->subparts['###TOPICS###'] = $this->cObj->substituteSubpart($topicsTemplate, '###TOPIC###', $output);

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['domain.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['domain.']['singlePid'] == $GLOBALS['TSFE']->id);
		if (!t3lib_div::inList($this->settings['displayLevels'], 'DOMAIN') && ($parentListIsCurrentPage || $parentSingleIsCurrentPage)) {
			$this->subparts['###HAS_PARENT###'] = '';
		}

		$blocks = array('LEVEL_INFORMATION', 'GENERAL_INFORMATION', 'NEWS', 'SUBTOPICS', 'DESCRIPTOR', 'SYNONYM');
		foreach ($blocks as $block) {
			if (!t3lib_div::inList($this->settings['displayBlocks.']['topic'], $block)) {
				$this->subparts['SHOW_' . $block] = '';
			}
		}
	}

	/**
	 * Configures the output to render a SINGLE topic.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @return void
	 */
	protected function prepareTopicSingle(/* tx_egovapi_domain_model_topic */ $topic) {
		if (!$topic) {
			return;
		}

		$markers = $this->getTopicMarkers($topic);
		foreach ($markers as $key => $value) {
			$this->markers[$key] = $value;
		}

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['domain.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['domain.']['singlePid'] == $GLOBALS['TSFE']->id);
		if (!t3lib_div::inList($this->settings['displayLevels'], 'DOMAIN') && ($parentListIsCurrentPage || $parentSingleIsCurrentPage)) {
			$this->subparts['###HAS_PARENT###'] = '';
		}

		$blocks = array('LEVEL_INFORMATION', 'GENERAL_INFORMATION', 'NEWS', 'SUBTOPICS', 'DESCRIPTOR', 'SYNONYM');
		foreach ($blocks as $block) {
			if (!t3lib_div::inList($this->settings['displayBlocks.']['topic'], $block)) {
				$this->subparts['SHOW_' . $block] = '';
			}
		}
	}

	/**
	 * Configures the output to render LIST of services.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @param tx_egovapi_domain_model_service[] $services
	 * @return void
	 */
	protected function prepareServiceList(/* tx_egovapi_domain_model_topic */ $topic, array $services) {
		$servicesTemplate = $this->cObj->getSubpart($this->template, '###SERVICES###');
		$serviceTemplate = $this->cObj->getSubpart($servicesTemplate, '###SERVICE###');

		$output = '';
		foreach ($services as $service) {
			$subparts = array(
				'SERVICE_LINK_DETAIL' => $this->getLinkSingleParts($service),
				// TODO: check this
				'SERVICE_LINK_TOPICS' => $this->getSubLevelLinkParts('service', 'topic', $service->getId()),
			);
			$markers = $this->getServiceMarkers($service);

			// Hook for post-processing the subparts and markers
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareServiceHook'])) {
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->pObj->extKey]['prepareServiceHook'] as $classRef) {
					/** @var tx_egovapi_interfaces_template_serviceHook $hookObject */
					$hookObject = t3lib_div::getUserObj($classRef);

					if(!($hookObject instanceof tx_egovapi_interfaces_template_serviceHook)) {
						throw new UnexpectedValueException('$hookObject must implement interface tx_egovapi_interfaces_template_serviceHook', 1296951478);
					}

					$hookObject->postProcessServiceSubpartsMarkers($service, 'list', $subparts, $markers, $this);
				}
			}

			$ret = $this->cObj->substituteSubpartArray($serviceTemplate, $subparts);
			$ret = $this->cObj->substituteMarkerArray($ret, $markers, '###|###');
			$output .= $ret;
		}

		if ($topic) {
			$this->subparts['TOPIC_LINK_DETAIL'] = $this->getLinkSingleParts($topic);
			$this->subparts['SERVICE_LINK_TOPICS'] = $this->getParentLevelLinkParts('topic', 'domain', $topic->getDomain()->getId());
			$this->subparts['SERVICE_LINK_TOPIC'] = $this->getParentLevelLinkParts('domain', 'domain', $topic->getDomain()->getId(), 'single');

			$topicMarkers = $this->getTopicMarkers($topic);
			foreach ($topicMarkers as $key => $value) {
				$this->markers[$key] = $value;
			}
		} else {
			$this->subparts['SERVICE_LINK_TOPICS'] = $this->getParentLevelLinkParts('topic');
		}

		$this->subparts['###SERVICES###'] = $this->cObj->substituteSubpart($servicesTemplate, '###SERVICE###', $output);

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['topic.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['topic.']['singlePid'] == $GLOBALS['TSFE']->id);
		if (!t3lib_div::inList($this->settings['displayLevels'], 'TOPIC') && ($parentListIsCurrentPage || $parentSingleIsCurrentPage)) {
			$this->subparts['###HAS_PARENT###'] = '';
		}

		$blocks = array('LEVEL_INFORMATION', 'GENERAL_INFORMATION', 'PREREQUISITES', 'PROCEDURE', 'FORMS',
		                'DOCUMENTS_REQUIRED', 'SERVICE_PROVIDED', 'FEE', 'LEGAL_REGULATION', 'DOCUMENTS_OTHER',
		                'REMARKS', 'APPROVAL', 'CONTACT', 'BACK_TO_LIST');
		foreach ($blocks as $block) {
			if (!t3lib_div::inList($this->settings['displayBlocks.']['service'], $block)) {
				$this->subparts['SHOW_' . $block] = '';
			}
		}
	}

	/**
	 * Configures the output to render a SINGLE service.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return void
	 */
	protected function prepareServiceSingle(/* tx_egovapi_domain_model_service */ $service) {
		if (!$service) {
			return;
		}

		$this->registerServiceForRdf($service);

		$markers = $this->getServiceMarkers($service);
		foreach ($markers as $key => $value) {
			$this->markers[$key] = $value;
		}

		$topic = $service->getTopic();
		if ($topic) {
			$this->subparts['TOPIC_LINK_DETAIL'] = $this->getLinkSingleParts($topic);
			$this->subparts['SERVICE_LINK_TOPICS'] = $this->getParentLevelLinkParts('topic', 'domain', $topic->getDomain()->getId());
			$this->subparts['SERVICE_LINK_TOPIC'] = $this->getParentLevelLinkParts('domain', 'domain', $topic->getDomain()->getId(), 'single');
			$this->subparts['SERVICE_LINK_SERVICES'] = $this->getParentLevelLinkParts('service', 'domain', $topic->getDomain()->getId());
		} else {
			$this->subparts['TOPIC_LINK_DETAIL'] = '';
			$this->subparts['SERVICE_LINK_TOPICS'] = '';
			$this->subparts['SERVICE_LINK_TOPIC'] = '';
			$this->subparts['SERVICE_LINK_SERVICES'] = '';
		}

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['topic.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['topic.']['singlePid'] == $GLOBALS['TSFE']->id);
		if (!t3lib_div::inList($this->settings['displayLevels'], 'TOPIC') && ($parentListIsCurrentPage || $parentSingleIsCurrentPage)) {
			$this->subparts['###HAS_PARENT###'] = '';
		}

		$blocks = array('LEVEL_INFORMATION', 'GENERAL_INFORMATION', 'PREREQUISITES', 'PROCEDURE', 'FORMS',
		                'DOCUMENTS_REQUIRED', 'SERVICE_PROVIDED', 'FEE', 'LEGAL_REGULATION', 'DOCUMENTS_OTHER',
		                'REMARKS', 'APPROVAL', 'CONTACT', 'BACK_TO_LIST');
		foreach ($blocks as $block) {
			if (!t3lib_div::inList($this->settings['displayBlocks.']['service'], $block)) {
				$this->subparts['SHOW_' . $block] = '';
			}
		}

		$this->markers['VCARD_URL'] = $this->settings['vcardUrl'];
	}

	/**
	 * Returns markers for an audience.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @return array
	 */
	public function getAudienceMarkers(tx_egovapi_domain_model_audience $audience) {
		$markers = array(
			'AUDIENCE_ID'                     => $audience->getId(),
			'AUDIENCE_NAME'                   => $audience->getName(),
			'AUDIENCE_AUTHOR'                 => $audience->getAuthor(),
			'AUDIENCE_CREATION_DATE'          => date('d.m.Y', $audience->getCreationDate()),
			'AUDIENCE_LAST_MODIFICATION_DATE' => date('d.m.Y', $audience->getLastModificationDate()),
		);

		return $markers;
	}

	/**
	 * Returns markers for a view.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @return array
	 */
	public function getViewMarkers(tx_egovapi_domain_model_view $view) {
		$markers = array(
			'VIEW_ID'                     => $view->getId(),
			'VIEW_NAME'                   => $view->getName(),
			'VIEW_AUTHOR'                 => $view->getAuthor(),
			'VIEW_CREATION_DATE'          => date('d.m.Y', $view->getCreationDate()),
			'VIEW_LAST_MODIFICATION_DATE' => date('d.m.Y', $view->getLastModificationDate()),
		);

		return $markers;
	}

	/**
	 * Returns markers for a domain.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @return array
	 */
	public function getDomainMarkers(tx_egovapi_domain_model_domain $domain) {
		$markers = array(
			'DOMAIN_ID'                     => $domain->getId(),
			'DOMAIN_NAME'                   => $domain->getName(),
			'DOMAIN_DESCRIPTION'            => $domain->getDescription(),
			'DOMAIN_IS_PARENT'              => $domain->getIsParent() ? 1 : 0,
			'DOMAIN_VERSION_ID'             => $domain->getVersionId(),
			'DOMAIN_VERSION_NAME'           => $domain->getVersionName(),
			'DOMAIN_COMMUNITY_ID'           => $domain->getCommunityId(),
			'DOMAIN_RELEASE'                => $domain->getRelease(),
			'DOMAIN_REMARKS'                => $domain->getRemarks(),
			'DOMAIN_STATUS'                 => $domain->getStatus(),
			'DOMAIN_AUTHOR'                 => $domain->getAuthor(),
			'DOMAIN_CREATION_DATE'          => date('d.m.Y', $domain->getCreationDate()),
			'DOMAIN_LAST_MODIFICATION_DATE' => date('d.m.Y', $domain->getLastModificationDate()),
		);

			// Only render blocks that are allowed to be shown
		$blocks = array('GENERAL_INFORMATION', 'NEWS', 'SUBDOMAINS', 'DESCRIPTOR', 'SYNONYM');
		foreach ($blocks as $block) {
			if (t3lib_div::inList($this->settings['displayBlocks.']['domain'], $block)) {
				switch ($block) {
					case 'GENERAL_INFORMATION':
						$value = (string)$domain->getGeneralInformation();
						break;
					case 'NEWS':
						$value = (string)$domain->getNews();
						break;
					case 'SUBDOMAINS':
						$value = (string)$domain->getSubdomains();
						break;
					case 'DESCRIPTOR':
						$value = (string)$domain->getDescriptor();
						break;
					case 'SYNONYM':
						$value = (string)$domain->getSynonym();
						break;
				}
				$markers['DOMAIN_' . $block] = $value;
			}
		}

		return $markers;
	}

	/**
	 * Returns markers for a topic.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @return array
	 */
	public function getTopicMarkers(tx_egovapi_domain_model_topic $topic) {
		$markers = array(
			'TOPIC_ID'                     => $topic->getId(),
			'TOPIC_NAME'                   => $topic->getName(),
			'TOPIC_DESCRIPTION'            => $topic->getDescription(),
			'TOPIC_IS_PARENT'              => $topic->getIsParent() ? 1 : 0,
			'TOPIC_VERSION_ID'             => $topic->getVersionId(),
			'TOPIC_VERSION_NAME'           => $topic->getVersionName(),
			'TOPIC_COMMUNITY_ID'           => $topic->getCommunityId(),
			'TOPIC_RELEASE'                => $topic->getRelease(),
			'TOPIC_REMARKS'                => $topic->getRemarks(),
			'TOPIC_STATUS'                 => $topic->getStatus(),
			'TOPIC_AUTHOR'                 => $topic->getAuthor(),
			'TOPIC_CREATION_DATE'          => date('d.m.Y', $topic->getCreationDate()),
			'TOPIC_LAST_MODIFICATION_DATE' => date('d.m.Y', $topic->getLastModificationDate()),
		);

			// Only render blocks that are allowed to be shown
		$blocks = array('GENERAL_INFORMATION', 'NEWS', 'SUBTOPICS', 'DESCRIPTOR', 'SYNONYM');
		foreach ($blocks as $block) {
			if (t3lib_div::inList($this->settings['displayBlocks.']['topic'], $block)) {
				switch ($block) {
					case 'GENERAL_INFORMATION':
						$value = (string)$topic->getGeneralInformation();
						break;
					case 'NEWS':
						$value = (string)$topic->getNews();
						break;
					case 'SUBTOPICS':
						$value = (string)$topic->getSubtopics();
						break;
					case 'DESCRIPTOR':
						$value = (string)$topic->getDescriptor();
						break;
					case 'SYNONYM':
						$value = (string)$topic->getSynonym();
						break;
				}
				$markers['TOPIC_' . $block] = $value;
			}
		}

		return $markers;
	}

	/**
	 * Returns markers for a service.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return array
	 */
	public function getServiceMarkers(tx_egovapi_domain_model_service $service) {
		$markers = array(
			'SERVICE_ID'                     => $service->getId(),
			'SERVICE_NAME'                   => $service->getName(),
			'SERVICE_DESCRIPTION'            => $service->getDescription(),
			'SERVICE_VERSION_ID'             => $service->getVersionId(),
			'SERVICE_VERSION_NAME'           => $service->getVersionName(),
			'SERVICE_COMMUNITY_ID'           => $service->getCommunityId(),
			'SERVICE_RELEASE'                => $service->getRelease(),
			'SERVICE_COMMENTS'               => $service->getComments(),
			'SERVICE_PROVIDER'               => $service->getProvider(),
			'SERVICE_CUSTOMER'               => $service->getCustomer(),
			'SERVICE_TYPE'                   => $service->getType(),
			'SERVICE_ACTION'                 => $service->getAction(),
			'SERVICE_STATUS'                 => $service->getStatus(),
			'SERVICE_AUTHOR'                 => $service->getAuthor(),
			'SERVICE_CREATION_DATE'          => date('d.m.Y', $service->getCreationDate()),
			'SERVICE_LAST_MODIFICATION_DATE' => date('d.m.Y', $service->getLastModificationDate()),
		);

			// Only render blocks that are allowed to be shown
		$blocks = array('GENERAL_INFORMATION', 'PREREQUISITES', 'PROCEDURE', 'FORMS', 'DOCUMENTS_REQUIRED',
		                'SERVICE_PROVIDED', 'FEE', 'LEGAL_REGULATION', 'DOCUMENTS_OTHER', 'REMARKS', 'APPROVAL');
		foreach ($blocks as $block) {
			if (t3lib_div::inList($this->settings['displayBlocks.']['service'], $block)) {
				switch ($block) {
					case 'GENERAL_INFORMATION':
						$value = (string)$service->getGeneralInformation();
						break;
					case 'PREREQUISITES':
						$value = (string)$service->getPrerequisites();
						break;
					case 'PROCEDURE':
						$value = (string)$service->getProcedure();
						break;
					case 'FORMS':
						$value = (string)$service->getForms();
						break;
					case 'DOCUMENTS_REQUIRED':
						$value = (string)$service->getDocumentsRequired();
						break;
					case 'SERVICE_PROVIDED':
						$value = (string)$service->getServiceProvided();
						break;
					case 'FEE':
						$value = (string)$service->getFee();
						break;
					case 'LEGAL_REGULATION':
						$value = (string)$service->getLegalRegulation();
						break;
					case 'DOCUMENTS_OTHER':
						$value = (string)$service->getDocumentsOther();
						break;
					case 'REMARKS':
						$value = (string)$service->getRemarks();
						break;
					case 'APPROVAL':
						$value = (string)$service->getApproval();
						break;
				}
				$markers['SERVICE_' . $block] = $value;
			}
		}

		if (t3lib_div::inList($this->settings['displayBlocks.']['service'], 'CONTACT')) {
			$markers['SERVICE_CONTACT'] = (string)$service->getContact();

				// Additional markers to be fair with people running an old version of TYPO3
			if ($service->getContact()) {
				$markers['SERVICE_CONTACT_DEPARTMENT']    = $service->getContact()->getDepartment();
				$markers['SERVICE_CONTACT_OFFICE']        = $service->getContact()->getOffice();
				$markers['SERVICE_CONTACT_ADDRESS']       = $service->getContact()->getAddress();
				// @deprecated Marker SERVICE_CONTACT_POSTAL_CASE will be removed in egovapi 1.6
				$this->deprecateMarker('SERVICE_CONTACT_POSTAL_CASE', 'SERVICE_CONTACT_PO_BOX', '1.6');
				$markers['SERVICE_CONTACT_POSTAL_CASE']   = $service->getContact()->getPoBox();
				$markers['SERVICE_CONTACT_PO_BOX']        = $service->getContact()->getPoBox();
				$markers['SERVICE_CONTACT_POSTAL_CODE']   = $service->getContact()->getPostalCode();
				// @deprecated Marker SERVICE_CONTACT_MUNICIPALITY will be removed in egovapi 1.6
				$this->deprecateMarker('SERVICE_CONTACT_MUNICIPALITY', 'SERVICE_CONTACT_LOCALITY', '1.6');
				$markers['SERVICE_CONTACT_MUNICIPALITY']  = $service->getContact()->getLocality();
				$markers['SERVICE_CONTACT_LOCALITY']      = $service->getContact()->getLocality();
				$markers['SERVICE_CONTACT_PERSON']        = $service->getContact()->getPerson();
				// @deprecated Marker SERVICE_CONTACT_PHONE1 will be removed in egovapi 1.6
				$this->deprecateMarker('SERVICE_CONTACT_PHONE1', 'SERVICE_CONTACT_PHONE', '1.6');
				$markers['SERVICE_CONTACT_PHONE1']        = $service->getContact()->getPhone();
				$markers['SERVICE_CONTACT_PHONE']         = $service->getContact()->getPhone();
				$markers['SERVICE_CONTACT_FAX']           = $service->getContact()->getFax();
				$markers['SERVICE_CONTACT_EMAIL']         = str_replace(array('@', '.'), array('&#64;', '&#46;'), $service->getContact()->getEmail());
				$markers['SERVICE_CONTACT_EMAIL_LINK']    = $this->cObj->typoLink(
					$service->getContact()->getEmail(),
					array('parameter' => $service->getContact()->getEmail())
				);
				$markers['SERVICE_CONTACT_PUBLIC_KEY']    = $service->getContact()->getPublicKey();
				$markers['SERVICE_CONTACT_LOGO']          = $service->getContact()->getLogo();
				$markers['SERVICE_CONTACT_BANNER']        = $service->getContact()->getBanner();
				$markers['SERVICE_CONTACT_OPENING_HOURS'] = $service->getContact()->getOpeningHours();
			}
		}

		return $markers;
	}

	/**
	 * Returns the link parts to a page showing given $entity in SINGLE mode.
	 *
	 * @param tx_egovapi_domain_model_abstractEntity $entity
	 * @return array
	 */
	public function getLinkSingleParts(tx_egovapi_domain_model_abstractEntity $entity) {
		$level = '';

		switch (TRUE) {
			case is_a($entity, 'tx_egovapi_domain_model_audience'):
				$level = 'audience';
				break;
			case is_a($entity, 'tx_egovapi_domain_model_view'):
				$level = 'view';
				break;
			case is_a($entity, 'tx_egovapi_domain_model_domain'):
				$level = 'domain';
				break;
			case is_a($entity, 'tx_egovapi_domain_model_topic'):
				$level = 'topic';
				break;
			case is_a($entity, 'tx_egovapi_domain_model_service');
				$level = 'service';
				break;
		}

		$params = array(
			$level => $entity->getId(),
			'action' => $level,
			'mode' => 'single',
		);

		if ($level === 'service' && $entity->getTopic() !== NULL) {
			$params['topic'] = $entity->getTopic()->getId();
		}

		$conf = $this->getTypolinkConf(
			$this->settings['targets.'][$level . '.']['singlePid'],
			$params
		);
		return $this->cObj->typolinkWrap($conf);
	}

	/**
	 * Returns the link parts to a page showing next level item(s) for
	 * the given entity.
	 *
	 * @param string $currentLevel
	 * @param string $subLevel
	 * @param string $currentId
	 * @param string $mode Either 'list' or 'single'
	 * @param string $subLevelId
	 * @return array
	 */
	public function getSubLevelLinkParts($currentLevel, $subLevel, $currentId, $mode = 'list', $subLevelId = '') {
		$params = array(
			$currentLevel => $currentId,
			'action' => $subLevel,
			'mode' => $mode,
		);
		if ($mode === 'single') {
			$params[$subLevel] = $subLevelId;
		}
		$conf = $this->getTypolinkConf(
			$this->settings['targets.'][$subLevel . '.'][$mode . 'Pid'],
			$params
		);
		return $this->cObj->typolinkWrap($conf);
	}

	/**
	 * Returns the link parts to a page showing parent level item(s) for
	 * the given entity.
	 *
	 * @param string $parentLevel
	 * @param string $idKey
	 * @param string $idValue
	 * @param string $mode Either 'list' or 'single'
	 * @return void
	 */
	public function getParentLevelLinkParts($parentLevel, $idKey = '', $idValue = '', $mode = 'list') {
		$params = array(
			'action' => $parentLevel,
			'mode' => $mode,
		);
		if ($idKey) {
			$params[$idKey] = $idValue;
		}
		$conf = $this->getTypolinkConf(
			$this->settings['targets.'][$parentLevel . '.'][$mode . 'Pid'],
			$params
		);
		return $this->cObj->typolinkWrap($conf);
	}

	/**
	 * Returns the typolink configuration.
	 *
	 * @param integer $uid
	 * @param array $params
	 * @return array
	 */
	protected function getTypolinkConf($uid, array $params) {
		$conf = array(
			'parameter' => $uid,
			'useCacheHash' => 1,
			'no_cache' => 0,
			'additionalParams' => t3lib_div::implodeArrayForUrl($this->pObj->prefixId, $params, '', TRUE),
		);
		return $conf;
	}

	/**
	 * Initializes the template by loading the corresponding file
	 * and preparing global marker substitution.
	 *
	 * @return void
	 */
	protected function initializeOutput() {
		$mode = strtolower($this->settings['mode']);
		$level = strtolower($this->settings['level']);

		$templateFile = $this->settings['templates.'][$mode . '.'][$level . '.']['standard'];
		$template = $this->cObj->fileResource($templateFile);

		$this->template = $this->cObj->getSubpart($template, '###' . strtoupper($level) . '_' . strtoupper($mode) . '###');

		$this->subparts = array();

		// Load all labels without a dot in the key as available marker
		$this->deprecatedMarkers = array();
		$this->markers = array();
		foreach ($this->pObj->LOCAL_LANG['default'] as $key => $label) {
			if (strpos($key, '.') === FALSE) {
				$this->markers[strtoupper($key)] = $this->pObj->pi_getLL($key);
			}
		}
	}

	/**
	 * Deprecates a marker.
	 *
	 * @param string $oldMarker
	 * @param string $newMarker
	 * @param string $versionRemoval
	 * @return void
	 */
	protected function deprecateMarker($oldMarker, $newMarker, $versionRemoval) {
		$this->deprecatedMarkers[] = array(
			'old' => $oldMarker,
			'new' => $newMarker,
			'version' => $versionRemoval,
		);
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi1/TemplateRenderer.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi1/TemplateRenderer.php']);
}

?>