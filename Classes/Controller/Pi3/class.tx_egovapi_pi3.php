<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Xavier Perseguers <xavier@causal.ch>
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
 * Plugin 'eGov API - RDF Generator' for the 'egovapi' extension.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_pi3 extends tx_egovapi_pibase {

	public $prefixId      = 'tx_egovapi_pi3';
	public $scriptRelPath = 'Classes/Controller/Pi3/class.tx_egovapi_pi3.php';

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var array
	 */
	protected $sessionData;

	/**
	 * Main function.
	 *
	 * @param string $content: The Plugin content
	 * @param array $conf: The Plugin configuration
	 * @return string Content which appears on the website
	 */
	public function main($content, array $conf) {
		$this->init($conf);
		$this->pi_USER_INT_obj = 1;	// Configuring so caching is not expected.
		$this->pi_loadLL();

		if (!$this->conf['wsdlVersion']) {
			die('Plugin ' . $this->prefixId . ' is not configured properly!');
		}

		$templateFile = $this->conf['template'];
		$this->template = $this->cObj->fileResource($templateFile);

		if ($this->piVars['action'] === 'download') {
			$this->downloadRDF();
		}

		switch ($this->piVars['step']) {
			case 1:
				$output = $this->step1();
				break;
			case 2:
				$output = $this->step2();
				break;
			case 3:
				$output = $this->step3();
				break;
			default:
				$output = 'INVALID STEP!';
		}

		$GLOBALS['TSFE']->fe_user->setKey('ses', $this->prefixId, $this->sessionData);

		$output = $this->pi_wrapInBaseClass($output);
		return $output;
	}

	/**
	 * Prepares step 1 of the wizard.
	 *
	 * @return string
	 */
	protected function step1() {
		$template = $this->cObj->getSubpart($this->template, '###TEMPLATE_STEP1###');

		/** @var $utilityConstants tx_egovapi_utility_constants */
		$utilityConstants = t3lib_div::makeInstance('tx_egovapi_utility_constants');

		$markers = array(
			'ACTION'             => $this->pi_getPageLink($GLOBALS['TSFE']->id),
			'LABEL_LANGUAGE'     => $this->pi_getLL('common_language'),
			'LABEL_COMMUNITY'    => $this->pi_getLL('header_community'),
			'LABEL_ORGANIZATION' => $this->pi_getLL('header_organization'),
			'LABEL_WEBSITE'      => $this->pi_getLL('common_website'),
			'LABEL_ALL_VERSIONS' => $this->pi_getLL('common_all_versions'),
			'LABEL_NEXT'         => $this->pi_getLL('common_next'),
			'AJAX_LOADER_SMALL'  => $this->conf['ajaxLoaderSmall'],
			'AJAX_URL'           => $this->pi_getPageLink($GLOBALS['TSFE']->id),
			'WEBSITE'            => '',
		);

		foreach ($this->sessionData as $key => $value) {
			$markers[strtoupper($key)] = $value;
		}

		$subparts = array(
			'COMMUNITIES' => $utilityConstants->getCommunities(array(
				'fieldId' => 'tx_egovapi_community',
				'fieldName' => $this->prefixId . '[community]',
				'fieldValue' => $this->sessionData['community'],
			)),
		);

		$output = $this->cObj->substituteSubpartArray($template, $subparts);
		$output = $this->cObj->substituteMarkerArray($output, $markers, '###|###');

		return $output;
	}

	/**
	 * Prepares step 2 of the wizard.
	 *
	 * @return string
	 */
	protected function step2() {
		if (!$this->sessionData['community']) {
			return $this->step1();
		}
		$services = $this->getDomainServices();

		$template = $this->cObj->getSubpart($this->template, '###TEMPLATE_STEP2###');
		$providerTemplate = $this->cObj->getSubpart($template, '###TEMPLATE_PROVIDER###');
		$serviceTemplate = $this->cObj->getSubpart($template, '###TEMPLATE_SERVICE###');
		$formServices = array();

		$i = 1;
		$lastProvider = '';
		$odd = TRUE;
		foreach ($services as $service) {
			if ($lastProvider !== $service->getProvider()) {
				$formServices[] = $this->cObj->substituteMarkerArray(
					$providerTemplate,
					array(
						'PROVIDER' => $service->getProvider(),
					),
					'###|###'
				);
				$odd = TRUE;
			}
			if ($this->sessionData['all_versions']) {
				$versions = $service->getVersions();
			} else {
				/** @var $version tx_egovapi_domain_model_version */
				$version = t3lib_div::makeInstance('tx_egovapi_domain_model_version', $service->getVersionId());

				$version->setService($service);
				$version->setName($service->getVersionName());
				$version->setIsDefault(TRUE);

				$versions = array($version);
			}

			foreach ($versions as $version) {
				/** @var $version tx_egovapi_domain_model_version */
				$serviceId = $service->getId() . '-' . $version->getId();
				if ($version->isDefault()) {
					$servicePattern = '%s (•%s)';
				} else {
					$servicePattern = '<em>%s (%s)</em>';
				}

				$markers = array(
					'COUNTER'     => $i++,
					'CYCLE'       => $odd ? 'odd' : 'even',
					'SERVICE'     => sprintf($servicePattern, $service->getName(), $version->getId()),
					'SERVICE_ID'  => $serviceId,
					'URL'         => isset($this->sessionData['url'][$serviceId]) ? $this->sessionData['url'][$serviceId] : '',
				);

				$formServices[] = $this->cObj->substituteMarkerArray($serviceTemplate, $markers, '###|###');
				$odd = !$odd;
			}

			$lastProvider = $service->getProvider();
		}

		$markers = array(
			'ACTION'             => $this->pi_getPageLink($GLOBALS['TSFE']->id),
			'LABEL_SERVICE'      => $this->pi_getLL('header_service'),
			'LABEL_URL'          => $this->pi_getLL('common_url'),
			'LABEL_GENERATE'     => $this->pi_getLL('common_generate'),
		);

		foreach ($this->sessionData as $key => $value) {
			$markers[strtoupper($key)] = $value;
		}

		$subparts = array(
			'TEMPLATE_PROVIDER'  => '',
			'TEMPLATE_SERVICE'   => implode(LF, $formServices),
		);

		$output = $this->cObj->substituteSubpartArray($template, $subparts);
		$output = $this->cObj->substituteMarkerArray($output, $markers, '###|###');

		return $output;
	}

	/**
	 * Prepares step 3 of the wizard.
	 *
	 * @return string
	 */
	protected function step3() {
		$template = $this->cObj->getSubpart($this->template, '###TEMPLATE_STEP3###');

		$markers = array(
			'RDF_CONTENT' => htmlentities($this->getRDF()),
		);
		$subparts = array(
			'LINK_DOWNLOAD' => $this->cObj->typolinkWrap(array(
				'parameter' => $GLOBALS['TSFE']->id,
				'additionalParams' => '&tx_egovapi_pi3[action]=download',
			))
		);

		$output = $this->cObj->substituteSubpartArray($template, $subparts);
		$output = $this->cObj->substituteMarkerArray($output, $markers, '###|###');

		return $output;
	}

	/**
	 * Generates and sends the RDF for download.
	 *
	 * @return void
	 */
	protected function downloadRDF() {
		header('Content-type: text/n3');
		header('Content-Disposition: attachment; filename="services.n3"');
		echo $this->getRDF();
		exit;
	}

	protected function getRDF() {
		$rdf = array();
		$rdf[] = '@prefix :<http://semantic.cyberadmin.ch/ontologies/spso#> .';

		$communityWebsite = rtrim($this->sessionData['website'], '/') . '/';
		$references = array();

		foreach ($this->sessionData['url'] as $serviceId => $url) {
			list($service, $version) = explode('-', $serviceId, 2);
			$identifier = sprintf(
				'%s-%s-%s-%s',
				intval($service),
				intval($this->sessionData['organization']),
				$this->sessionData['language'],
				$version
			);
			$reference = $communityWebsite . $identifier;
			$rdf[] = sprintf('
<%s>
	a :ProvidedService ;
	:isService <http://semantic.cyberadmin.ch/service/%s> ;
	:url "%s" ;
	:language "%s" .
',
				$communityWebsite . $identifier,
				intval($service),
				htmlspecialchars($url),
				strtoupper($this->sessionData['language'])
			);

			$references[] = $reference;
		}

		if (count($references) > 0) {
			$rdf[] = sprintf('<http://semantic.cyberadmin.ch/municipality/%s>', intval($this->sessionData['organization']));
			for ($i = 0; $i < count($references); $i++) {
				$pattern = $i == count($references) - 1
					? ':providesService <%s>.'
					: ':providesService <%s> ;';
				$rdf[] = TAB . sprintf($pattern, $references[$i]);
			}
		}

		return implode(LF, $rdf);
	}

	/**
	 * Returns available services as domain model objects.
	 *
	 * @return tx_egovapi_domain_model_service[]
	 */
	protected function getDomainServices() {
		$services = array();
		$serviceIds = array();

		/** @var tx_egovapi_domain_repository_audienceRepository $audienceRepository */
		$audienceRepository = tx_egovapi_domain_repository_factory::getRepository('audience');

		$audiences = $audienceRepository->findAll();
		foreach ($audiences as $audience) {
			foreach ($audience->getViews() as $view) {
				foreach ($view->getDomains() as $domain) {
					foreach ($domain->getTopics() as $topic) {
						foreach ($topic->getServices() as $service) {
							$serviceId = $service->getId();
							if (!in_array($serviceId, $serviceIds)) {
								$services[] = $service;
								$serviceIds[] = $serviceId;
							}
						}
					}
				}
			}
		}

		if (!count($services)) {
				// Invalid community?
			return array();
		}

			// Sort services by provider and then by name
		tx_egovapi_utility_objects::sort($services, 'provider');
		$providersServices = array();
		foreach ($services as $service) {
			$provider = $service->getProvider();
			if (!isset($providersServices[$provider])) {
				$providersServices[$provider] = array();
			}
			$providersServices[$provider][] = $service;
		}
		$buffer = array();
		foreach ($providersServices as $provider => &$services) {
			tx_egovapi_utility_objects::sort($services, 'name');
			$buffer = array_merge($buffer, $services);
		}
		$services = $buffer;

		return $services;
	}

	/**
	 * This method performs various initializations.
	 *
	 * @param array $settings: Plugin configuration, as received by the main() method
	 * @return void
	 */
	protected function init(array $settings) {
		// Initialize default values based on extension TS
		$this->conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		if (!is_array($this->conf)) {
			$this->conf = array();
		}

		// Base configuration is equal to the plugin's TS setup
		$this->conf = t3lib_div::array_merge_recursive_overrule($this->conf, $settings, FALSE, FALSE);

		// Basically process stdWrap over all global parameters
		$this->conf = tx_egovapi_utility_ts::preprocessConfiguration($this->cObj, $this->conf);

		$data = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->prefixId);
		$this->sessionData = is_array($data) ? $data : array();

		$this->pi_setPiVarDefaults();

		$transferDataKeys = array('language', 'community', 'organization', 'website', 'all_versions');
		foreach ($transferDataKeys as $key) {
			if (isset($this->piVars[$key])) {
				if ($key === 'website' && !preg_match('#^https?://.+#', trim($this->piVars[$key]))) {
					$this->piVars[$key] = 'http://' . trim($this->piVars[$key]);
				}
				$this->sessionData[$key] = $this->piVars[$key];
			}
		}

		if (isset($this->piVars['service']) && isset($this->piVars['url'])) {
			$this->sessionData['url'] = array();
			foreach ($this->piVars['url'] as $key => $url) {
				if (preg_match('#^https?://.+#', trim($url))) {
					$serviceId = $this->piVars['service'][$key];
					$this->sessionData['url'][$serviceId] = trim($url);
				}
			}
		}

		if (!isset($this->piVars['step'])) {
			$this->piVars['step'] = 1;
		} else {
			$totalSteps = 3;
			$this->piVars['step'] = max(1, min($totalSteps, $this->piVars['step']));
		}

		if (!isset($this->sessionData['language'])) {
			$this->sessionData['language'] = t3lib_div::inList('de,en,fr,it,rm', $GLOBALS['TSFE']->lang) ? $GLOBALS['TSFE']->lang : 'de';
		}
		$this->conf['eCHlanguageID'] = $this->sessionData['language'];
		if (isset($this->sessionData['community'])) {
			$this->conf['eCHcommunityID'] = $this->sessionData['community'];
		}
		if (isset($this->sessionData['organization'])) {
			$this->conf['organizationID'] = $this->sessionData['organization'];
		}

		if ($this->conf['wsdlVersion']) {
			$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $this->conf);
			tx_egovapi_domain_repository_factory::injectDao($dao);
		}
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi3/class.tx_egovapi_pi3.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi3/class.tx_egovapi_pi3.php']);
}

?>