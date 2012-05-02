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
 * Fluid renderer for plugin 'eGov API' for the 'egovapi' extension.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_controller_pi1_fluidRenderer extends tx_egovapi_controller_pi1_abstractRenderer {

	/**
	 * @var Tx_Fluid_View_StandaloneView
	 */
	protected $view;

	/**
	 * Renders the output of plugin Pi1 using Fluid.
	 *
	 * @return string
	 */
	public function render() {
		$this->initializeRender();
		return $this->view->render();
	}

	/**
	 * Configures the output to render LIST of audiences.
	 *
	 * @param tx_egovapi_domain_model_audience[] $audiences
	 * @return void
	 */
	protected function prepareAudienceList(array $audiences) {
		$this->view->assign('audiences', $audiences);
	}

	/**
	 * Configures the output to render a SINGLE audience.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @return void
	 */
	protected function prepareAudienceSingle(/* tx_egovapi_domain_model_audience */ $audience) {
		$this->view->assign('audience', $audience);
	}

	/**
	 * Configures the output to render LIST of views.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @param tx_egovapi_domain_model_view[] $views
	 * @return void
	 */
	protected function prepareViewList(/* tx_egovapi_domain_model_audience */ $audience, array $views) {
		$this->view->assign('audience', $audience);
		$this->view->assign('views', $views);

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['audience.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['audience.']['singlePid'] == $GLOBALS['TSFE']->id);
		$hasParent = (t3lib_div::inList($this->settings['displayLevels'], 'AUDIENCE') || !($parentListIsCurrentPage || $parentSingleIsCurrentPage));


		$this->view->assign('hasParent', $hasParent);
	}

	/**
	 * Configures the output to render a SINGLE view.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @return void
	 */
	protected function prepareViewSingle(/* tx_egovapi_domain_model_view */ $view) {
		$this->view->assign('view', $view);

			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['audience.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['audience.']['singlePid'] == $GLOBALS['TSFE']->id);
		$hasParent = (t3lib_div::inList($this->settings['displayLevels'], 'AUDIENCE') || !($parentListIsCurrentPage || $parentSingleIsCurrentPage));

		$this->view->assign('hasParent', $hasParent);
	}

	/**
	 * Configures the output to render LIST of domains.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @param tx_egovapi_domain_model_domain[] $domains
	 * @return void
	 */
	protected function prepareDomainList(/* tx_egovapi_domain_model_view */ $view, array $domains) {
		$this->view->assign('view', $view);
		$this->view->assign('domains', $domains);

		$this->populateDomainConditions();
	}

	/**
	 * Configures the output to render a SINGLE domain.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @return void
	 */
	protected function prepareDomainSingle(/* tx_egovapi_domain_model_domain */ $domain) {
		$this->view->assign('domain', $domain);

		$this->populateDomainConditions();
	}

	/**
	 * Populates domain conditions.
	 *
	 * @return void
	 */
	protected function populateDomainConditions() {
			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['view.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['view.']['singlePid'] == $GLOBALS['TSFE']->id);
		$hasParent = (t3lib_div::inList($this->settings['displayLevels'], 'VIEW') || !($parentListIsCurrentPage || $parentSingleIsCurrentPage));

		$this->view->assign('hasParent', $hasParent);

			// Blocks to show
		$this->view->assign('showLevelInformation',   t3lib_div::inList($this->settings['displayBlocks.']['domain'], 'LEVEL_INFORMATION'));
		$this->view->assign('showGeneralInformation', t3lib_div::inList($this->settings['displayBlocks.']['domain'], 'GENERAL_INFORMATION'));
		$this->view->assign('showNews',               t3lib_div::inList($this->settings['displayBlocks.']['domain'], 'NEWS'));
		$this->view->assign('showSubdomains',         t3lib_div::inList($this->settings['displayBlocks.']['domain'], 'SUBDOMAINS'));
		$this->view->assign('showDescriptor',         t3lib_div::inList($this->settings['displayBlocks.']['domain'], 'DESCRIPTOR'));
		$this->view->assign('showSynonym',            t3lib_div::inList($this->settings['displayBlocks.']['domain'], 'SYNONYM'));
	}

	/**
	 * Configures the output to render LIST of topics.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @param tx_egovapi_domain_model_topic[] $topics
	 * @return void
	 */
	protected function prepareTopicList(/* tx_egovapi_domain_model_domain */ $domain, array $topics) {
		$this->view->assign('domain', $domain);
		$this->view->assign('topics', $topics);

		$this->populateTopicConditions();
	}

	/**
	 * Configures the output to render a SINGLE topic.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @return void
	 */
	protected function prepareTopicSingle(/* tx_egovapi_domain_model_topic */ $topic) {
		$this->view->assign('topic', $topic);

		$this->populateTopicConditions();
	}

	/**
	 * Populates topic conditions.
	 *
	 * @return void
	 */
	protected function populateTopicConditions() {
			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['domain.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['domain.']['singlePid'] == $GLOBALS['TSFE']->id);
		$hasParent = (t3lib_div::inList($this->settings['displayLevels'], 'DOMAIN') || !($parentListIsCurrentPage || $parentSingleIsCurrentPage));

		$this->view->assign('hasParent', $hasParent);

			// Blocks to show
		$this->view->assign('showLevelInformation',   t3lib_div::inList($this->settings['displayBlocks.']['topic'], 'LEVEL_INFORMATION'));
		$this->view->assign('showGeneralInformation', t3lib_div::inList($this->settings['displayBlocks.']['topic'], 'GENERAL_INFORMATION'));
		$this->view->assign('showNews',               t3lib_div::inList($this->settings['displayBlocks.']['topic'], 'NEWS'));
		$this->view->assign('showSubtopics',          t3lib_div::inList($this->settings['displayBlocks.']['topic'], 'SUBTOPICS'));
		$this->view->assign('showDescriptor',         t3lib_div::inList($this->settings['displayBlocks.']['topic'], 'DESCRIPTOR'));
		$this->view->assign('showSynonym',            t3lib_div::inList($this->settings['displayBlocks.']['topic'], 'SYNONYM'));
	}

	/**
	 * Configures the output to render LIST of services.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @param tx_egovapi_domain_model_service[] $services
	 * @return void
	 */
	protected function prepareServiceList(/* tx_egovapi_domain_model_topic */ $topic, array $services) {
		$this->view->assign('topic', $topic);
		$this->view->assign('services', $services);

		$this->populateServiceConditions();
	}

	/**
	 * Configures the output to render a SINGLE service.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return void
	 */
	protected function prepareServiceSingle(/* tx_egovapi_domain_model_service */ $service) {
		$this->registerServiceForRdf($service);

		$this->view->assign('service', $service);
		$this->view->assign('vcardUrl', $this->settings['vcardUrl']);

		$this->populateServiceConditions();
	}

	/**
	 * Populates service conditions.
	 *
	 * @return void
	 */
	protected function populateServiceConditions() {
			// If parent level is not allowed and target for parent list or single mode
			// is the same as current page, then remove content of HAS_PARENT subpart
		$parentListIsCurrentPage = ($this->settings['targets.']['topic.']['listPid'] == $GLOBALS['TSFE']->id);
		$parentSingleIsCurrentPage = ($this->settings['targets.']['topic.']['singlePid'] == $GLOBALS['TSFE']->id);
		$hasParent = (t3lib_div::inList($this->settings['displayLevels'], 'TOPIC') || !($parentListIsCurrentPage || $parentSingleIsCurrentPage));

		$this->view->assign('hasParent', $hasParent);

			// Blocks to show
		$this->view->assign('showLevelInformation',   t3lib_div::inList($this->settings['displayBlocks.']['service'], 'LEVEL_INFORMATION'));
		$this->view->assign('showGeneralInformation', t3lib_div::inList($this->settings['displayBlocks.']['service'], 'GENERAL_INFORMATION'));
		$this->view->assign('showPrerequisites',      t3lib_div::inList($this->settings['displayBlocks.']['service'], 'PREREQUISITES'));
		$this->view->assign('showProcedure',          t3lib_div::inList($this->settings['displayBlocks.']['service'], 'PROCEDURE'));
		$this->view->assign('showForms',              t3lib_div::inList($this->settings['displayBlocks.']['service'], 'FORMS'));
		$this->view->assign('showDocumentsRequired',  t3lib_div::inList($this->settings['displayBlocks.']['service'], 'DOCUMENTS_REQUIRED'));
		$this->view->assign('showServiceProvided',    t3lib_div::inList($this->settings['displayBlocks.']['service'], 'SERVICE_PROVIDED'));
		$this->view->assign('showFee',                t3lib_div::inList($this->settings['displayBlocks.']['service'], 'FEE'));
		$this->view->assign('showLegalRegulation',    t3lib_div::inList($this->settings['displayBlocks.']['service'], 'LEGAL_REGULATION'));
		$this->view->assign('showDocumentsOther',     t3lib_div::inList($this->settings['displayBlocks.']['service'], 'DOCUMENTS_OTHER'));
		$this->view->assign('showRemarks',            t3lib_div::inList($this->settings['displayBlocks.']['service'], 'REMARKS'));
		$this->view->assign('showApproval',           t3lib_div::inList($this->settings['displayBlocks.']['service'], 'APPROVAL'));
		$this->view->assign('showContact',            t3lib_div::inList($this->settings['displayBlocks.']['service'], 'CONTACT'));
		$this->view->assign('showBackToList',         t3lib_div::inList($this->settings['displayBlocks.']['service'], 'BACK_TO_LIST'));
	}

	/**
	 * Returns a newly created Tx_Fluid_View_StandaloneView object.
	 * Code is based on method render of tslib_content_FluidTemplate.
	 *
	 * @return void
	 * @see tslib_content_FluidTemplate
	 * @throws RuntimeException
	 * @throws InvalidArgumentException
	 */
	protected function initializeOutput() {
		// Check if the needed extensions are installed
		if (!t3lib_extMgm::isLoaded('fluid')) {
			throw new RuntimeException('You need to install "Fluid" in order to use a FLUID template', 1335963295);
		}

		$mode = strtolower($this->settings['mode']);
		$level = strtolower($this->settings['level']);

		$conf = $this->settings['templates.'][$mode . '.'][$level . '.']['fluid.'];

		/**
		 * 1. Initializing Fluid StandaloneView and setting configuration parameters
		 **/
		$this->view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
			// fetch the Fluid template
		$file = isset($conf['file.'])
			? $this->cObj->stdWrap($conf['file'], $conf['file.'])
			: $conf['file'];
		$templatePathAndFilename = $GLOBALS['TSFE']->tmpl->getFileName($file);
		$this->view->setTemplatePathAndFilename($templatePathAndFilename);

		// Override the default layout path via TypoScript
		$layoutRootPath = isset($conf['layoutRootPath.'])
			? $this->cObj->stdWrap($conf['layoutRootPath'], $conf['layoutRootPath.'])
			: $conf['layoutRootPath'];
		if($layoutRootPath) {
			$layoutRootPath = t3lib_div::getFileAbsFileName($layoutRootPath);
			$this->view->setLayoutRootPath($layoutRootPath);
		}

		// Override the default partials path via TypoScript
		$partialRootPath = isset($conf['partialRootPath.'])
			? $this->cObj->stdWrap($conf['partialRootPath'], $conf['partialRootPath.'])
			: $conf['partialRootPath'];
		if($partialRootPath) {
			$partialRootPath = t3lib_div::getFileAbsFileName($partialRootPath);
			$this->view->setPartialRootPath($partialRootPath);
		}

		// Override the default format
		$format = isset($conf['format.'])
			? $this->cObj->stdWrap($conf['format'], $conf['format.'])
			: $conf['format'];
		if ($format) {
			$this->view->setFormat($format);
		}

		// Set some default variables for initializing Extbase
		$this->view->getRequest()->setPluginName('Pi1');
		$this->view->getRequest()->setControllerExtensionName($this->pObj->extKey);
		//$this->view->getRequest()->setControllerName($requestControllerName);
		//$this->view->getRequest()->setControllerActionName($requestControllerActionName);

		/**
		 * 2. Default variable assignment
		 */
		$levels = array('audience', 'view', 'domain', 'topic', 'service');
		$keys = array('listPid', 'singlePid');
		$targets = array();
		foreach ($levels as $level) {
			$targets[$level] = array();
			foreach ($keys as $key) {
				$targets[$level][$key] = $this->settings['targets.'][$level . '.'][$key];
			}
		}
		$this->view->assign('targets', $targets);

		/**
		 * 3. TypoScript variable assignment
 		 */
		if (isset($conf['variables.'])) {
			$reservedVariables = array('data', 'current');
				// Accumulate the variables to be replaced
				// and loop them through cObjGetSingle
			$variables = (array) $conf['variables.'];
			foreach ($variables as $variableName => $cObjType) {
				if (is_array($cObjType)) {
					continue;
				}
				if (!in_array($variableName, $reservedVariables)) {
					$this->view->assign($variableName, $this->cObj->cObjGetSingle($cObjType, $variables[$variableName . '.']));
				} else {
					throw new InvalidArgumentException('Cannot use reserved name "' . $variableName . '" as variable name in FLUIDTEMPLATE.', 1288095720);
				}
			}
		}
	}
}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi1/FluidRenderer.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi1/FluidRenderer.php']);
}

?>