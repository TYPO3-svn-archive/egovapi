<?php

########################################################################
# Extension Manager/Repository config file for ext "egovapi".
#
# Auto generated 10-11-2011 19:06
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
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.4-dev',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.4.0-4.6.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'fluid' => '1.3.0-0.0.0',
		),
	),
	'_md5_values_when_last_written' => 'a:122:{s:9:"ChangeLog";s:4:"1a6c";s:13:"RELEASE_NOTES";s:4:"a4c0";s:16:"ext_autoload.php";s:4:"ae73";s:21:"ext_conf_template.txt";s:4:"f944";s:12:"ext_icon.gif";s:4:"fc04";s:17:"ext_localconf.php";s:4:"35e3";s:14:"ext_tables.php";s:4:"9b47";s:14:"ext_tables.sql";s:4:"edf1";s:43:"Classes/Cache/Frontend/VariableFrontend.php";s:4:"0ae0";s:46:"Classes/Controller/class.tx_egovapi_pibase.php";s:4:"5cd1";s:43:"Classes/Controller/Pi1/AbstractRenderer.php";s:4:"a7a7";s:40:"Classes/Controller/Pi1/FluidRenderer.php";s:4:"d8ac";s:43:"Classes/Controller/Pi1/TemplateRenderer.php";s:4:"d62b";s:40:"Classes/Controller/Pi1/VcardRenderer.php";s:4:"520f";s:47:"Classes/Controller/Pi1/class.tx_egovapi_pi1.php";s:4:"849a";s:55:"Classes/Controller/Pi1/class.tx_egovapi_pi1_wizicon.php";s:4:"c271";s:31:"Classes/Controller/Pi2/Ajax.php";s:4:"1caf";s:47:"Classes/Controller/Pi2/class.tx_egovapi_pi2.php";s:4:"ab64";s:55:"Classes/Controller/Pi2/class.tx_egovapi_pi2_wizicon.php";s:4:"33ee";s:19:"Classes/Dao/Dao.php";s:4:"44f6";s:26:"Classes/Dao/WebService.php";s:4:"c39f";s:39:"Classes/Domain/Model/AbstractEntity.php";s:4:"1303";s:33:"Classes/Domain/Model/Audience.php";s:4:"b01c";s:34:"Classes/Domain/Model/Community.php";s:4:"0fee";s:34:"Classes/Domain/Model/Constants.php";s:4:"8335";s:31:"Classes/Domain/Model/Domain.php";s:4:"9a99";s:37:"Classes/Domain/Model/Organization.php";s:4:"7544";s:32:"Classes/Domain/Model/Service.php";s:4:"8a26";s:30:"Classes/Domain/Model/Topic.php";s:4:"99ab";s:32:"Classes/Domain/Model/Version.php";s:4:"0d45";s:29:"Classes/Domain/Model/View.php";s:4:"045e";s:39:"Classes/Domain/Model/Block/Approval.php";s:4:"6532";s:38:"Classes/Domain/Model/Block/Contact.php";s:4:"52b9";s:41:"Classes/Domain/Model/Block/Descriptor.php";s:4:"ea56";s:39:"Classes/Domain/Model/Block/Document.php";s:4:"2b4a";s:45:"Classes/Domain/Model/Block/DocumentsOther.php";s:4:"e8dc";s:48:"Classes/Domain/Model/Block/DocumentsRequired.php";s:4:"deca";s:34:"Classes/Domain/Model/Block/Fee.php";s:4:"4fed";s:35:"Classes/Domain/Model/Block/Form.php";s:4:"ab00";s:36:"Classes/Domain/Model/Block/Forms.php";s:4:"095d";s:49:"Classes/Domain/Model/Block/GeneralInformation.php";s:4:"0140";s:46:"Classes/Domain/Model/Block/LegalRegulation.php";s:4:"e417";s:50:"Classes/Domain/Model/Block/LegalRegulationItem.php";s:4:"10e9";s:35:"Classes/Domain/Model/Block/News.php";s:4:"f1d2";s:43:"Classes/Domain/Model/Block/Prerequisite.php";s:4:"85c7";s:44:"Classes/Domain/Model/Block/Prerequisites.php";s:4:"3a86";s:38:"Classes/Domain/Model/Block/Pricing.php";s:4:"7e8a";s:40:"Classes/Domain/Model/Block/Procedure.php";s:4:"916a";s:44:"Classes/Domain/Model/Block/ProcedureItem.php";s:4:"eb5a";s:38:"Classes/Domain/Model/Block/Remarks.php";s:4:"5168";s:46:"Classes/Domain/Model/Block/ServiceProvided.php";s:4:"3c5f";s:40:"Classes/Domain/Model/Block/Subdomain.php";s:4:"efab";s:41:"Classes/Domain/Model/Block/Subdomains.php";s:4:"30d8";s:39:"Classes/Domain/Model/Block/Subtopic.php";s:4:"0190";s:40:"Classes/Domain/Model/Block/Subtopics.php";s:4:"6ef4";s:38:"Classes/Domain/Model/Block/Synonym.php";s:4:"0c58";s:48:"Classes/Domain/Repository/AbstractRepository.php";s:4:"8166";s:48:"Classes/Domain/Repository/AudienceRepository.php";s:4:"72d6";s:49:"Classes/Domain/Repository/CommunityRepository.php";s:4:"ef10";s:46:"Classes/Domain/Repository/DomainRepository.php";s:4:"ccd5";s:37:"Classes/Domain/Repository/Factory.php";s:4:"2ba0";s:52:"Classes/Domain/Repository/OrganizationRepository.php";s:4:"a9d6";s:47:"Classes/Domain/Repository/ServiceRepository.php";s:4:"a137";s:45:"Classes/Domain/Repository/TopicRepository.php";s:4:"312a";s:47:"Classes/Domain/Repository/VersionRepository.php";s:4:"54cf";s:44:"Classes/Domain/Repository/ViewRepository.php";s:4:"cef8";s:29:"Classes/Helpers/Constants.php";s:4:"ead7";s:28:"Classes/Helpers/FlexForm.php";s:4:"2b6e";s:27:"Classes/Helpers/Objects.php";s:4:"d3e9";s:30:"Classes/Helpers/TypoScript.php";s:4:"227a";s:25:"Classes/Hooks/RealUrl.php";s:4:"c46d";s:40:"Classes/Service/LatestChangesCleanup.php";s:4:"a912";s:63:"Classes/Service/LatestChangesCleanupAdditionalFieldProvider.php";s:4:"f3dc";s:44:"Classes/Service/LatestChangesCleanupTask.php";s:4:"2f1a";s:25:"Classes/Utility/Cache.php";s:4:"c70b";s:29:"Classes/Utility/Constants.php";s:4:"7d0d";s:28:"Classes/Utility/FlexForm.php";s:4:"7f9d";s:27:"Classes/Utility/Objects.php";s:4:"858c";s:30:"Classes/Utility/TypoScript.php";s:4:"b2d7";s:31:"Configuration/FlexForms/Pi1.xml";s:4:"bbfb";s:47:"Configuration/TypoScript/Selector/constants.txt";s:4:"3f9c";s:43:"Configuration/TypoScript/Selector/setup.txt";s:4:"5082";s:47:"Configuration/TypoScript/Settings/constants.txt";s:4:"556d";s:43:"Configuration/TypoScript/Settings/setup.txt";s:4:"494d";s:41:"Configuration/TypoScript/Styles/setup.txt";s:4:"4301";s:23:"Interfaces/AjaxHook.php";s:4:"bca5";s:36:"Interfaces/Template/AudienceHook.php";s:4:"9cb7";s:34:"Interfaces/Template/DomainHook.php";s:4:"d82c";s:34:"Interfaces/Template/RenderHook.php";s:4:"20c5";s:35:"Interfaces/Template/ServiceHook.php";s:4:"f4eb";s:33:"Interfaces/Template/TopicHook.php";s:4:"96ca";s:32:"Interfaces/Template/ViewHook.php";s:4:"62c9";s:38:"Resources/Private/Data/communities.csv";s:4:"9b98";s:40:"Resources/Private/Data/organizations.csv";s:4:"c86a";s:40:"Resources/Private/Language/locallang.xml";s:4:"f4b5";s:44:"Resources/Private/Language/locallang_csh.xml";s:4:"0b6a";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"80e0";s:44:"Resources/Private/Language/locallang_mod.xml";s:4:"2326";s:37:"Resources/Private/Templates/Vcard.txt";s:4:"bd7d";s:52:"Resources/Private/Templates/Fluid/Audience/List.html";s:4:"e1f8";s:54:"Resources/Private/Templates/Fluid/Audience/Single.html";s:4:"1c15";s:50:"Resources/Private/Templates/Fluid/Domain/List.html";s:4:"657d";s:52:"Resources/Private/Templates/Fluid/Domain/Single.html";s:4:"de41";s:51:"Resources/Private/Templates/Fluid/Service/List.html";s:4:"a477";s:56:"Resources/Private/Templates/Fluid/Service/Single.10.html";s:4:"d530";s:53:"Resources/Private/Templates/Fluid/Service/Single.html";s:4:"f237";s:49:"Resources/Private/Templates/Fluid/Topic/List.html";s:4:"70c0";s:51:"Resources/Private/Templates/Fluid/Topic/Single.html";s:4:"5d1c";s:48:"Resources/Private/Templates/Fluid/View/List.html";s:4:"ebbf";s:50:"Resources/Private/Templates/Fluid/View/Single.html";s:4:"c940";s:41:"Resources/Private/Templates/Pi2/form.html";s:4:"3291";s:50:"Resources/Private/Templates/Standard/Audience.html";s:4:"6087";s:48:"Resources/Private/Templates/Standard/Domain.html";s:4:"5790";s:49:"Resources/Private/Templates/Standard/Service.html";s:4:"becc";s:47:"Resources/Private/Templates/Standard/Topic.html";s:4:"61c0";s:46:"Resources/Private/Templates/Standard/View.html";s:4:"e555";s:40:"Resources/Public/Icons/pi1_ce_wizard.png";s:4:"5d61";s:40:"Resources/Public/Icons/pi2_ce_wizard.png";s:4:"4c74";s:39:"Resources/Public/JavaScript/selector.js";s:4:"0896";s:16:"doc/doxygen.conf";s:4:"38a2";s:14:"doc/manual.pdf";s:4:"97ad";s:14:"doc/manual.sxw";s:4:"b968";}',
);

?>