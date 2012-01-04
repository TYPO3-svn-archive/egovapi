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
 * Hook interface for the prepare view process.
 *
 * @category    Hook Interface
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
interface tx_egovapi_interfaces_template_viewHook {

	/**
	 * Post process of the subparts and markers.
	 *
	 * @param tx_egovapi_domain_model_view $view
	 * @param string $mode Either 'list' or 'single'
	 * @param array &$subparts
	 * @param array &$markers
	 * @param tx_egovapi_controller_pi1_templateRenderer $pObj
	 * @return void
	 */
	public function postProcessViewSubpartsMarkers(tx_egovapi_domain_model_view $view, $mode, array &$subparts, array &$markers, tx_egovapi_controller_pi1_templateRenderer $pObj);

}

?>