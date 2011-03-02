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
 * Helper functions for Objects.
 *
 * @category    Utility
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_utility_objects {


	/**
	 * Alphabetically sorts an array of objects by a given sorting property.
	 *
	 * @param array $objects Array of objects
	 * @param string $sortingProperty
	 * @return void
	 */
	public static function sort(array &$objects, $sortingProperty) {
		$keyValues = array();
		foreach ($objects as $key => $object) {
			$propertyGetterName = 'get' . ucfirst($sortingProperty);

			if (method_exists($object, $propertyGetterName)) {
				$value = call_user_func(array($object, $propertyGetterName));
			} else {
				$value = '';
			}

			$keyValues[$key] = $value;
		}

		array_multisort($keyValues, SORT_ASC, $objects);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Utility/Objects.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Utility/Objects.php']);
}

?>