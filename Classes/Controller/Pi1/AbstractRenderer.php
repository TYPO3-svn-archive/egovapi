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
 * Base class with some business logic for the renderers of the plugin 'eGov API'
 * for the 'egovapi' extension.
 *
 * @category    Renderer
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
abstract class tx_egovapi_controller_pi1_abstractRenderer {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var array
	 */
	protected $parameters;

	/**
	 * @var tslib_pibase $pObj
	 */
	protected $pObj;

	/**
	 * @var tslib_cObj
	 */
	protected $cObj;

	/**
	 * Initializes the renderer.
	 *
	 * @param tslib_pibase $pObj
	 * @param array $settings
	 * @param array $parameters
	 */
	public function initialize(tslib_pibase $pObj, array $settings, array $parameters) {
		$this->pObj = $pObj;
		$this->cObj = $pObj->cObj;
		$this->settings = $settings;
		$this->parameters = $parameters;
	}

	/**
	 * Renders the output.
	 *
	 * @return string
	 */
	abstract public function render();

	/**
	 * Initializes the output.
	 *
	 * @return void
	 */
	abstract protected function initializeOutput();

	/**
	 * This method is typically invoked at the beginning of
	 * method {@see render()} to initialize the rendering process.
	 *
	 * @return void
	 */
	protected function initializeRender() {
		$this->initializeOutput();

		switch ($this->settings['level']) {
			case 'AUDIENCE':
				$this->prepareAudience();
				break;
			case 'VIEW':
				$this->prepareView();
				break;
			case 'DOMAIN':
				$this->prepareDomain();
				break;
			case 'TOPIC':
				$this->prepareTopic();
				break;
			case 'SERVICE':
				$this->prepareService();
				break;
		}
	}

	/**
	 * Configures the output to render Audience (either in LIST or SINGLE mode).
	 *
	 * @return void
	 */
	protected function prepareAudience() {
		/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
		$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');

		switch ($this->settings['mode']) {
			case 'LIST':
				if ($this->settings['audiences']) {
					// Static list of audiences to show
					$audiences = array();
					$ids = t3lib_div::trimExplode(',', $this->settings['audiences']);
					foreach ($ids as $id) {
						$audiences[] = $audienceRepository->findById($id);
					}
				} else {
					$audiences = $audienceRepository->findAll();
				}

				$this->prepareAudienceList($audiences);
				break;

			case 'SINGLE':
				$requestedId = isset($this->parameters['audience']) ? $this->parameters['audience'] : 0;
				$id = $requestedId;
				if ($this->settings['audiences']) {
					// Static list of audiences to show, take first one
					// if requested id is not authorized
					$ids = t3lib_div::trimExplode(',', $this->settings['audiences']);
					$id = t3lib_div::inArray($ids, $requestedId) ? $requestedId : $ids[0];
				}

				$audience = $audienceRepository->findById($id);
				$this->prepareAudienceSingle($audience);
				break;
		}
	}

	/**
	 * Configures the output to render LIST of audiences.
	 *
	 * @param tx_egovapi_domain_model_audience[] $audiences
	 * @return void
	 */
	abstract protected function prepareAudienceList(array $audiences);

	/**
	 * Configures the output to render a SINGLE audience.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @return void
	 */
	abstract protected function prepareAudienceSingle(/* tx_egovapi_domain_model_audience */ $audience);

	/**
	 * Configures the view to render View (either in LIST or SINGLE mode).
	 *
	 * @return void
	 */
	protected function prepareView() {
		/** @var tx_egovapi_domain_repository_viewRepository $viewRepository */
		$viewRepository = tx_egovapi_domain_repository_factory::getRepository('view');

		switch ($this->settings['mode']) {
			case 'LIST':
				/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
				$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');

				$restrictAudience = FALSE;
				$audience = null;
				if ($this->settings['audience']) {
					$audience = $audienceRepository->findById($this->settings['audience']);
					$restrictAudience = TRUE;
				}
				if ($restrictAudience && !$audience) {
					return;
				}

				if ($this->settings['views']) {
					// Static list of views to show
					$views = array();
					$ids = t3lib_div::trimExplode(',', $this->settings['views']);
					foreach ($ids as $id) {
						$view = $viewRepository->findById($id);
						// Only use views related to current audience
						if ($view && (!$restrictAudience || $view->getAudience() == $audience)) {
							$views[] = $view;
						}
					}
				} else {
					$views = $viewRepository->findAll($audience);
				}

				$this->prepareViewList($audience, $views);
				break;

			case 'SINGLE':
				$requestedId = isset($this->parameters['view']) ? $this->parameters['view'] : 0;
				$id = $requestedId;
				if ($this->settings['views']) {
					// Static list of views to show, take first one
					// if requested id is not authorized
					$ids = t3lib_div::trimExplode(',', $this->settings['views']);
					$id = t3lib_div::inArray($ids, $requestedId) ? $requestedId : $ids[0];
				}

				$view = $viewRepository->findById($id);
				$this->prepareViewSingle($view);
				break;
		}
	}

