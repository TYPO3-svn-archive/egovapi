.. sectnum::
.. Ã„Ã–ÃœÃ¤Ã¶Ã¼ÃŸ
.. coding: utf-8 without BOM
.. _Show files in current folder: .

============================================================
EXT: eGov API
============================================================

:Extension Key: egovapi
:Language:      en, de, fr, it
:Keywords:      e-government, switzerland, administrative services, cyberadministration
:Author:        Causal Sàrl <xavier@causal.ch> for SECO
:Date:          2011-10-13
:Revision:      $Revision$
:Description:   This is the documentation for the TYPO3 extension egovapi

.. contents::

Introduction
============

What does it do?
----------------

In the digital age, enterprises and citizens should be able to ask for an administrative
authorization or an official register extract electronically. Thanks to the eGov CH Reference
project, they can access any useful information online 24/7 and order via internet or mobile phone.
The administration better responds to current requirements and customer needs, helping to reduce
bureaucracy and increasing the attractiveness of the Swiss economy.

The eGov API extension aims at providing an easy way to swiss public authorities to show and grant
access to the different administrative services they offer through their TYPO3 website.

Sponsorship
-----------

This extension is the official implementation of the swiss eGov Remote API for TYPO3. It has been
sponsored by the State Secretariat for Economic Affairs SECO which is the Confederation's competence
centre for all core issues relating to economic policy. The SECO wanted to improve the online access
to the administrative services they offer. This project has been developed and is being maintained
by Causal Sàrl, in Fribourg.

Further information:

- Website of the SECO: http://www.seco.admin.ch/
- Reference eGov project's website: http://www.cyberadmin.ch/
- Causal Sàrl: http://causal.ch/

Screenshots
-----------

.. image:: images/screenshots/overview.jpg





User manual
===========

Plugin options
--------------

The plugin is split among a general configuration tab and then a tab for each and every level of the
eGov API (Audience, View, Domain, Topic, Service). Finally a “Version” tab allows you to force a
given version of a service to be used instead of the default one. “Other Settings” provides a few
additional configuration options we will describe later on.

General
```````

.. image:: images/user_manual/general.png

The rules are as follows:

- If a field is left empty, the corresponding TypoScript property is taken into account.
- If the corresponding TypoScript property is empty, there is no restriction. In the screenshot
  above, leaving “Authorized levels” empty will let you freely navigate from Audience to Service
  back and forth.

**Backend administrators only:** Field myTS allows you to override the TypoScript configuration
after the merge of the FlexForm options. This is really the last chance to update the rendering
configuration. Any configuration option may be overridden except the web service related properties
(WSDL, cache lifetime, language, ...). A business processing makes use of it before the rendering
takes place. We assume it is more than unlikely to be needed anyway.

Level hierarchy
```````````````

Before actually describing the options of the level configuration tabs, it may help giving us an
overview of the level hierarchy within the eGov web service:

.. image:: images/user_manual/hierarchy.png

Audience
````````

.. image:: images/user_manual/audience.png

By selecting items, you trim down the list of items being shown by the plugin and this additionally
lets you manually order them.

Whenever you change the list of selected items in any of the Audience, View, Domain or Topic tab, you
should save your plugin configuration as the sublevel list of items in the next tab will be filtered
accordingly. E.g., in the screenshot above we selected “Entreprise” and “Personne privée” as
audiences to be shown and we ordered them to show “Entreprise” and then “Personne privée”. We should
now save the plugin configuration before moving on to tab “View” where we only will get views related
either to “Entreprise” or to “Personne privée” in the available items.

View
````

.. image:: images/user_manual/view.png

Domain
``````

.. image:: images/user_manual/domain.png

**Blocks to show:** This section is used when showing the details of a single domain. It allows us
to trim down the amount of information or to split them among multiple copies of the plugin on a
detail page.

Topic
`````

.. image:: images/user_manual/topic.png

Service
```````

.. image:: images/user_manual/service.png

Version
```````

.. image:: images/user_manual/version.png

Domain Model
------------

This section describes the eGov API domain model. The domain model may be accessed directly when
using Fluid-based templates. If instead you use marker-based templates, only partial access to the
domain model will be possible and you probably will have to implement one of the available hooks
(see `Hooks`_ section in `Developer manual`_) for more complex scenarios.

We only describe domain model for the eGov API levels (audience, view, domain, topic, service).
Attributes for the blocks of information which are typically used for the details view of an entity
should be analyzed with Fluid template examples or by looking at the classes within directory
``Classes/Domain/Model/Blocks/`` (e.g., using http://api.causal.ch/egovapi/).

Available markers and subparts
------------------------------





Administration
==============

Installing the extension
------------------------

TypoScript configuration
------------------------

Selector Form Plugin
--------------------





Configuration
=============

plugin.tx_egovapi_pi1
---------------------

plugin.tx_egovapi_pi1.displayBlocks
-----------------------------------

plugin.tx_egovapi_pi1.versions
------------------------------

plugin.tx_egovapi_pi1.targets
-----------------------------

plugin.tx_egovapi_pi1.templates
-------------------------------

plugin.tx_egovapi_pi2
---------------------





Tutorial
========

This tutorial is best suited for day-to-day webmasters or editors having to integrate the eGov API
within their website. It assumes an administrator already properly installed this extension (see
chapter `Administration`_). It is targeted at users and as such section “`Plugin options`_” of
chapter `User manual`_) is part of the basic know-how the webmaster or editor should have to be
able to use this extension.

Use case
--------

Description
```````````

