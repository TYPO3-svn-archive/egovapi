<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);
$enableSelectorPlugins = isset($extConf['enableSelectorPlugins']) && (bool)$extConf['enableSelectorPlugins'];
$enableRdfGenerator = isset($extConf['enableRdfGenerator']) && (bool)$extConf['enableRdfGenerator'];

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key,pages';

// Register the plugins
t3lib_extMgm::addPlugin(array(
	'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
), 'list_type');

if ($enableSelectorPlugins) {
	t3lib_extMgm::addPlugin(array(
		'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi2',
		$_EXTKEY . '_pi2',
		t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
	), 'list_type');
}

if ($enableRdfGenerator) {
	t3lib_extMgm::addPlugin(array(
		'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi3',
		$_EXTKEY . '_pi3',
		t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
	), 'list_type');
}

// Register the FlexForms
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Pi1.xml');

// Register the context-sensitive help for FlexForm
t3lib_extMgm::addLLrefForTCAdescr('tt_content.pi_flexform.' . $_EXTKEY . '_pi1.list', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh.xml');

// Initialize static extension templates
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Settings/', 'eGov API settings');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Styles/', 'eGov API CSS-styles');
if ($enableSelectorPlugins) {
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Selector/', 'eGov API Selector settings');
}

if (TYPO3_MODE === 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_egovapi_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Controller/Pi1/class.tx_egovapi_pi1_wizicon.php';
	if ($enableSelectorPlugins) {
		$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_egovapi_pi2_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Controller/Pi2/class.tx_egovapi_pi2_wizicon.php';
	}
	if ($enableRdfGenerator) {
		$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_egovapi_pi3_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Controller/Pi3/class.tx_egovapi_pi3_wizicon.php';
	}
}

?>
