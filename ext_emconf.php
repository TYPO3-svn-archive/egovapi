<?php

########################################################################
# Extension Manager/Repository config file for ext "egovapi".
#
# Auto generated 06-05-2012 18:18
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'eGov API (Swiss Federal Administration)',
	'description' => 'Official implementation of the swiss eGov Remote API (eGovernment) that allows to access the Reference eGov CH application from SECO which hosts and provides administrative services (cyberadministration).',
	'category' => 'plugin',
	'author' => 'Xavier Perseguers (sponsored by SECO)',
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
	'version' => '2.1.0',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-5.3.99',
			'typo3' => '4.5.0-4.7.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'fluid' => '1.3.0-0.0.0',
		),
	),
	'_md5_values_when_last_written' => 'a:341:{s:9:"ChangeLog";s:4:"621a";s:13:"RELEASE_NOTES";s:4:"a5bd";s:16:"ext_autoload.php";s:4:"ae73";s:21:"ext_conf_template.txt";s:4:"3c5f";s:12:"ext_icon.gif";s:4:"fc04";s:17:"ext_localconf.php";s:4:"5410";s:14:"ext_tables.php";s:4:"ff48";s:14:"ext_tables.sql";s:4:"d0a9";s:43:"Classes/Cache/Frontend/VariableFrontend.php";s:4:"eb44";s:46:"Classes/Controller/class.tx_egovapi_pibase.php";s:4:"42f3";s:43:"Classes/Controller/Pi1/AbstractRenderer.php";s:4:"78f6";s:40:"Classes/Controller/Pi1/FluidRenderer.php";s:4:"d2f5";s:43:"Classes/Controller/Pi1/TemplateRenderer.php";s:4:"ff79";s:40:"Classes/Controller/Pi1/VcardRenderer.php";s:4:"3b94";s:47:"Classes/Controller/Pi1/class.tx_egovapi_pi1.php";s:4:"c090";s:55:"Classes/Controller/Pi1/class.tx_egovapi_pi1_wizicon.php";s:4:"5439";s:31:"Classes/Controller/Pi2/Ajax.php";s:4:"aa64";s:47:"Classes/Controller/Pi2/class.tx_egovapi_pi2.php";s:4:"567d";s:55:"Classes/Controller/Pi2/class.tx_egovapi_pi2_wizicon.php";s:4:"1fa8";s:30:"Classes/Controller/Pi3/Rdf.php";s:4:"8301";s:47:"Classes/Controller/Pi3/class.tx_egovapi_pi3.php";s:4:"a475";s:55:"Classes/Controller/Pi3/class.tx_egovapi_pi3_wizicon.php";s:4:"6b18";s:19:"Classes/Dao/Dao.php";s:4:"f1dd";s:26:"Classes/Dao/WebService.php";s:4:"05dc";s:39:"Classes/Domain/Model/AbstractEntity.php";s:4:"2bbe";s:33:"Classes/Domain/Model/Audience.php";s:4:"b01c";s:34:"Classes/Domain/Model/Community.php";s:4:"1bad";s:34:"Classes/Domain/Model/Constants.php";s:4:"eaf7";s:31:"Classes/Domain/Model/Domain.php";s:4:"9a99";s:37:"Classes/Domain/Model/Organization.php";s:4:"4274";s:32:"Classes/Domain/Model/Service.php";s:4:"b398";s:30:"Classes/Domain/Model/Topic.php";s:4:"99ab";s:32:"Classes/Domain/Model/Version.php";s:4:"52ba";s:29:"Classes/Domain/Model/View.php";s:4:"045e";s:39:"Classes/Domain/Model/Block/Approval.php";s:4:"9005";s:38:"Classes/Domain/Model/Block/Contact.php";s:4:"3b13";s:41:"Classes/Domain/Model/Block/Descriptor.php";s:4:"0bce";s:39:"Classes/Domain/Model/Block/Document.php";s:4:"ecab";s:45:"Classes/Domain/Model/Block/DocumentsOther.php";s:4:"c5e5";s:48:"Classes/Domain/Model/Block/DocumentsRequired.php";s:4:"3394";s:34:"Classes/Domain/Model/Block/Fee.php";s:4:"d4f2";s:35:"Classes/Domain/Model/Block/Form.php";s:4:"f120";s:36:"Classes/Domain/Model/Block/Forms.php";s:4:"761b";s:49:"Classes/Domain/Model/Block/GeneralInformation.php";s:4:"56ea";s:46:"Classes/Domain/Model/Block/LegalRegulation.php";s:4:"dd5a";s:50:"Classes/Domain/Model/Block/LegalRegulationItem.php";s:4:"6b09";s:35:"Classes/Domain/Model/Block/News.php";s:4:"41c9";s:43:"Classes/Domain/Model/Block/Prerequisite.php";s:4:"8f7e";s:44:"Classes/Domain/Model/Block/Prerequisites.php";s:4:"da7f";s:38:"Classes/Domain/Model/Block/Pricing.php";s:4:"eca6";s:40:"Classes/Domain/Model/Block/Procedure.php";s:4:"1b30";s:44:"Classes/Domain/Model/Block/ProcedureItem.php";s:4:"e63f";s:38:"Classes/Domain/Model/Block/Remarks.php";s:4:"7f8a";s:46:"Classes/Domain/Model/Block/ServiceProvided.php";s:4:"7352";s:40:"Classes/Domain/Model/Block/Subdomain.php";s:4:"6380";s:41:"Classes/Domain/Model/Block/Subdomains.php";s:4:"6248";s:39:"Classes/Domain/Model/Block/Subtopic.php";s:4:"90fb";s:40:"Classes/Domain/Model/Block/Subtopics.php";s:4:"ffaf";s:38:"Classes/Domain/Model/Block/Synonym.php";s:4:"bfe9";s:48:"Classes/Domain/Repository/AbstractRepository.php";s:4:"8166";s:48:"Classes/Domain/Repository/AudienceRepository.php";s:4:"72d6";s:49:"Classes/Domain/Repository/CommunityRepository.php";s:4:"ac57";s:46:"Classes/Domain/Repository/DomainRepository.php";s:4:"111f";s:37:"Classes/Domain/Repository/Factory.php";s:4:"2ba0";s:52:"Classes/Domain/Repository/OrganizationRepository.php";s:4:"d1df";s:47:"Classes/Domain/Repository/ServiceRepository.php";s:4:"9024";s:45:"Classes/Domain/Repository/TopicRepository.php";s:4:"604e";s:47:"Classes/Domain/Repository/VersionRepository.php";s:4:"c438";s:44:"Classes/Domain/Repository/ViewRepository.php";s:4:"9f9f";s:29:"Classes/Helpers/Constants.php";s:4:"0208";s:28:"Classes/Helpers/FlexForm.php";s:4:"2568";s:27:"Classes/Helpers/Objects.php";s:4:"4df6";s:30:"Classes/Helpers/TypoScript.php";s:4:"c9a8";s:25:"Classes/Hooks/RealUrl.php";s:4:"8a74";s:40:"Classes/Service/LatestChangesCleanup.php";s:4:"85fc";s:63:"Classes/Service/LatestChangesCleanupAdditionalFieldProvider.php";s:4:"5b68";s:44:"Classes/Service/LatestChangesCleanupTask.php";s:4:"cb32";s:25:"Classes/Utility/Cache.php";s:4:"ccc9";s:29:"Classes/Utility/Constants.php";s:4:"3d67";s:28:"Classes/Utility/FlexForm.php";s:4:"2634";s:27:"Classes/Utility/Objects.php";s:4:"f257";s:30:"Classes/Utility/TypoScript.php";s:4:"f456";s:31:"Configuration/FlexForms/Pi1.xml";s:4:"bbfb";s:51:"Configuration/TypoScript/RdfGenerator/constants.txt";s:4:"36f5";s:47:"Configuration/TypoScript/RdfGenerator/setup.txt";s:4:"d7cc";s:47:"Configuration/TypoScript/Selector/constants.txt";s:4:"8f1b";s:43:"Configuration/TypoScript/Selector/setup.txt";s:4:"ff24";s:47:"Configuration/TypoScript/Settings/constants.txt";s:4:"556d";s:43:"Configuration/TypoScript/Settings/setup.txt";s:4:"6b67";s:41:"Configuration/TypoScript/Styles/setup.txt";s:4:"073d";s:23:"Interfaces/AjaxHook.php";s:4:"c0fe";s:29:"Interfaces/WebServiceHook.php";s:4:"d717";s:36:"Interfaces/Template/AudienceHook.php";s:4:"aae9";s:34:"Interfaces/Template/DomainHook.php";s:4:"5aa8";s:34:"Interfaces/Template/RenderHook.php";s:4:"d85e";s:35:"Interfaces/Template/ServiceHook.php";s:4:"cfbc";s:33:"Interfaces/Template/TopicHook.php";s:4:"36ef";s:32:"Interfaces/Template/ViewHook.php";s:4:"9c45";s:38:"Resources/Private/Data/communities.csv";s:4:"3d0d";s:40:"Resources/Private/Data/organizations.csv";s:4:"f808";s:40:"Resources/Private/Language/locallang.xlf";s:4:"ca6f";s:40:"Resources/Private/Language/locallang.xml";s:4:"6f4f";s:44:"Resources/Private/Language/locallang_csh.xlf";s:4:"d991";s:44:"Resources/Private/Language/locallang_csh.xml";s:4:"316f";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"23ec";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"e308";s:44:"Resources/Private/Language/locallang_mod.xlf";s:4:"4cc0";s:44:"Resources/Private/Language/locallang_mod.xml";s:4:"f766";s:37:"Resources/Private/Templates/Vcard.txt";s:4:"bd7d";s:52:"Resources/Private/Templates/Fluid/Audience/List.html";s:4:"e1f8";s:54:"Resources/Private/Templates/Fluid/Audience/Single.html";s:4:"1c15";s:50:"Resources/Private/Templates/Fluid/Domain/List.html";s:4:"657d";s:52:"Resources/Private/Templates/Fluid/Domain/Single.html";s:4:"de41";s:51:"Resources/Private/Templates/Fluid/Service/List.html";s:4:"2dde";s:56:"Resources/Private/Templates/Fluid/Service/Single.10.html";s:4:"d530";s:53:"Resources/Private/Templates/Fluid/Service/Single.html";s:4:"df7a";s:49:"Resources/Private/Templates/Fluid/Topic/List.html";s:4:"70c0";s:51:"Resources/Private/Templates/Fluid/Topic/Single.html";s:4:"5d1c";s:48:"Resources/Private/Templates/Fluid/View/List.html";s:4:"ebbf";s:50:"Resources/Private/Templates/Fluid/View/Single.html";s:4:"c940";s:41:"Resources/Private/Templates/Pi2/form.html";s:4:"9683";s:56:"Resources/Private/Templates/Pi3/ProvidedServices.n3.tmpl";s:4:"3c81";s:41:"Resources/Private/Templates/Pi3/form.html";s:4:"213b";s:50:"Resources/Private/Templates/Standard/Audience.html";s:4:"6087";s:48:"Resources/Private/Templates/Standard/Domain.html";s:4:"5790";s:49:"Resources/Private/Templates/Standard/Service.html";s:4:"6f07";s:47:"Resources/Private/Templates/Standard/Topic.html";s:4:"61c0";s:46:"Resources/Private/Templates/Standard/View.html";s:4:"e555";s:40:"Resources/Public/Icons/pi1_ce_wizard.png";s:4:"5d61";s:40:"Resources/Public/Icons/pi2_ce_wizard.png";s:4:"4c74";s:40:"Resources/Public/Icons/pi3_ce_wizard.png";s:4:"4c74";s:45:"Resources/Public/Images/ajax-loader-large.gif";s:4:"7ca7";s:45:"Resources/Public/Images/ajax-loader-small.gif";s:4:"2d5a";s:44:"Resources/Public/JavaScript/rdf-generator.js";s:4:"f59f";s:39:"Resources/Public/JavaScript/selector.js";s:4:"6f5b";s:16:"doc/doxygen.conf";s:4:"38a2";s:14:"doc/manual.pdf";s:4:"3232";s:14:"doc/manual.sxw";s:4:"e24a";s:17:"doc/reST/Makefile";s:4:"f5c4";s:42:"doc/reST/build/doctrees/environment.pickle";s:4:"2409";s:37:"doc/reST/build/doctrees/index.doctree";s:4:"58e7";s:66:"doc/reST/build/doctrees/Administration/InstallingExtension.doctree";s:4:"0652";s:50:"doc/reST/build/doctrees/Administration/RDF.doctree";s:4:"fc0c";s:59:"doc/reST/build/doctrees/Administration/SelectorForm.doctree";s:4:"e2d5";s:57:"doc/reST/build/doctrees/Administration/TypoScript.doctree";s:4:"7522";s:49:"doc/reST/build/doctrees/Configuration/Pi1.doctree";s:4:"3141";s:49:"doc/reST/build/doctrees/Configuration/Pi2.doctree";s:4:"8d4e";s:51:"doc/reST/build/doctrees/DeveloperManual/API.doctree";s:4:"fa21";s:53:"doc/reST/build/doctrees/DeveloperManual/Hooks.doctree";s:4:"f6f8";s:58:"doc/reST/build/doctrees/DeveloperManual/WebService.doctree";s:4:"d25c";s:56:"doc/reST/build/doctrees/Introduction/Screenshots.doctree";s:4:"0fbc";s:56:"doc/reST/build/doctrees/Introduction/Sponsorship.doctree";s:4:"4cc3";s:57:"doc/reST/build/doctrees/Introduction/WhatDoesItDo.doctree";s:4:"6e7a";s:48:"doc/reST/build/doctrees/Tutorial/UseCase.doctree";s:4:"3d2c";s:54:"doc/reST/build/doctrees/UserManual/DomainModel.doctree";s:4:"be13";s:58:"doc/reST/build/doctrees/UserManual/MarkersSubparts.doctree";s:4:"6c38";s:56:"doc/reST/build/doctrees/UserManual/PluginOptions.doctree";s:4:"ffce";s:33:"doc/reST/build/html/genindex.html";s:4:"4fa0";s:30:"doc/reST/build/html/index.html";s:4:"e625";s:31:"doc/reST/build/html/objects.inv";s:4:"86b2";s:31:"doc/reST/build/html/search.html";s:4:"2806";s:34:"doc/reST/build/html/searchindex.js";s:4:"7e4d";s:59:"doc/reST/build/html/Administration/InstallingExtension.html";s:4:"8abf";s:43:"doc/reST/build/html/Administration/RDF.html";s:4:"83a9";s:52:"doc/reST/build/html/Administration/SelectorForm.html";s:4:"e351";s:50:"doc/reST/build/html/Administration/TypoScript.html";s:4:"0074";s:42:"doc/reST/build/html/Configuration/Pi1.html";s:4:"eeff";s:42:"doc/reST/build/html/Configuration/Pi2.html";s:4:"4bae";s:44:"doc/reST/build/html/DeveloperManual/API.html";s:4:"d28c";s:46:"doc/reST/build/html/DeveloperManual/Hooks.html";s:4:"78f6";s:51:"doc/reST/build/html/DeveloperManual/WebService.html";s:4:"41ae";s:49:"doc/reST/build/html/Introduction/Screenshots.html";s:4:"3a54";s:49:"doc/reST/build/html/Introduction/Sponsorship.html";s:4:"60fc";s:50:"doc/reST/build/html/Introduction/WhatDoesItDo.html";s:4:"5ae3";s:41:"doc/reST/build/html/Tutorial/UseCase.html";s:4:"299d";s:47:"doc/reST/build/html/UserManual/DomainModel.html";s:4:"1d2e";s:51:"doc/reST/build/html/UserManual/MarkersSubparts.html";s:4:"db05";s:49:"doc/reST/build/html/UserManual/PluginOptions.html";s:4:"0e57";s:48:"doc/reST/build/html/_images/AdvancedSettings.png";s:4:"33a8";s:54:"doc/reST/build/html/_images/AudiencePersonnePrivee.png";s:4:"a5a0";s:40:"doc/reST/build/html/_images/CEWizard.png";s:4:"f2be";s:46:"doc/reST/build/html/_images/ConstantEditor.png";s:4:"7a7f";s:38:"doc/reST/build/html/_images/EditCE.png";s:4:"1e5f";s:41:"doc/reST/build/html/_images/IncludeTS.png";s:4:"82fd";s:42:"doc/reST/build/html/_images/InfoModify.png";s:4:"70d6";s:37:"doc/reST/build/html/_images/NewCE.png";s:4:"7921";s:38:"doc/reST/build/html/_images/Plugin.png";s:4:"4041";s:49:"doc/reST/build/html/_images/advanced_settings.png";s:4:"33a8";s:40:"doc/reST/build/html/_images/audience.png";s:4:"faeb";s:56:"doc/reST/build/html/_images/audience_personne_privee.png";s:4:"a5a0";s:41:"doc/reST/build/html/_images/ce_wizard.png";s:4:"f2be";s:47:"doc/reST/build/html/_images/constant_editor.png";s:4:"7a7f";s:38:"doc/reST/build/html/_images/domain.png";s:4:"97a1";s:39:"doc/reST/build/html/_images/edit_ce.png";s:4:"1e5f";s:39:"doc/reST/build/html/_images/general.png";s:4:"9215";s:41:"doc/reST/build/html/_images/hierarchy.png";s:4:"d204";s:42:"doc/reST/build/html/_images/include_ts.png";s:4:"82fd";s:43:"doc/reST/build/html/_images/info_modify.png";s:4:"70d6";s:38:"doc/reST/build/html/_images/new_ce.png";s:4:"7921";s:40:"doc/reST/build/html/_images/overview.jpg";s:4:"5cf2";s:38:"doc/reST/build/html/_images/result.png";s:4:"343e";s:52:"doc/reST/build/html/_images/selector_form_plugin.png";s:4:"4041";s:39:"doc/reST/build/html/_images/service.png";s:4:"c073";s:37:"doc/reST/build/html/_images/topic.png";s:4:"4778";s:39:"doc/reST/build/html/_images/version.png";s:4:"2c57";s:36:"doc/reST/build/html/_images/view.png";s:4:"f01c";s:38:"doc/reST/build/html/_sources/index.txt";s:4:"457e";s:67:"doc/reST/build/html/_sources/Administration/InstallingExtension.txt";s:4:"20c9";s:51:"doc/reST/build/html/_sources/Administration/RDF.txt";s:4:"ed36";s:60:"doc/reST/build/html/_sources/Administration/SelectorForm.txt";s:4:"a1e0";s:58:"doc/reST/build/html/_sources/Administration/TypoScript.txt";s:4:"2849";s:50:"doc/reST/build/html/_sources/Configuration/Pi1.txt";s:4:"529e";s:50:"doc/reST/build/html/_sources/Configuration/Pi2.txt";s:4:"f6bf";s:52:"doc/reST/build/html/_sources/DeveloperManual/API.txt";s:4:"6f15";s:54:"doc/reST/build/html/_sources/DeveloperManual/Hooks.txt";s:4:"59a4";s:59:"doc/reST/build/html/_sources/DeveloperManual/WebService.txt";s:4:"e92b";s:57:"doc/reST/build/html/_sources/Introduction/Screenshots.txt";s:4:"c491";s:57:"doc/reST/build/html/_sources/Introduction/Sponsorship.txt";s:4:"a6e0";s:58:"doc/reST/build/html/_sources/Introduction/WhatDoesItDo.txt";s:4:"e443";s:49:"doc/reST/build/html/_sources/Tutorial/UseCase.txt";s:4:"5710";s:55:"doc/reST/build/html/_sources/UserManual/DomainModel.txt";s:4:"c0a0";s:59:"doc/reST/build/html/_sources/UserManual/MarkersSubparts.txt";s:4:"671f";s:57:"doc/reST/build/html/_sources/UserManual/PluginOptions.txt";s:4:"d388";s:43:"doc/reST/build/html/_static/ajax-loader.gif";s:4:"ae66";s:37:"doc/reST/build/html/_static/basic.css";s:4:"e750";s:46:"doc/reST/build/html/_static/comment-bright.png";s:4:"0c85";s:45:"doc/reST/build/html/_static/comment-close.png";s:4:"2635";s:39:"doc/reST/build/html/_static/comment.png";s:4:"882e";s:39:"doc/reST/build/html/_static/default.css";s:4:"9085";s:39:"doc/reST/build/html/_static/doctools.js";s:4:"5ff5";s:44:"doc/reST/build/html/_static/down-pressed.png";s:4:"ebe8";s:36:"doc/reST/build/html/_static/down.png";s:4:"f6f3";s:36:"doc/reST/build/html/_static/file.png";s:4:"6587";s:37:"doc/reST/build/html/_static/jquery.js";s:4:"1009";s:37:"doc/reST/build/html/_static/minus.png";s:4:"8d57";s:36:"doc/reST/build/html/_static/plus.png";s:4:"0125";s:40:"doc/reST/build/html/_static/pygments.css";s:4:"d625";s:42:"doc/reST/build/html/_static/searchtools.js";s:4:"d550";s:38:"doc/reST/build/html/_static/sidebar.js";s:4:"521d";s:41:"doc/reST/build/html/_static/underscore.js";s:4:"db5b";s:42:"doc/reST/build/html/_static/up-pressed.png";s:4:"8ea9";s:34:"doc/reST/build/html/_static/up.png";s:4:"ecc3";s:41:"doc/reST/build/html/_static/websupport.js";s:4:"9e61";s:31:"doc/reST/build/json/Index.fjson";s:4:"7c62";s:38:"doc/reST/build/json/environment.pickle";s:4:"2409";s:34:"doc/reST/build/json/genindex.fjson";s:4:"87c3";s:38:"doc/reST/build/json/globalcontext.json";s:4:"6f6a";s:30:"doc/reST/build/json/last_build";s:4:"d41d";s:31:"doc/reST/build/json/objects.inv";s:4:"b995";s:32:"doc/reST/build/json/search.fjson";s:4:"ac00";s:36:"doc/reST/build/json/searchindex.json";s:4:"0e59";s:60:"doc/reST/build/json/Administration/InstallingExtension.fjson";s:4:"0774";s:44:"doc/reST/build/json/Administration/RDF.fjson";s:4:"2981";s:53:"doc/reST/build/json/Administration/SelectorForm.fjson";s:4:"6c54";s:51:"doc/reST/build/json/Administration/TypoScript.fjson";s:4:"c496";s:43:"doc/reST/build/json/Configuration/Pi1.fjson";s:4:"e850";s:43:"doc/reST/build/json/Configuration/Pi2.fjson";s:4:"b97e";s:45:"doc/reST/build/json/DeveloperManual/API.fjson";s:4:"1bb0";s:47:"doc/reST/build/json/DeveloperManual/Hooks.fjson";s:4:"9948";s:52:"doc/reST/build/json/DeveloperManual/WebService.fjson";s:4:"e488";s:50:"doc/reST/build/json/Introduction/Screenshots.fjson";s:4:"b7ae";s:50:"doc/reST/build/json/Introduction/Sponsorship.fjson";s:4:"740c";s:51:"doc/reST/build/json/Introduction/WhatDoesItDo.fjson";s:4:"e2bc";s:42:"doc/reST/build/json/Tutorial/UseCase.fjson";s:4:"9941";s:48:"doc/reST/build/json/UserManual/DomainModel.fjson";s:4:"adf0";s:52:"doc/reST/build/json/UserManual/MarkersSubparts.fjson";s:4:"1bb1";s:50:"doc/reST/build/json/UserManual/PluginOptions.fjson";s:4:"3331";s:48:"doc/reST/build/json/_images/AdvancedSettings.png";s:4:"33a8";s:40:"doc/reST/build/json/_images/Audience.png";s:4:"faeb";s:54:"doc/reST/build/json/_images/AudiencePersonnePrivee.png";s:4:"a5a0";s:40:"doc/reST/build/json/_images/CEWizard.png";s:4:"f2be";s:46:"doc/reST/build/json/_images/ConstantEditor.png";s:4:"7a7f";s:38:"doc/reST/build/json/_images/Domain.png";s:4:"97a1";s:38:"doc/reST/build/json/_images/EditCE.png";s:4:"1e5f";s:39:"doc/reST/build/json/_images/General.png";s:4:"9215";s:41:"doc/reST/build/json/_images/Hierarchy.png";s:4:"d204";s:41:"doc/reST/build/json/_images/IncludeTS.png";s:4:"82fd";s:42:"doc/reST/build/json/_images/InfoModify.png";s:4:"70d6";s:37:"doc/reST/build/json/_images/NewCE.png";s:4:"7921";s:40:"doc/reST/build/json/_images/Overview.jpg";s:4:"5cf2";s:38:"doc/reST/build/json/_images/Plugin.png";s:4:"4041";s:38:"doc/reST/build/json/_images/Result.png";s:4:"343e";s:39:"doc/reST/build/json/_images/Service.png";s:4:"c073";s:37:"doc/reST/build/json/_images/Topic.png";s:4:"4778";s:39:"doc/reST/build/json/_images/Version.png";s:4:"2c57";s:36:"doc/reST/build/json/_images/View.png";s:4:"f01c";s:38:"doc/reST/build/json/_sources/Index.txt";s:4:"457e";s:67:"doc/reST/build/json/_sources/Administration/InstallingExtension.txt";s:4:"20c9";s:51:"doc/reST/build/json/_sources/Administration/RDF.txt";s:4:"ed36";s:60:"doc/reST/build/json/_sources/Administration/SelectorForm.txt";s:4:"a1e0";s:58:"doc/reST/build/json/_sources/Administration/TypoScript.txt";s:4:"2849";s:50:"doc/reST/build/json/_sources/Configuration/Pi1.txt";s:4:"529e";s:50:"doc/reST/build/json/_sources/Configuration/Pi2.txt";s:4:"f6bf";s:52:"doc/reST/build/json/_sources/DeveloperManual/API.txt";s:4:"6f15";s:54:"doc/reST/build/json/_sources/DeveloperManual/Hooks.txt";s:4:"59a4";s:59:"doc/reST/build/json/_sources/DeveloperManual/WebService.txt";s:4:"e92b";s:57:"doc/reST/build/json/_sources/Introduction/Screenshots.txt";s:4:"c491";s:57:"doc/reST/build/json/_sources/Introduction/Sponsorship.txt";s:4:"a6e0";s:58:"doc/reST/build/json/_sources/Introduction/WhatDoesItDo.txt";s:4:"e443";s:49:"doc/reST/build/json/_sources/Tutorial/UseCase.txt";s:4:"5710";s:55:"doc/reST/build/json/_sources/UserManual/DomainModel.txt";s:4:"fff9";s:59:"doc/reST/build/json/_sources/UserManual/MarkersSubparts.txt";s:4:"671f";s:57:"doc/reST/build/json/_sources/UserManual/PluginOptions.txt";s:4:"d388";s:40:"doc/reST/build/json/_static/pygments.css";s:4:"d625";s:25:"doc/reST/source/Index.rst";s:4:"457e";s:23:"doc/reST/source/conf.py";s:4:"ec2d";s:54:"doc/reST/source/Administration/InstallingExtension.rst";s:4:"20c9";s:38:"doc/reST/source/Administration/RDF.rst";s:4:"ed36";s:47:"doc/reST/source/Administration/SelectorForm.rst";s:4:"a1e0";s:45:"doc/reST/source/Administration/TypoScript.rst";s:4:"2849";s:78:"doc/reST/source/Administration/Images/InstallingExtension/AdvancedSettings.png";s:4:"33a8";s:61:"doc/reST/source/Administration/Images/SelectorForm/Plugin.png";s:4:"4041";s:67:"doc/reST/source/Administration/Images/TypoScript/ConstantEditor.png";s:4:"7a7f";s:62:"doc/reST/source/Administration/Images/TypoScript/IncludeTS.png";s:4:"82fd";s:63:"doc/reST/source/Administration/Images/TypoScript/InfoModify.png";s:4:"70d6";s:37:"doc/reST/source/Configuration/Pi1.rst";s:4:"529e";s:37:"doc/reST/source/Configuration/Pi2.rst";s:4:"f6bf";s:39:"doc/reST/source/DeveloperManual/API.rst";s:4:"6f15";s:41:"doc/reST/source/DeveloperManual/Hooks.rst";s:4:"59a4";s:46:"doc/reST/source/DeveloperManual/WebService.rst";s:4:"e92b";s:44:"doc/reST/source/Introduction/Screenshots.rst";s:4:"c491";s:44:"doc/reST/source/Introduction/Sponsorship.rst";s:4:"a6e0";s:45:"doc/reST/source/Introduction/WhatDoesItDo.rst";s:4:"e443";s:60:"doc/reST/source/Introduction/Images/Screenshots/Overview.jpg";s:4:"5cf2";s:36:"doc/reST/source/Tutorial/UseCase.rst";s:4:"5710";s:66:"doc/reST/source/Tutorial/Images/UseCase/AudiencePersonnePrivee.png";s:4:"a5a0";s:52:"doc/reST/source/Tutorial/Images/UseCase/CEWizard.png";s:4:"f2be";s:50:"doc/reST/source/Tutorial/Images/UseCase/EditCE.png";s:4:"1e5f";s:49:"doc/reST/source/Tutorial/Images/UseCase/NewCE.png";s:4:"7921";s:50:"doc/reST/source/Tutorial/Images/UseCase/Result.png";s:4:"343e";s:42:"doc/reST/source/UserManual/DomainModel.rst";s:4:"fff9";s:46:"doc/reST/source/UserManual/MarkersSubparts.rst";s:4:"671f";s:44:"doc/reST/source/UserManual/PluginOptions.rst";s:4:"d388";s:60:"doc/reST/source/UserManual/Images/PluginOptions/Audience.png";s:4:"faeb";s:58:"doc/reST/source/UserManual/Images/PluginOptions/Domain.png";s:4:"97a1";s:59:"doc/reST/source/UserManual/Images/PluginOptions/General.png";s:4:"9215";s:61:"doc/reST/source/UserManual/Images/PluginOptions/Hierarchy.png";s:4:"d204";s:59:"doc/reST/source/UserManual/Images/PluginOptions/Service.png";s:4:"c073";s:57:"doc/reST/source/UserManual/Images/PluginOptions/Topic.png";s:4:"4778";s:59:"doc/reST/source/UserManual/Images/PluginOptions/Version.png";s:4:"2c57";s:56:"doc/reST/source/UserManual/Images/PluginOptions/View.png";s:4:"f01c";}',
);

?>