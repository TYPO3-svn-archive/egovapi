.. coding: utf-8 without BOM
.. _Show files in current folder: .

.. _start:

############################################################
EXT: eGov API
############################################################

.. only:: html

	:Classification:
		egovapi

	:Version:
		|release|

	:Language:
		en, de, fr, it

	:Description:
		Official implementation of the swiss eGov Remote API (eGovernment) that allows to access the
		Reference eGov CH application from SECO which hosts and provides administrative services
		(cyberadministration).

	:Keywords:
		e-government, switzerland, administrative services, cyberadministration

	:Copyright:
		2010-2013

	:Author:
		Xavier Perseguers (Causal) for SECO

	:Email:
		xavier@causal.ch

	:License:
		This document is published under the Open Content License
		available from http://www.opencontent.org/opl.shtml

	:Rendered:
		|today|

	The content of this document is related to TYPO3,
	a GNU/GPL CMS/Framework available from `www.typo3.org <http://www.typo3.org/>`_.

Introduction
############

.. toctree::
	:maxdepth: 2

	Introduction/WhatDoesItDo
	Introduction/Sponsorship
	Introduction/Screenshots

User manual
###########

.. toctree::
	:maxdepth: 2

	UserManual/PluginOptions
	UserManual/DomainModel
	UserManual/MarkersSubparts

Administration
##############

This chapter describes how to manage the extension from a superuser point of view.

.. toctree::
	:maxdepth: 2

	Administration/InstallingExtension
	Administration/TypoScript
	Administration/SelectorForm
	Administration/RDF

Configuration
#############

.. toctree::
	:maxdepth: 2

	Configuration/Pi1
	Configuration/Pi2

Tutorial
########

This tutorial is best suited for day-to-day webmasters or editors having to integrate the eGov API
within their website. It assumes an administrator already properly installed this extension (see
chapter Administration). It is targeted at users and as such section "Plugin options" of
chapter User manual) is part of the basic know-how the webmaster or editor should have to be
able to use this extension.

.. toctree::
	:maxdepth: 2

	Tutorial/UseCase

Developer manual
################

This chapter is really targeted at extension developers. Most TYPO3 integrators should never have
the need to go that deep in order for them to configure the eGov API extension to fit their
integration needs. If however you encounter some limitation you cannot solve using TypoScript
configuration, you may want to read the following sections to learn how to take more control.

.. toctree::
	:maxdepth: 2

	DeveloperManual/Hooks
	DeveloperManual/API
	DeveloperManual/WebService

Known problems
##############

Please use the extension's bug tracker on Forge to report bugs:
http://forge.typo3.org/projects/extension-egovapi/issues.

To-Do list
##########

Please use the extension's bug tracker on Forge to propose new features:
http://forge.typo3.org/projects/extension-egovapi/issues.

ChangeLog
#########

The following is a very high level overview of the changes in this extension. For more details, see
the ChangeLog file included with the extension or `read it online
<http://forge.typo3.org/projects/extension-egovapi/repository/entry/trunk/ChangeLog/>`_.

+-------------+----------------------------------------------------------------------------------+
| Version     | Changes                                                                          |
+=============+==================================================================================+
| 2.0.0       | - RDF output, semantic web integration                                           |
+-------------+----------------------------------------------------------------------------------+
| 1.5.0       | - Google Map and loading mask support                                            |
|             | - Localization files have been converted to XLIFF                                |
+-------------+----------------------------------------------------------------------------------+
| 1.4.0       | - Added support for both the existing web service and its upcoming new version   |
|             | - Tested with TYPO3 4.6                                                          |
+-------------+----------------------------------------------------------------------------------+
| 1.3.0       | - Added microformat markers (hCard) for the service's office                     |
|             | - Services are grouped by provider for the selector form plugin                  |
+-------------+----------------------------------------------------------------------------------+
| 1.2.0       | - Selector form plugin added                                                     |
+-------------+----------------------------------------------------------------------------------+
| 1.1.0       | - Completed German translation                                                   |
+-------------+----------------------------------------------------------------------------------+
| 1.0.0       | - Stable release                                                                 |
|             | - Updated documentation with additional screenshots                              |
|             | - Updated templates with additional CSS classes                                  |
+-------------+----------------------------------------------------------------------------------+
| 0.9.0       | - Further documentation                                                          |
+-------------+----------------------------------------------------------------------------------+
| 0.8.0       | - First release on TER                                                           |
+-------------+----------------------------------------------------------------------------------+
