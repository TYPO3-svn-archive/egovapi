<?php

########################################################################
# Extension Manager/Repository config file for ext "egovapi".
#
# Auto generated 08-04-2011 15:49
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'eGov API (Swiss Federal Administration)',
	'description' => 'Official implementation of the swiss eGov Remote API (eGovernment) that allows to access the Reference eGov CH application from SECO which hosts and provides administrative services (cyberadministration).',
	'category' => 'plugin',
	'author' => 'Xavier Perseguers / SECO',
	'author_company' => 'Causal Sàrl',
	'author_email' => 'xavier@causal.ch',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'suggests' => 'fluid',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '1.1.1',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.3.0-4.5.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'fluid' => '1.3.0-0.0.0',
		),
	),
	'_md5_values_when_last_written' => 'a:91:{s:9:"ChangeLog";s:4:"4b71";s:16:"ext_autoload.php";s:4:"74e3";s:21:"ext_conf_template.txt";s:4:"9afc";s:12:"ext_icon.gif";s:4:"fc04";s:17:"ext_localconf.php";s:4:"84ec";s:14:"ext_tables.php";s:4:"ae97";s:14:"ext_tables.sql";s:4:"c30a";s:43:"Classes/Controller/Pi1/AbstractRenderer.php";s:4:"c4e9";s:40:"Classes/Controller/Pi1/FluidRenderer.php";s:4:"c158";s:43:"Classes/Controller/Pi1/TemplateRenderer.php";s:4:"64a6";s:47:"Classes/Controller/Pi1/class.tx_egovapi_pi1.php";s:4:"616b";s:55:"Classes/Controller/Pi1/class.tx_egovapi_pi1_wizicon.php";s:4:"5960";s:21:"Classes/Dao/Cache.php";s:4:"13ee";s:19:"Classes/Dao/Dao.php";s:4:"b3a0";s:26:"Classes/Dao/WebService.php";s:4:"6d52";s:39:"Classes/Domain/Model/AbstractEntity.php";s:4:"a56e";s:33:"Classes/Domain/Model/Audience.php";s:4:"b252";s:34:"Classes/Domain/Model/Community.php";s:4:"6223";s:31:"Classes/Domain/Model/Domain.php";s:4:"39bf";s:32:"Classes/Domain/Model/Service.php";s:4:"1b03";s:30:"Classes/Domain/Model/Topic.php";s:4:"0d99";s:29:"Classes/Domain/Model/View.php";s:4:"8343";s:39:"Classes/Domain/Model/Block/Approval.php";s:4:"17b5";s:38:"Classes/Domain/Model/Block/Contact.php";s:4:"1f6e";s:41:"Classes/Domain/Model/Block/Descriptor.php";s:4:"e05e";s:39:"Classes/Domain/Model/Block/Document.php";s:4:"702d";s:45:"Classes/Domain/Model/Block/DocumentsOther.php";s:4:"83ac";s:48:"Classes/Domain/Model/Block/DocumentsRequired.php";s:4:"9f7d";s:34:"Classes/Domain/Model/Block/Fee.php";s:4:"6e5c";s:35:"Classes/Domain/Model/Block/Form.php";s:4:"f403";s:36:"Classes/Domain/Model/Block/Forms.php";s:4:"e56e";s:49:"Classes/Domain/Model/Block/GeneralInformation.php";s:4:"c054";s:46:"Classes/Domain/Model/Block/LegalRegulation.php";s:4:"e07d";s:50:"Classes/Domain/Model/Block/LegalRegulationItem.php";s:4:"c14f";s:35:"Classes/Domain/Model/Block/News.php";s:4:"65a2";s:43:"Classes/Domain/Model/Block/Prerequisite.php";s:4:"78a0";s:44:"Classes/Domain/Model/Block/Prerequisites.php";s:4:"2f9f";s:40:"Classes/Domain/Model/Block/Procedure.php";s:4:"a886";s:44:"Classes/Domain/Model/Block/ProcedureItem.php";s:4:"bedc";s:38:"Classes/Domain/Model/Block/Remarks.php";s:4:"1cfb";s:46:"Classes/Domain/Model/Block/ServiceProvided.php";s:4:"c8e6";s:40:"Classes/Domain/Model/Block/Subdomain.php";s:4:"4b05";s:41:"Classes/Domain/Model/Block/Subdomains.php";s:4:"c075";s:39:"Classes/Domain/Model/Block/Subtopic.php";s:4:"d34c";s:40:"Classes/Domain/Model/Block/Subtopics.php";s:4:"7248";s:38:"Classes/Domain/Model/Block/Synonym.php";s:4:"148b";s:48:"Classes/Domain/Repository/AbstractRepository.php";s:4:"dab9";s:48:"Classes/Domain/Repository/AudienceRepository.php";s:4:"0e9f";s:49:"Classes/Domain/Repository/CommunityRepository.php";s:4:"ce43";s:46:"Classes/Domain/Repository/DomainRepository.php";s:4:"167d";s:37:"Classes/Domain/Repository/Factory.php";s:4:"1081";s:47:"Classes/Domain/Repository/ServiceRepository.php";s:4:"4755";s:45:"Classes/Domain/Repository/TopicRepository.php";s:4:"d98d";s:44:"Classes/Domain/Repository/ViewRepository.php";s:4:"19ed";s:25:"Classes/Hooks/RealUrl.php";s:4:"3699";s:29:"Classes/Utility/Constants.php";s:4:"3156";s:28:"Classes/Utility/FlexForm.php";s:4:"6281";s:27:"Classes/Utility/Objects.php";s:4:"7f6d";s:30:"Classes/Utility/TypoScript.php";s:4:"9d5b";s:31:"Configuration/FlexForms/Pi1.xml";s:4:"bbfb";s:47:"Configuration/TypoScript/Settings/constants.txt";s:4:"90df";s:43:"Configuration/TypoScript/Settings/setup.txt";s:4:"dd4e";s:41:"Configuration/TypoScript/Styles/setup.txt";s:4:"4fbd";s:36:"Interfaces/Template/AudienceHook.php";s:4:"2dca";s:34:"Interfaces/Template/DomainHook.php";s:4:"95e5";s:34:"Interfaces/Template/RenderHook.php";s:4:"123f";s:35:"Interfaces/Template/ServiceHook.php";s:4:"9d1d";s:33:"Interfaces/Template/TopicHook.php";s:4:"41c0";s:32:"Interfaces/Template/ViewHook.php";s:4:"b3b2";s:40:"Resources/Private/Language/locallang.xml";s:4:"3afe";s:44:"Resources/Private/Language/locallang_csh.xml";s:4:"b1c3";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"8518";s:52:"Resources/Private/Templates/Fluid/Audience/List.html";s:4:"e1f8";s:54:"Resources/Private/Templates/Fluid/Audience/Single.html";s:4:"1c15";s:50:"Resources/Private/Templates/Fluid/Domain/List.html";s:4:"657d";s:52:"Resources/Private/Templates/Fluid/Domain/Single.html";s:4:"de41";s:51:"Resources/Private/Templates/Fluid/Service/List.html";s:4:"a477";s:53:"Resources/Private/Templates/Fluid/Service/Single.html";s:4:"2a7d";s:49:"Resources/Private/Templates/Fluid/Topic/List.html";s:4:"70c0";s:51:"Resources/Private/Templates/Fluid/Topic/Single.html";s:4:"5d1c";s:48:"Resources/Private/Templates/Fluid/View/List.html";s:4:"ebbf";s:50:"Resources/Private/Templates/Fluid/View/Single.html";s:4:"c940";s:50:"Resources/Private/Templates/Standard/Audience.html";s:4:"6087";s:48:"Resources/Private/Templates/Standard/Domain.html";s:4:"5790";s:49:"Resources/Private/Templates/Standard/Service.html";s:4:"a003";s:47:"Resources/Private/Templates/Standard/Topic.html";s:4:"61c0";s:46:"Resources/Private/Templates/Standard/View.html";s:4:"e555";s:40:"Resources/Public/Icons/pi1_ce_wizard.png";s:4:"5d61";s:16:"doc/doxygen.conf";s:4:"38a2";s:14:"doc/manual.pdf";s:4:"c35c";s:14:"doc/manual.sxw";s:4:"550f";}',
);

?>