You would like to show the list of service domains available for the audience “Personne privée”
(100) in some part of your website.

Step-by-step explanation
````````````````````````

1. Open Web > Page module and navigate within your website to the page where you would like to
   add the eGov API plugin.
2. Click on icon |new_ce| to add a content element to your page
3. Move to section “Plugins” and select the eGov API plugin:

.. |new_ce| image:: images/tutorial/new_ce.png
.. image:: images/tutorial/ce_wizard.png

After having given a header to your content element as a best practice (possibly set its rendering
Type to hidden), you should configure the eGov API plugin:

.. image:: images/tutorial/edit_ce.png

1. Move to the “Plugin” tab to access plugin's configuration options
2. Select “General” option tab
3. Authorize level “Domain” to be shown. As this is the only selected level, it will be used as
   entry point for the plugin and will not allow navigation to other levels. If you need this, you
   may either add other authorized levels after the entry point level or configure redirect pages
   containing plugins for the other levels on “Other Settings” option tab.

Last step is to configure the plugin in order to only show the audience “Personne privée”:

.. image:: images/tutorial/audience_personne_privee.png

1. Select “Audience” option tab
2. Select audience “Personne privée”.

That's it! If you show your page, you should have a list of domains related to the audience
“Personne privée”:

.. image:: images/tutorial/result.png





Developer manual
================

This chapter is really targeted at extension developers. Most TYPO3 integrators should never have
the need to go that deep in order for them to configure the eGov API extension to fit their
integration needs. If however you encounter some limitation you cannot solve using TypoScript
configuration, you may want to read the following sections to learn how to take more control.

Hooks
-----

Some hooks have been integrated into the eGov API extension. They are primarily targeted at letting
you post-process the subparts and markers prior to the actual rendering process, when using
marker-based templates or to post-process the AJAX returned data. Hooks have not been used for
Fluid-based templates as you may achieve the same goal with TypoScript configuration or use of
ViewHelpers.

Following hooks are available:

- Final post-processing:
    ``$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['egovapi']['renderHook']``
- Post-processing of audience subparts and markers:
    ``$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['egovapi']['prepareAudienceHook']``
- Post-processing of view subparts and markers:
    ``$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['egovapi']['prepareViewHook']``
- Post-processing of domain subparts and markers:
    ``$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['egovapi']['prepareDomainHook']``
- Post-processing of topic subparts and markers:
    ``$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['egovapi']['prepareTopicHook']``
- Post-processing of service subparts and markers:
    ``$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['egovapi']['prepareServiceHook']``
- Post-processing of AJAX returned data:
    ``$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['egovapi']['ajaxHook']``

API documentation
-----------------

The latest API documentation may be manually generated using doxygen configuration file
``doc/doxygen.conf``.

Alternatively, you may access it from http://api.causal.ch/egovapi/.

eGov web service
----------------

The eGov web service is documented by the corresponding WSDL.

When accessing this WSDL endpoint, XML schemas are defined as namespaces. The underlying XSD files
may be retrieved by prefixing the namespace by http://ref.cyberadmin.ch/WS20/ServiceContract/.
E.g., the schema of "dataCommonBlocks" may be accessed with
http://ref.cyberadmin.ch/WS20/Service/Contract/MessageContract/DataContract/CommonBlocks.xsd.





Known problems
==============

Please use the extension's bug tracker on Forge to report bugs:
http://forge.typo3.org/projects/extension-egovapi/issues.





To-Do list
==========

Please use the extension's bug tracker on Forge to propose new features:
http://forge.typo3.org/projects/extension-egovapi/issues.





ChangeLog
=========

The following is a very high level overview of the changes in this extension. For more details, see
the ChangeLog file included with the extension or `read it online
<http://forge.typo3.org/projects/extension-egovapi/repository/entry/trunk/ChangeLog/>`_.

+-------------+----------------------------------------------------------------------------------+
| Version     | Changes                                                                          |
+=============+==================================================================================+
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
