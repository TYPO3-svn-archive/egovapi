.. _hooks:

Hooks
=====

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