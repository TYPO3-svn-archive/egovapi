<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'Classes/Controller/Pi1/class.tx_egovapi_pi1.php', '_pi1', 'list_type', 1);

// RealURL auto-configuration
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/RealUrl.php:tx_egovapi_hooks_realurl->addEgovApiConfig';

?>