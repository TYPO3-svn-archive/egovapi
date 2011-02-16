<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key,pages';

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
), 'list_type');

// Register the FlexForm
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Pi1.xml');

// Register the context-sensitive help for FlexForm
t3lib_extMgm::addLLrefForTCAdescr('tt_content.pi_flexform.' . $_EXTKEY . '_pi1.list', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh.xml');

// Initialize static extension templates
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Settings/', 'eGov API settings');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Styles/', 'eGov API CSS-styles');

if (TYPO3_MODE === 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_egovapi_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Controller/Pi1/class.tx_egovapi_pi1_wizicon.php';
}

?>
