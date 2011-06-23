<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);
$enableSelectorPlugins = isset($extConf['enableSelectorPlugins']) && (bool)$extConf['enableSelectorPlugins'];

t3lib_extMgm::addPItoST43($_EXTKEY, 'Classes/Controller/Pi1/class.tx_egovapi_pi1.php', '_pi1', 'list_type', 1);

if ($enableSelectorPlugins) {
	t3lib_extMgm::addPItoST43($_EXTKEY, 'Classes/Controller/Pi2/class.tx_egovapi_pi2.php', '_pi2', 'list_type', 0);

	// Ajax configuration (through eID)
	$TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY . '_pi2'] = 'EXT:' . $_EXTKEY . '/Classes/Controller/Pi2/Ajax.php';
}

// RealURL auto-configuration
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/realurl/class.tx_realurl_autoconfgen.php']['extensionConfiguration'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/RealUrl.php:tx_egovapi_hooks_realurl->addEgovApiConfig';

// Register the cache garbage collector task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_' . $_EXTKEY . '_service_latestChangesCleanupTask'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml:latestChangesCleanupTask.name',
	'description'      => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml:latestChangesCleanupTask.description',
	'additionalFields' => 'tx_egovapi_service_latestChangesCleanupAdditionalFieldProvider',
);

?>