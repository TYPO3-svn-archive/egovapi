<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Xavier Perseguers <xavier@causal.ch>
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

// Exit, if script is called directly (must be included via eID in index_ts.php)
if (!defined('PATH_typo3conf')) {
	die('Script cannot be accessed directly!');
}

class tx_egovapi_controller_pi3_Rdf {

	/**
	 * @var integer
	 */
	protected $organization = 0;

	/**
	 * Default action.
	 *
	 * @return string RDF output
	 */
	public function main() {
		$this->initTSFE();

		$host = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
		$references = array();
		$rdf = array();
		$rdf[] = '@prefix :<http://semantic.cyberadmin.ch/ontologies/spso#> .';

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'organization, service, version, language, url',
			'tx_egovapi_rdf',
			'hidden=0',
			'',
			'organization, service, version'
		);
		while (FALSE !== ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
			if (!$this->organization) {
				$this->organization = $row['organization'];
			}
			$identifier = sprintf(
				'%s-%s-%s-%s',
				$row['service'],
				$row['organization'],
				$row['language'],
				$row['version']
			);

			$reference = $host . $identifier;
			$rdf[] = sprintf('
<%s>
	a :ProvidedService ;
	:isService <http://semantic.cyberadmin.ch/service/%s> ;
	:url "%s" ;
	:language "%s" .
',
				$host . $identifier,
				$row['service'],
				htmlspecialchars($row['url']),
				strtoupper($row['language'])
			);

			$references[] = $reference;
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		if (count($references) > 0) {
			$rdf[] = sprintf('<http://semantic.cyberadmin.ch/municipality/%s>', $this->organization);
			for ($i = 0; $i < count($references); $i++) {
				$pattern = $i == count($references) - 1
					? ':providesService <%s>.'
					: ':providesService <%s> ;';
				$rdf[] = TAB . sprintf($pattern, $references[$i]);
			}
		}

		return implode(LF, $rdf);
	}

	/**
	 * Returns the organization.
	 *
	 * @return integer
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * Initializes TSFE and sets $GLOBALS['TSFE'], actually only the
	 * database connection.
	 *
	 * @return void
	 */
	protected function initTSFE() {
		$GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], t3lib_div::_GP('id'), '');
		$GLOBALS['TSFE']->connectToDB();
	}

}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi3/Rdf.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Controller/Pi3/Rdf.php']);
}

/** @var $rdfGenerator tx_egovapi_controller_pi3_Rdf */
$rdfGenerator = t3lib_div::makeInstance('tx_egovapi_controller_pi3_Rdf');

$output = $rdfGenerator->main();
$organization = $rdfGenerator->getOrganization();

header('Pragma: public');
header('Content-Length: ' . strlen($output));
header('Content-disposition: attachment; filename=services-' . $organization . '.n3');
header('Content-Type: text/rdf+n3');

echo $output;

?>