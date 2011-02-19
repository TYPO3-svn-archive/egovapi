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
 * RealURL auto-configuration.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_hooks_realurl {

	/**
	 * Generates additional RealURL configuration and merges it with provided configuration.
	 *
	 * @param array $params
	 * @param tx_realurl_autoconfgen $pObj
	 * @return array
	 */
	public function addEgovApiConfig(array $params, tx_realurl_autoconfgen $pObj) {
		return array_merge_recursive($params['config'], array(
			'postVarSets' => array(
				'_DEFAULT' => array(
					'audience' => array(
						array(
							'GETvar' => 'tx_egovapi_pi1[audience]',
						),
					),
					'view' => array(
						array(
							'GETvar' => 'tx_egovapi_pi1[view]',
						),
					),
					'domain' => array(
						array(
							'GETvar' => 'tx_egovapi_pi1[domain]',
						),
					),
					'topic' => array(
						array(
							'GETvar' => 'tx_egovapi_pi1[topic]',
						),
					),
					'service' => array(
						array(
							'GETvar' => 'tx_egovapi_pi1[service]',
						),
					),
					'action' => array(
						array(
							'GETvar' => 'tx_egovapi_pi1[action]',
						)
					),
					'mode' => array(
						array(
							'GETvar' => 'tx_egovapi_pi1[mode]',
						)
					),
				),
			),
		));
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Hooks/RealUrl.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Hooks/RealUrl.php']);
}

?>