	/**
	 * Configures the output to render LIST of views.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @param tx_egovapi_domain_model_view[] $views
	 * @return void
	 */
	abstract protected function prepareViewList(/* tx_egovapi_domain_model_audience */ $audience, array $views);

	/**
	 * Configures the output to render a SINGLE view.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @return void
	 */
	abstract protected function prepareViewSingle(/* tx_egovapi_domain_model_view */ $view);

	/**
	 * Configures the output to render Domain (either in LIST or SINGLE mode).
	 *
	 * @return void
	 */
	protected function prepareDomain() {
		/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
		$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');

		switch ($this->settings['mode']) {
			case 'LIST':
				/** @var tx_egovapi_domain_repository_viewRepository $viewRepository */
				$viewRepository = tx_egovapi_domain_repository_factory::getRepository('view');

				$restrictView = FALSE;
				$view = null;
				if ($this->settings['view']) {
					$view = $viewRepository->findById($this->settings['view']);
					$restrictView = TRUE;
				}
				if ($restrictView && !$view) {
					return;
				}

				if ($this->settings['domains']) {
					// Static list of domains to show
					$domains = array();
					$ids = t3lib_div::trimExplode(',', $this->settings['domains']);
					foreach ($ids as $id) {
						$domain = $domainRepository->findById($id);
						// Only use domains related to current view
						if ($domain && (!$restrictView || $domain->getView() == $view)) {
							$domains[] = $domain;
						}
					}
				} else {
					$domains = $domainRepository->findAll($view);
				}

				$this->prepareDomainList($view, $domains);
				break;

			case 'SINGLE':
				$requestedId = isset($this->parameters['domain']) ? $this->parameters['domain'] : 0;
				$id = $requestedId;
				if ($this->settings['domains']) {
					// Static list of domains to show, take first one
					// if requested id is not authorized
					$ids = t3lib_div::trimExplode(',', $this->settings['domains']);
					$id = t3lib_div::inArray($ids, $requestedId) ? $requestedId : $ids[0];
				}

				$domain = $domainRepository->findById($id);
				$this->prepareDomainSingle($domain);
				break;
		}
	}

	/**
	 * Configures the output to render LIST of domains.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @param tx_egovapi_domain_model_domain[] $domains
	 * @return void
	 */
	abstract protected function prepareDomainList(/* tx_egovapi_domain_model_view */ $view, array $domains);

	/**
	 * Configures the output to render a SINGLE domain.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @return void
	 */
	abstract protected function prepareDomainSingle(/* tx_egovapi_domain_model_domain */ $domain);

	/**
	 * Configures the output to render Topic (either in LIST or SINGLE mode).
	 *
	 * @return void
	 */
	protected function prepareTopic() {
		/** @var tx_egovapi_domain_repository_topicRepository $topicRepository */
		$topicRepository = tx_egovapi_domain_repository_factory::getRepository('topic');

		switch ($this->settings['mode']) {
			case 'LIST':
				/** @var tx_egovapi_domain_repository_domainRepository $domainRepository */
				$domainRepository = tx_egovapi_domain_repository_factory::getRepository('domain');

				$restrictDomain = FALSE;
				$domain = null;
				if ($this->settings['domain']) {
					$domain = $domainRepository->findById($this->settings['domain']);
					$restrictDomain = TRUE;
				}
				if ($restrictDomain && !$domain) {
					return;
				}
				if ($this->settings['topics']) {
					// Static list of topics to show
					$topics = array();
					$ids = t3lib_div::trimExplode(',', $this->settings['topics']);
					foreach ($ids as $id) {
						$topic = $topicRepository->findById($id);
						// Only use topics related to current domain
						if ($topic && (!$restrictDomain || $topic->getDomain() == $domain)) {
							$topics[] = $topic;
						}
					}
				} else {
					$topics = $topicRepository->findAll($domain);
				}

				$this->prepareTopicList($domain, $topics);
				break;

			case 'SINGLE':
				$requestedId = isset($this->parameters['topic']) ? $this->parameters['topic'] : 0;
				$id = $requestedId;
				if ($this->settings['topics']) {
					// Static list of topics to show, take first one
					// if requested id is not authorized
					$ids = t3lib_div::trimExplode(',', $this->settings['topics']);
					$id = t3lib_div::inArray($ids, $requestedId) ? $requestedId : $ids[0];
				}

				$topic = $topicRepository->findById($id);
				$this->prepareTopicSingle($topic);
				break;
		}
	}

