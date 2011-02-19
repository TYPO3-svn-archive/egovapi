<?php

########################################################################
# Extension Manager/Repository config file for ext "egovapi".
#
# Auto generated 18-02-2011 16:41
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'eGov CH API',
	'description' => 'Official implementation of the swiss eGov Remote API that allows to access the Ref application from SECO which hosts and provides administrative services.',
	'category' => 'plugin',
	'author' => 'Xavier Perseguers',
	'author_company' => 'Causal Sàrl / SECO',
	'author_email' => 'xavier@causal.ch',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'suggests' => 'fluid',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '0.7.1',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'fluid' => '1.3.0-0.0.0',
		),
	),
	'_md5_values_when_last_written' => 'a:87:{s:9:"ChangeLog";s:4:"3249";s:16:"ext_autoload.php";s:4:"020f";s:21:"ext_conf_template.txt";s:4:"9afc";s:12:"ext_icon.gif";s:4:"fc04";s:17:"ext_localconf.php";s:4:"63d6";s:14:"ext_tables.php";s:4:"ae97";s:14:"ext_tables.sql";s:4:"c30a";s:43:"Classes/Controller/Pi1/AbstractRenderer.php";s:4:"25a6";s:40:"Classes/Controller/Pi1/FluidRenderer.php";s:4:"c158";s:43:"Classes/Controller/Pi1/TemplateRenderer.php";s:4:"7419";s:47:"Classes/Controller/Pi1/class.tx_egovapi_pi1.php";s:4:"1c9c";s:55:"Classes/Controller/Pi1/class.tx_egovapi_pi1_wizicon.php";s:4:"5960";s:21:"Classes/Dao/Cache.php";s:4:"13ee";s:19:"Classes/Dao/Dao.php";s:4:"2ed9";s:26:"Classes/Dao/WebService.php";s:4:"b8b2";s:39:"Classes/Domain/Model/AbstractEntity.php";s:4:"a56e";s:33:"Classes/Domain/Model/Audience.php";s:4:"b252";s:34:"Classes/Domain/Model/Community.php";s:4:"6223";s:31:"Classes/Domain/Model/Domain.php";s:4:"39bf";s:32:"Classes/Domain/Model/Service.php";s:4:"1b03";s:30:"Classes/Domain/Model/Topic.php";s:4:"0d99";s:29:"Classes/Domain/Model/View.php";s:4:"8343";s:39:"Classes/Domain/Model/Block/Approval.php";s:4:"17b5";s:38:"Classes/Domain/Model/Block/Contact.php";s:4:"1f6e";s:41:"Classes/Domain/Model/Block/Descriptor.php";s:4:"e05e";s:39:"Classes/Domain/Model/Block/Document.php";s:4:"702d";s:45:"Classes/Domain/Model/Block/DocumentsOther.php";s:4:"83ac";s:48:"Classes/Domain/Model/Block/DocumentsRequired.php";s:4:"9f7d";s:34:"Classes/Domain/Model/Block/Fee.php";s:4:"6e5c";s:35:"Classes/Domain/Model/Block/Form.php";s:4:"4883";s:36:"Classes/Domain/Model/Block/Forms.php";s:4:"e56e";s:49:"Classes/Domain/Model/Block/GeneralInformation.php";s:4:"c054";s:46:"Classes/Domain/Model/Block/LegalRegulation.php";s:4:"e07d";s:50:"Classes/Domain/Model/Block/LegalRegulationItem.php";s:4:"3a58";s:35:"Classes/Domain/Model/Block/News.php";s:4:"65a2";s:43:"Classes/Domain/Model/Block/Prerequisite.php";s:4:"78a0";s:44:"Classes/Domain/Model/Block/Prerequisites.php";s:4:"2f9f";s:40:"Classes/Domain/Model/Block/Procedure.php";s:4:"a886";s:44:"Classes/Domain/Model/Block/ProcedureItem.php";s:4:"bedc";s:38:"Classes/Domain/Model/Block/Remarks.php";s:4:"1cfb";s:46:"Classes/Domain/Model/Block/ServiceProvided.php";s:4:"c8e6";s:40:"Classes/Domain/Model/Block/Subdomain.php";s:4:"4b05";s:41:"Classes/Domain/Model/Block/Subdomains.php";s:4:"c075";s:39:"Classes/Domain/Model/Block/Subtopic.php";s:4:"d34c";s:40:"Classes/Domain/Model/Block/Subtopics.php";s:4:"7248";s:38:"Classes/Domain/Model/Block/Synonym.php";s:4:"148b";s:48:"Classes/Domain/Repository/AbstractRepository.php";s:4:"7feb";s:48:"Classes/Domain/Repository/AudienceRepository.php";s:4:"0e9f";s:49:"Classes/Domain/Repository/CommunityRepository.php";s:4:"ce43";s:46:"Classes/Domain/Repository/DomainRepository.php";s:4:"cb4b";s:37:"Classes/Domain/Repository/Factory.php";s:4:"b34b";s:47:"Classes/Domain/Repository/ServiceRepository.php";s:4:"1cac";s:45:"Classes/Domain/Repository/TopicRepository.php";s:4:"48f4";s:44:"Classes/Domain/Repository/ViewRepository.php";s:4:"6122";s:29:"Classes/Helpers/Constants.php";s:4:"9992";s:28:"Classes/Helpers/FlexForm.php";s:4:"54b7";s:30:"Classes/Helpers/TypoScript.php";s:4:"231c";s:31:"Configuration/FlexForms/Pi1.xml";s:4:"bc24";s:47:"Configuration/TypoScript/Settings/constants.txt";s:4:"5183";s:43:"Configuration/TypoScript/Settings/setup.txt";s:4:"5ef4";s:41:"Configuration/TypoScript/Styles/setup.txt";s:4:"9b32";s:36:"Interfaces/Template/AudienceHook.php";s:4:"2dca";s:34:"Interfaces/Template/DomainHook.php";s:4:"95e5";s:34:"Interfaces/Template/RenderHook.php";s:4:"123f";s:35:"Interfaces/Template/ServiceHook.php";s:4:"9d1d";s:33:"Interfaces/Template/TopicHook.php";s:4:"41c0";s:32:"Interfaces/Template/ViewHook.php";s:4:"b3b2";s:40:"Resources/Private/Language/locallang.xml";s:4:"27b2";s:44:"Resources/Private/Language/locallang_csh.xml";s:4:"e7b1";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"323d";s:52:"Resources/Private/Templates/Fluid/Audience/List.html";s:4:"b66c";s:54:"Resources/Private/Templates/Fluid/Audience/Single.html";s:4:"1c15";s:50:"Resources/Private/Templates/Fluid/Domain/List.html";s:4:"2dc8";s:52:"Resources/Private/Templates/Fluid/Domain/Single.html";s:4:"ce33";s:51:"Resources/Private/Templates/Fluid/Service/List.html";s:4:"ac96";s:53:"Resources/Private/Templates/Fluid/Service/Single.html";s:4:"b02c";s:49:"Resources/Private/Templates/Fluid/Topic/List.html";s:4:"60fb";s:51:"Resources/Private/Templates/Fluid/Topic/Single.html";s:4:"b81f";s:48:"Resources/Private/Templates/Fluid/View/List.html";s:4:"2436";s:50:"Resources/Private/Templates/Fluid/View/Single.html";s:4:"c940";s:50:"Resources/Private/Templates/Standard/Audience.html";s:4:"ae55";s:48:"Resources/Private/Templates/Standard/Domain.html";s:4:"5dc2";s:49:"Resources/Private/Templates/Standard/Service.html";s:4:"b662";s:47:"Resources/Private/Templates/Standard/Topic.html";s:4:"f3a8";s:46:"Resources/Private/Templates/Standard/View.html";s:4:"946a";s:40:"Resources/Public/Icons/pi1_ce_wizard.png";s:4:"5d61";s:14:"doc/manual.sxw";s:4:"0f56";}',
);

?>