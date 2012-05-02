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
 * Vcard renderer for plugin 'eGov API' for the 'egovapi' extension.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_controller_pi1_vcardRenderer extends tx_egovapi_controller_pi1_templateRenderer {

	/**
	 * Renders the vCard.
	 *
	 * @return string
	 */
	public function render() {
		$this->initializeRender();

			// Fix some markers
		$email = $this->markers['SERVICE_CONTACT_EMAIL'];
		$openingHours = $this->markers['SERVICE_CONTACT_OPENING_HOURS'];
		$department = $this->markers['SERVICE_CONTACT_DEPARTMENT'];

		$this->markers['SERVICE_CONTACT_EMAIL'] = str_replace(array('&#64;', '&#46;'), array('@', '.'), $email);
		$this->markers['REVISION'] = date('Ymd\THis\Z');
		$this->markers['SERVICE_CONTACT_OPENING_HOURS'] = str_replace(
			"\n", '\n',	// Use literal \n marker for new lines
			trim(strip_tags(str_replace(
				array('</p>', '<br />', '&nbsp;'),
				array("\n\n", "\n", ' '),
				$openingHours
			)))
		);
		if (!$department) {
				// Office is defined but not department and due to logical inversion
				// of fields, see http://forge.typo3.org/issues/27467, we take over
				// information from SERVICE_CONTACT_OFFICE instead to have a valid
				// vCard with a proper office defined
			$this->markers['SERVICE_CONTACT_DEPARTMENT'] = $this->markers['SERVICE_CONTACT_OFFICE'];
			$this->markers['SERVICE_CONTACT_OFFICE'] = '';
		}

		$output = $this->cObj->substituteSubpartArray($this->template, $this->subparts);
		$output = $this->cObj->substituteMarkerArray($output, $this->markers, '###|###');

		$filename = 'contact_' . $this->markers['SERVICE_ID'] . '.vcf';

		t3lib_div::flushOutputBuffers();
		header('Content-Type: text/x-vcard; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		echo str_replace("\n", "\r\n", $output);
		exit;
	}

	/**
	 * Initializes the template by loading the corresponding file
	 * and preparing global marker substitution.
	 *
	 * @return void
	 */
	protected function initializeOutput() {
		$templateFile = 'EXT:egovapi/Resources/Private/Templates/Vcard.txt';

		$this->template = $this->cObj->fileResource($templateFile);
		$this->subparts = array();
		$this->markers = array();
	}

	/**
	 * Configures the output to render a SINGLE service.
	 *
	 * @param tx_egovapi_domain_model_service $service
	 * @return void
	 */
	protected function prepareServiceSingle(/* tx_egovapi_domain_model_service */ $service) {
		parent::prepareServiceSingle($service);
	}

	/**
	 * Configures the output to render LIST of services.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @param tx_egovapi_domain_model_service[] $services
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareServiceList(/* tx_egovapi_domain_model_topic */ $topic, array $services) {
		throw new RuntimeException('prepareServiceList is not implemented', 1308140884);
	}

	/**
	 * Configures the output to render a SINGLE topic.
	 *
	 * @param tx_egovapi_domain_model_topic $topic
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareTopicSingle(/* tx_egovapi_domain_model_topic */ $topic) {
		throw new RuntimeException('prepareTopicSingle is not implemented', 1308140910);
	}

	/**
	 * Configures the output to render LIST of topics.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @param tx_egovapi_domain_model_topic[] $topics
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareTopicList(/* tx_egovapi_domain_model_domain */ $domain, array $topics) {
		throw new RuntimeException('prepareTopicList is not implemented', 1308140928);
	}

	/**
	 * Configures the output to render a SINGLE domain.
	 *
	 * @param tx_egovapi_domain_model_domain $domain
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareDomainSingle(/* tx_egovapi_domain_model_domain */ $domain) {
		throw new RuntimeException('prepareDomainSingle is not implemented', 1308140944);
	}

	/**
	 * Configures the output to render LIST of domains.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @param tx_egovapi_domain_model_domain[] $domains
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareDomainList(/* tx_egovapi_domain_model_view */ $view, array $domains) {
		throw new RuntimeException('prepareDomainList is not implemented', 1308140962);
	}

	/**
	 * Configures the output to render a SINGLE view.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareViewSingle( /* tx_egovapi_domain_model_view */ $view) {
		throw new RuntimeException('prepareViewSingle is not implemented', 1308140987);
	}

	/**
	 * Configures the output to render LIST of views.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @param tx_egovapi_domain_model_view[] $views
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareViewList(/* tx_egovapi_domain_model_audience */ $audience, array $views) {
		throw new RuntimeException('prepareViewList is not implemented', 1308141020);
	}

	/**
	 * Configures the output to render a SINGLE audience.
	 *
	 * @param tx_egovapi_domain_model_audience $audience
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareAudienceSingle(/* tx_egovapi_domain_model_audience */ $audience) {
		throw new RuntimeException('prepareAudienceSingle is not implemented', 1308141047);
	}

	/**
	 * Configures the output to render LIST of audiences.
	 *
	 * @param tx_egovapi_domain_model_audience[] $audiences
	 * @return void
	 * @throws RuntimeException
	 */
	protected function prepareAudienceList(array $audiences) {
		throw new RuntimeException('prepareAudienceList is not implemented', 1308141064);
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi1/VcardRenderer.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi1/VcardRenderer.php']);
}

?>