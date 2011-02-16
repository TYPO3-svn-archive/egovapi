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
 * An eGov Community repository.
 *
 * @category    Domain\Repository
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_egovapi_domain_repository_communityRepository extends tx_egovapi_domain_repository_abstractRepository {

	protected static $communities = array();

	/**
	 * Finds all available communities.
	 *
	 * @return tx_egovapi_domain_model_community[]
	 */
	public function findAll() {
		if (!count(self::$communities)) {
			self::$communities = array();
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-00', 'Kanton Aargau');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-01', 'Berzirk Aarau');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-02', 'Berzirk Baden');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-03', 'Berzirk Bremgarten');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-04', 'Berzirk Brugg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-05', 'Berzirk Kulm');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-06', 'Berzirk Laufenburg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-07', 'Berzirk Lenzburg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-08', 'Berzirk Muri');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-09', 'Berzirk Rheinfelden');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-10', 'Berzirk Zofingen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '19-11', 'Berzirk Zurzach');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '15-00', 'Kanton Appenzel Ausserrhoden');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '15-01', 'Bezirk Hinterland');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '15-02', 'Bezirk Mittellang');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '15-03', 'Bezirk Vorderland');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '16-00', 'Kanton Appenzel Innerhoden');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '13-00', 'Kanton Basel-Land');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '13-01', 'Bezirk Arlesheim');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '13-02', 'Bezirk Laufen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '13-03', 'Bezirk Liestal');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '13-04', 'Bezirk Sissach');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '13-05', 'Bezirk Waldenburg');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '12-00', 'Kanton Basel-Stadt');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-00', 'Kanton Bern');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-01', 'Amtsbezirk Aarberg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-02', 'Amtsbezirk Aarwangen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-03', 'Amtsbezirk Bern');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-04', 'Amtsbezirk Biel');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-05', 'Amtsbezirk Büren');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-06', 'Amtsbezirk Burgdorf');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-07', 'District de Courtelary');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-08', 'Amtsbezirk Erlach');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-09', 'Amtsbezirk Fraubrunnen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-10', 'Amtsbezirk Frutigen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-11', 'Amtsbezirk Interlaken');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-12', 'Amtsbezirk Konolfingen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-13', 'Amtsbezirk Laupen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-14', 'District de Moutier');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-15', 'District de la Neuveville');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-16', 'Amtsbezirk Nidau');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-17', 'Amtsbezirk Niedersimmental');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-18', 'Amtsbezirk Oberhasli');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-19', 'Amtsbezirk Obersimmental');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-20', 'Amtsbezirk Saanen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-21', 'Amtsbezirk Schwarzenburg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-22', 'Amtsbezirk Seftigen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-23', 'Amtsbezirk Signau');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-24', 'Amtsbezirk Thun');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-25', 'Amtsbezirk Trachselwald');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '02-26', 'Amtsbezirk Wangen');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-00', 'Canton Fribourg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-01', 'District de la Broye');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-02', 'District de la Glâne');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-03', 'District de la Gruyère');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-04', 'District de la Sarine');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-05', 'Bezirk See / District du Lac');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-06', 'Bezirk Sense');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-07', 'District de la Veveyse');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '10-51', 'Département des finances');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '25-00', 'Canton Genève');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '08-00', 'Kanton Glarus');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-00', 'Kanton Graubunden');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-21', 'Bezirk Hinterland');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-22', 'Distretto di Bernina');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-23', 'Bezirk Hinterrhein');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-24', 'Bezirk Imboden');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-25', 'Bezirk Inn');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-26', 'Bezirk Landquart');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-27', 'Bezirk Maloja / Distretto di Maloggia');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-28', 'Distretto di Moesa');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-29', 'Bezirk Plessur');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-30', 'Bezirk Prättigau-Davos');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '18-31', 'Bezirk Surselva');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '26-00', 'Canton Jura');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '26-01', 'District de Delémont');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '26-02', 'District des Franches-Montagnes');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '26-03', 'District de Porrentruy');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '03-00', 'Kanton Luzern');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '03-01', 'Amt Entlebuch');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '03-02', 'Amt Hochdorf');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '03-03', 'Amt Luzern');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '03-04', 'Amt Sursee');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '03-05', 'Amt Willisau');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '24-00', 'Canton Neuchâtel');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '24-01', 'District de Boudry');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '24-02', 'District de la Chaux-de-Fonds');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '24-03', 'District du Locle');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '24-04', 'District de Neuchâtel');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '24-05', 'District du Val-de-Ruz');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '24-06', 'District du Val-de-Travers');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '07-00', 'Kanton Nidwalden');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '06-00', 'Kanton Obwalden');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '14-00', 'Kanton Schaffhausen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '14-01', 'Bezirk Oberklettgau');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '14-02', 'Bezirk Reiat');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '14-03', 'Bezirk Schaffhausen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '14-04', 'Bezirk Schleitheim');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '14-05', 'Bezirk Stein');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '14-06', 'Bezirk Unterklettgau');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '05-00', 'Kanton Schwyz');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '05-01', 'Bezirk Einsiedeln');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '05-02', 'Bezirk Gersau');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '05-03', 'Bezirk Höfe');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '05-04', 'Bezirk Küssnacht (SZ)');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '05-05', 'Bezirk March');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '05-06', 'Bezirk Schwyz');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-00', 'Kanton Solothurn');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-01', 'Bezirk Gaü');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-02', 'Bezirk Thal');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-03', 'Bezirk Bucheggberg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-04', 'Bezirk Dorneck');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-05', 'Bezirk Gösgen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-06', 'Bezirk Wasseramt');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-07', 'Bezirk Lebern');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-08', 'Bezirk Olten');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-09', 'Bezirk Solothurn');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '11-10', 'Bezirk Thierstein');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-00', 'Kanton St. Gallen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-21', 'Wahlkreis St. Gallen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-22', 'Wahlkreis Rorschach');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-23', 'Wahlkreis Rheintal');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-24', 'Wahlkreis Werdenberg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-25', 'Wahlkreis Sarganserland');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-26', 'Wahlkreis See-Gaster');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-27', 'Wahlkreis Toggenburg');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '17-28', 'Wahlkreis Wil');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-00', 'Canton Tessin');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-01', 'Distretto di Bellinzona');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-02', 'Distretto di Blenio');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-03', 'Distretto di Leventina');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-04', 'Distretto di Locarno');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-05', 'Distretto di Lugano');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-06', 'Distretto di Mendrisio');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-07', 'Distretto di Riviera');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '21-08', 'Distretto di Vallemaggia');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-00', 'Kanton Thurgau');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-01', 'Bezirk Arbon');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-02', 'Bezirk Bischofszell');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-03', 'Bezirk Diessenhofen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-04', 'Bezirk Frauenfeld');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-05', 'Bezirk Kreuzlingen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-06', 'Bezirk Münchwilen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-07', 'Bezirk Steckborn');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '20-08', 'Bezirk Weinfelden');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '04-00', 'Kanton Uri');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-00', 'Canton Valais');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-01', 'Bezirk Brig');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-02', 'District de Conthey');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-03', 'District d\'Entremont');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-04', 'Bezirk Goms');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-05', 'District d\'Hérens');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-06', 'Bezirk Leuk');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-07', 'District de Martigny');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-08', 'District de Monthey');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-09', 'Bezirk Raron');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-10', 'District de St-Maurice');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-11', 'District de Sierre');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-12', 'District de Sion');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '23-13', 'Bezirk Visp');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-00', 'Canton Vaud');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-01', 'District d\'Aigle');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-02', 'District d\'Aubonne');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-03', 'District d\'Avenches');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-04', 'District de Cossonay');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-05', 'District d\'Echallens');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-06', 'District de Grandson');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-07', 'District de Lausanne');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-08', 'District de Lavaux');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-09', 'District de Morges');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-10', 'District de Moudon');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-11', 'District de Nyon');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-12', 'District \'Orbe');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-13', 'District d\'Oron');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-14', 'District de Payerne');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-15', 'District du Pays-d\Enhaut');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-16', 'District de Rolle');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-17', 'District de la Vallée');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-18', 'District de Vevey');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '22-19', 'District d\'Yverdon');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '09-00', 'Kanton Zug');

			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-00', 'Kanton Zürich');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-01', 'Bezirk Affoltern');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-02', 'Bezirk Andelfingen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-03', 'Bezirk Bülach');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-04', 'Bezirk Dielsdorf');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-05', 'Bezirk Hinwil');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-06', 'Bezirk Horgen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-07', 'Bezirk Meilen');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-08', 'Bezirk Pfäffikon');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-09', 'Bezirk Uster');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-10', 'Bezirk Winterthur');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-11', 'Bezirk Dietikon');
			self::$communities[] = t3lib_div::makeInstance('tx_egovapi_domain_model_community', '01-12', 'Bezirk Zürich');
		}
		return self::$communities;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/CommunityRepository.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Domain/Repository/CommunityRepository.php']);
}

?>