	/**
	 * Configures the output to render LIST of topics.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @param tx_egovapi_domain_model_topic[] $topics
	 * @return void
	 */
	abstract protected function prepareTopicList(/* tx_egovapi_domain_model_domain */ $domain, array $topics);

	/**
	 * Configures the output to render a SINGLE topic.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @return void
	 */
	abstract protected function prepareTopicSingle(/* tx_egovapi_domain_model_topic */ $topic);

	/**
	 * Configures the output to render Service (either in LIST or SINGLE mode).
	 *
	 * @return void
	 */
	protected function prepareService() {
		/** @var tx_egovapi_domain_repository_serviceRepository $serviceRepository */
		$serviceRepository = tx_egovapi_domain_repository_factory::getRepository('service');

		/** @var tx_egovapi_domain_repository_topicRepository $topicRepository */
		$topicRepository = tx_egovapi_domain_repository_factory::getRepository('topic');

		$topic = null;
		$restrictTopic = FALSE;
		if ($this->settings['topic']) {
			$topic = $topicRepository->findById($this->settings['topic']);
			$restrictTopic = TRUE;
		}
		if ($restrictTopic && !$topic) {
			return;
		}

		switch ($this->settings['mode']) {
			case 'LIST':
				$topics = array();
				if (!$restrictTopic) {
					$topicIds = t3lib_div::trimExplode(',', $this->settings['topics']);
					$topics = array();
					foreach ($topicIds as $topicId) {
						$topic = $topicRepository->findById($topicId);
						if ($topic) {
							$topics[] = $topic;
						}
					}
				} else {
					$topics[] = $topic;
				}
				if ($this->settings['services']) {
					// Static list of services to show
					$services = array();
					$ids = t3lib_div::trimExplode(',', $this->settings['services']);
					foreach ($ids as $id) {
						if (count($topics) > 0) {
							foreach ($topics as $topic) {
								$version = isset($this->settings['versions.'][$id]) ? $this->settings['versions.'][$id] : 0;
								$service = $serviceRepository->getByTopicAndIdAndVersion($topic, $id, $version);
								if ($service) {
									// Using id as key to avoid duplicates
									$services[$service->getId()] = $service;
									break;
								}
							}
						} elseif ($id) {
							// Try to get the service solely using its ID
							$version = isset($this->settings['versions.'][$id]) ? $this->settings['versions.'][$id] : 0;
							$service = $serviceRepository->getByIdAndVersion($id, $version);
							if ($service) {
								// Using id as key to avoid duplicates
								$services[$service->getId()] = $service;
							}
						}
					}
				} else {
					$services = array();
					foreach ($topics as $topic) {
						$topicServices = $serviceRepository->findAll($topic);
						foreach ($topicServices as $service) {
							// Using id as key to avoid duplicates
							$services[$service->getId()] = $service;
						}
					}
				}

				// Force services to be always sorted by name
				tx_egovapi_utility_objects::sort($services, 'name');

				// In most case there should be only one topic selected
				// Give direct access to first one
				$this->prepareServiceList($topics[0], $services);
				break;

			case 'SINGLE':
				$requestedId = isset($this->parameters['service']) ? $this->parameters['service'] : 0;
				$id = $requestedId;
				if ($this->settings['services']) {
					// Static list of services to show, take first one
					// if requested id is not authorized
					$ids = t3lib_div::trimExplode(',', $this->settings['services']);
					$id = t3lib_div::inArray($ids, $requestedId) ? $requestedId : $ids[0];
				}

				$topics = array();
				if (!$restrictTopic) {
					$topicIds = t3lib_div::trimExplode(',', $this->settings['topics']);
					$topics = array();
					foreach ($topicIds as $topicId) {
						$topic = $topicRepository->findById($topicId);
						if ($topic) {
							$topics[] = $topic;
						}
					}
				} else {
					$topics[] = $topic;
				}
				$service = null;
				if (count($topics) > 0) {
					foreach ($topics as $topic) {
						$version = isset($this->settings['versions.'][$id]) ? $this->settings['versions.'][$id] : 0;
						$service = $serviceRepository->getByTopicAndIdAndVersion($topic, $id, $version);
						if ($service) {
							break;
						}
					}
				} elseif ($id) {
					// Try to get the service solely using its ID
					$version = isset($this->settings['versions.'][$id]) ? $this->settings['versions.'][$id] : 0;
					$service = $serviceRepository->getByIdAndVersion($id, $version);
				}

				$this->prepareServiceSingle($service);
				break;
		}
	}

	/**
	 * Configures the output to render LIST of services.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @param tx_egovapi_domain_model_service[] $services
	 * @return void
	 */
	abstract protected function prepareServiceList(/* tx_egovapi_domain_model_topic */ $topic, array $services);

	/**
	 * Configures the output to render a SINGLE service.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return void
	 */
	abstract protected function prepareServiceSingle(/* tx_egovapi_domain_model_service */ $service);

}

?>