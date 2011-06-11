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
 * Helper functions for the Constant Editor.
 *
 * @category    Utility
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_utility_constants {

	/**
	 * @var string
	 */
	protected $extKey = 'egovapi';

	/**
	 * @var array
	 */
	protected $constants;

	/**
	 * Default constructor
	 */
	public function __construct() {
		$this->constants = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		if (!is_array($this->constants)) {
			$this->constants = array();
		}
	}

	/**
	 * Returns a dropbox with all communities.
	 *
	 * @param array $params
	 * @param t3lib_tsparser_ext $pObj
	 * @return string
	 */
	public function getCommunities(array $params, t3lib_tsparser_ext $pObj = NULL) {
		if (isset($pObj->setup['constants']['plugin.']['tx_' . $this->extKey . '.'])) {
			$constants = t3lib_div::array_merge_recursive_overrule(
				$this->constants,
				$pObj->setup['constants']['plugin.']['tx_' . $this->extKey . '.'],
				FALSE, FALSE
			);

			//if (!$constants['wsdl']) {
			//	return 'WSDL is not defined.';
			//}

			$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $constants);
			tx_egovapi_domain_repository_factory::injectDao($dao);
		}

		/** @var tx_egovapi_domain_repository_communityRepository $communityRepository */
		$communityRepository = tx_egovapi_domain_repository_factory::getRepository('community');
		/** @var tx_egovapi_domain_model_community[] $communities */
		$communities = $communityRepository->findAll();

		$html = '';
		$html .= '<select name="' . $params['fieldName'] . '" id="' . $params['fieldName'] . '">';
		$html .= '<option value="00-00"></option>';

		$previousCanton = '';
		foreach ($communities as $community) {
			if ($previousCanton !== substr($community->getId(), 0, 2)) {
				if ($previousCanton !== '') {
					$html .= '</optgroup>';
				}
				$name = $community->getName();
				// Strip off "Kanton" or "Canton"
				$name = substr($name, strpos($name, ' ') + 1);
				$html .= '<optgroup label="' . $name . '">';
			}

			$html .= '<option value="' . $community->getId() . '"';
			if ($community->getId() === $params['fieldValue']) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $community->getName() . '</option>';

			$previousCanton = substr($community->getId(), 0, 2);
		}

		$html .= '</optgroup></select>';

		return $html;
	}

	/**
	 * Returns a block name given its number.
	 *
	 * @param integer $blockNumber
	 * @return string
	 */
	public static function getBlockName($blockNumber) {
		$blocks = array(
			1 => 'LEVEL_INFORMATION',
			2 => 'GENERAL_INFORMATION',
			3 => 'PREREQUISITES',
			4 => 'PROCEDURE',
			5 => 'FORMS',
			6 => 'DOCUMENTS_REQUIRED',
			7 => 'SERVICE_PROVIDED',
			8 => 'FEE',
			9 => 'LEGAL_REGULATION',
			10 => 'DOCUMENTS_OTHER',
			11 => 'REMARKS',
			12 => 'APPROVAL',
			13 => 'CONTACT',
		);

		if (isset($blocks[$blockNumber])) {
			return $blocks[$blockNumber];
		} else {
			return NULL;
		}
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Utility/Constants.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Utility/Constants.php']);
}

?>