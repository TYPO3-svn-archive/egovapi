<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Xavier Perseguers <xavier@typo3.org>
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
 * Update class for the 'egovapi' extension.
 *
 * @category    Update Wizard
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@typo3.org>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class ext_update extends t3lib_SCbase {

	protected $extKey = 'egovapi';

	protected $version;

	/**
	 * Default constructor.
	 */
	public function __construct() {
		$this->version = class_exists('t3lib_utility_VersionNumber')
				? t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version)
				: t3lib_div::int_from_ver(TYPO3_version);
	}

	/**
	 * Checks whether the "UPDATE!" menu item should be shown.
	 * This is the case for TYPO3 4.4 and 4.5 (supported versions).
	 *
	 * @return boolean
	 */
	public function access() {
		return ($this->version < 4006000);
	}

	/**
	 * Main method that is called whenever UPDATE! menu was clicked.
	 * Outputs a HTML form to dynamically generate locallang*.xml files.
	 *
	 * @return string HTML to display
	 */
	public function main() {
		$this->content = '';

		$this->doc = t3lib_div::makeInstance('noDoc');
		$this->doc->backPath = $GLOBALS['BACK_PATH'];
		$this->doc->form = '<form action="" method="post">';

		$title = 'Localization Files Converter';
		$this->content .= $this->doc->startPage($title);
		$this->content .= $this->doc->header($title);
		$this->content .= $this->doc->spacer(5);

		$this->content .= $this->doc->section('',
			'Extension ' . $this->extKey . ' is using XLIFF as localization format which is only supported'
			. ' since TYPO3 4.6.<br />'
			. 'This form lets you generate localization files compatible with the TYPO3 version you are'
			. ' using: TYPO3 ' . TYPO3_version . '.<br />'
			. 'Please note that you only should start the conversion process when installing or upgrading'
			. ' this extension.'
		);

		if (t3lib_div::_GET('convert')) {
			$messages = $this->generateLlXml();
			$this->content .= $this->doc->section('Generated files', implode('<br />', $messages));
		} else {
			$files = $this->getXliffFiles();
			$languages = $this->getLanguages($files);
			$languages[0] = 'default (English)';

			$this->content .= $this->doc->section('XLIFF files', implode('<br />', $files));
			$this->content .= $this->doc->section('Languages', implode('<br />', $languages));
			$this->content .= $this->doc->spacer(20);
			$this->content .= '<a href="' . t3lib_div::linkThisScript(array('convert' => 1)) . '">Click here</a>'
				. ' to start the conversion process.';
		}

		return $this->content;
	}

	/**
	 * Generates ll-XML localization files from XLIFF files of this extension.
	 * ll-XML localization files for default language will be stored next to the
	 * XLIFF files. Other languages will be stored within typo3conf/l10n/, as for
	 * localization files retrieved from TER.
	 *
	 * @return array
	 */
	protected function generateLlXml() {
		$files = $this->getXliffFiles();

			// Group files by language
		$sourceFiles = array_flip($this->getLanguages($files));
		foreach ($sourceFiles as $languageKey => $foo) {
			$sourceFiles[$languageKey] = array_filter($files, function($item) use ($languageKey) {
				if ($languageKey === 'default') {
					return t3lib_div::isFirstPartOfStr(basename($item), 'locallang');
				} else {
					return t3lib_div::isFirstPartOfStr(basename($item), $languageKey . '.locallang');
				}
			});
		}

			// Convert localization files
		$messages = array();
		$extDirectoryPrefix = substr(t3lib_extMgm::extPath($this->extKey), strlen(PATH_site));
		foreach ($sourceFiles as $languageKey => $files) {
			$l10nDirectory = 'typo3conf/l10n/' . $languageKey . '/' . $this->extKey . '/';
			foreach ($files as $file) {
				if ($languageKey === 'default') {
					$targetFile = substr($file, 0, strlen($file) - 4) . '.xml';
				} else {
					$llxmlFile = basename(substr($file, 0, strlen($file) - 4) . '.xml');
					$targetFile = $l10nDirectory . dirname(substr($file, strlen($extDirectoryPrefix))) . '/' . $llxmlFile;
				}

				if ($this->xliff2llxml($languageKey, PATH_site . $file, PATH_site . $targetFile)) {
					$messages[] = 'Created file ' . $targetFile;
				} else {
					$messages[] = 'ERROR creating file ' . $targetFile;
				}
			}
		}

			// Return the success/error messages for generated files
		return $messages;
	}

	/**
	 * Converts XLIFF localization file to ll-XML.
	 *
	 * @param string $languageKey
	 * @param string $xliffFile
	 * @param string $llxmlFile
	 * @return boolean
	 */
	protected function xliff2llxml($languageKey, $xliffFile, $llxmlFile) {
		try {
			$LOCAL_LANG = $this->parseXliff($xliffFile, $languageKey);
		} catch (Exception $e) {
			return FALSE;
		}

		switch (TRUE) {
			case substr($xliffFile, -7) === '_db.xlf':
				$type = 'database';
				$description = sprintf('Language labels for database tables/fields belonging to extension \'%s\'', $this->extKey);
				break;
			case substr($xliffFile, -8) === '_mod.xlf':
				$type = 'module';
				$description = sprintf('Language labels for module fields belonging to extension \'%s\'', $this->extKey);
				break;
			case substr($xliffFile, -8) === '_csh.xlf':
				$type = 'CSH';
				$description = sprintf('Context Sensitive Help language labels for plugin belonging to extension \'%s\'', $this->extKey);
				break;
			default:
				$type = 'module';
				$description = sprintf('Language labels for plugin belonging to extension \'%s\'', $this->extKey);
				break;
		}

		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>';
		$xml[] = '<T3locallang>';
		$xml[] = '	<meta type="array">';
		$xml[] = '		<type>' . $type . '</type>';
		$xml[] = '		<description>' . $description . '</description>';
		$xml[] = '	</meta>';
		$xml[] = '	<data type="array">';
		$xml[] = '		<languageKey index="' . $languageKey . '" type="array">';

		foreach ($LOCAL_LANG[$languageKey] as $key => $data) {
			$xml[] = '			<label index="' . $key . '">' . htmlspecialchars($data[0]['target']) . '</label>';
		}

		$xml[] = '		</languageKey>';
		$xml[] = '	</data>';
		$xml[] = '</T3locallang>';

		try {
			$ret = t3lib_div::mkdir_deep(PATH_site, substr(dirname($llxmlFile), strlen(PATH_site)) . '/');
			$OK = !$ret;
		} catch (RuntimeException $e) {
			$OK = FALSE;
		}

		if ($OK) {
			$OK = t3lib_div::writeFile($llxmlFile, implode(chr(10), $xml));
		}

		return $OK;
	}

	/**
	 * Parses an XLIFF file into a LOCAL_LANG array structure.
	 *
	 * @param string $file
	 * @param string $languageKey
	 * @return array
	 * @throws Exception
	 */
	protected function parseXliff($file, $languageKey) {
		$root = simplexml_load_file($file, 'SimpleXmlElement', \LIBXML_NOWARNING);
		$parsedData = array();
		$bodyOfFileTag = $root->file->body;

		foreach ($bodyOfFileTag->children() as $translationElement) {
			if ($translationElement->getName() === 'trans-unit' && !isset($translationElement['restype'])) {
					// If restype would be set, it could be metadata from Gettext to XLIFF conversion (and we don't need this data)

				$parsedData[(string)$translationElement['id']][0] = array(
					'source' => (string)$translationElement->source,
					'target' => (string)$translationElement->target,
				);
			} elseif ($translationElement->getName() === 'group' && isset($translationElement['restype']) && (string)$translationElement['restype'] === 'x-gettext-plurals') {
					// This is a translation with plural forms
				$parsedTranslationElement = array();

				foreach ($translationElement->children() as $translationPluralForm) {
					if ($translationPluralForm->getName() === 'trans-unit') {
							// When using plural forms, ID looks like this: 1[0], 1[1] etc
						$formIndex = substr((string)$translationPluralForm['id'], strpos((string)$translationPluralForm['id'], '[') + 1, -1);

						$parsedTranslationElement[(int)$formIndex] = array(
							'source' => (string)$translationPluralForm->source,
							'target' => (string)$translationPluralForm->target,
						);
					}
				}

				if (!empty($parsedTranslationElement)) {
					if (isset($translationElement['id'])) {
						$id = (string)$translationElement['id'];
					} else {
						$id = (string)($translationElement->{'trans-unit'}[0]['id']);
						$id = substr($id, 0, strpos($id, '['));
					}

					$parsedData[$id] = $parsedTranslationElement;
				}
			}
		}

		$LOCAL_LANG = array();
		$LOCAL_LANG[$languageKey] = $parsedData;

		return $LOCAL_LANG;
	}

	/**
	 * Returns an array of XLIFF files for this extension.
	 *
	 * @return array
	 */
	protected function getXliffFiles() {
		$files = t3lib_div::removePrefixPathFromList(
			t3lib_div::getAllFilesAndFoldersInPath(array(), t3lib_extMgm::extPath($this->extKey), 'xlf'),
			PATH_site
		);

		return $files;
	}

	/**
	 * Extracts the languages from a list of localization files.
	 *
	 * @param array $files
	 * @return array
	 */
	protected function getLanguages(array $files) {
		$languages = array('default');
		foreach ($files as $file) {
			if (preg_match('/^([^.]+)\.locallang.*\.xlf$/', basename($file), $matches)) {
				if (!in_array($matches[1], $languages)) {
					$languages[] = $matches[1];
				}
			}
		}

		return $languages;
	}

}

?>