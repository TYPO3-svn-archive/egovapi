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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


/**
 * Cache engine helper for web service result sets.
 *
 * @category    DAO
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_dao_cache {

	/**
	 * Initializes the caching framework by loading the cache manager and factory
	 * into the global context.
	 *
	 * @return void
	 */
	public static function initializeCachingFramework() {
		t3lib_cache::initializeCachingFramework();
	}

	/**
	 * Initializes the web service cache.
	 *
	 * @return void
	 */
	public static function initWebServiceCache() {
		try {
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['egovapi'])) {
				$backend = $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['egovapi']['backend'];
				$options = $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['egovapi']['options'];
			} else {
					// Transient storage, will be better than nothing
				$backend = 't3lib_cache_backend_TransientMemoryBackend';
				$options = array();
			}

			$GLOBALS['typo3CacheFactory']->create(
				'egovapi',
				't3lib_cache_frontend_VariableFrontend',
				$backend,
				$options
			);
		} catch (t3lib_cache_exception_DuplicateIdentifier $e) {
			// Do nothing, an eGov API cache already exists
		}
	}

	/**
	 * Returns a proper cache key.
	 *
	 * @param mixed $config
	 * @return void
	 */
	public static function getCacheKey($config) {
		if (is_array($config)) {
				// json_encode is quicker than serialize and anyway sufficient for us
			$config = json_encode($config);
		}
		return md5($config);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Dao/Cache.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Dao/Cache.php']);
}

?>