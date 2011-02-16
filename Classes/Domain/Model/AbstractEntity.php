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
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Abstract entity for eGov API.
 *
 * @category    Domain\Model
 * @package     TYPO3
 * @subpackage  tx_egovapi
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl / SECO (http://www.cyberadmin.ch/)
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
abstract class tx_egovapi_domain_model_abstractEntity {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $author;

	/**
	 * @var integer
	 */
	protected $creationDate;

	/**
	 * @var integer
	 */
	protected $lastModificationDate;

	/**
	 * @var boolean
	 */
	protected $hasDetails;

	/**
	 * Default constructor.
	 *
	 * @param string $id
	 */
	public function __construct($id) {
		$this->id = $id;
		$this->hasDetails = FALSE;
	}

	/**
	 * Gets the id.
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Gets the author.
	 *
	 * @return string
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * Sets the author.
	 *
	 * @param string $author
	 * @return tx_egovapi_domain_model_abstractEntity
	 */
	public function setAuthor($author) {
		$this->author = $author;
		return $this;
	}

	/**
	 * Gets the creation date.
	 *
	 * @return integer
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * Gets the creation date (converting Unix timestamp into proper value for the server)
	 *
	 * @return DateTime Date
	 */
	public function getLocalCreationDate() {
		$dateTime = new DateTime('@' . $this->creationDate);
		$dateTime->setTimezone(new DateTimeZone(date_default_timezone_get()));
		return $dateTime;
	}

	/**
	 * Sets the creation date.
	 *
	 * @param integer $creationDate
	 * @return tx_egovapi_domain_model_abstractEntity
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
		return $this;
	}

	/**
	 * Gets the last modification date.
	 *
	 * @return integer
	 */
	public function getLastModificationDate() {
		return $this->lastModificationDate;
	}

	/**
	 * Gets the last modification date (converting Unix timestamp into proper value for the server)
	 *
	 * @return DateTime Date
	 */
	public function getLocalLastModificationDate() {
		$dateTime = new DateTime('@' . $this->lastModificationDate);
		$dateTime->setTimezone(new DateTimeZone(date_default_timezone_get()));
		return $dateTime;
	}

	/**
	 * Sets the last modification date.
	 *
	 * @param integer $lastModificationDate
	 * @return tx_egovapi_domain_model_abstractEntity
	 */
	public function setLastModificationDate($lastModificationDate) {
		$this->lastModificationDate = $lastModificationDate;
		return $this;
	}

	/**
	 * Sets the internal flag hasDetails.
	 *
	 * @return void
	 * @access private This method is not part of the public API
	 */
	public function setHasDetails() {
		$this->hasDetails = TRUE;
	}

}

?>