#
# Table structure for table 'cf_egovapi'
#
CREATE TABLE cf_egovapi (
	id int(11) unsigned NOT NULL auto_increment,
	identifier varchar(128) DEFAULT '' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	content mediumblob,
	lifetime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (id),
	KEY cache_id (identifier)
) ENGINE=InnoDB;


#
# Table structure for table 'cf_egovapi_tags'
#
CREATE TABLE cf_egovapi_tags (
	id int(11) unsigned NOT NULL auto_increment,
	identifier varchar(128) DEFAULT '' NOT NULL,
	tag varchar(128) DEFAULT '' NOT NULL,

	PRIMARY KEY (id),
	KEY cache_id (identifier),
	KEY cache_tag (tag)
) ENGINE=InnoDB;

#
# Table structure for table 'tx_egovapi_rdf'
#
CREATE TABLE tx_egovapi_rdf (
	identifier char(32) DEFAULT '' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	organization int(11) DEFAULT '0' NOT NULL,
	service int(11) DEFAULT '0' NOT NULL,
	version int(11) DEFAULT '0' NOT NULL,
	language char(2) DEFAULT 'de' NOT NULL,
	url varchar(500) DEFAULT '' NOT NULL,

	PRIMARY KEY (identifier)
) ENGINE=InnoDB;