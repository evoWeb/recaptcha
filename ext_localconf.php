<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:form/Resources/Private/Language/Database.xlf'][] =
        'EXT:recaptcha/Resources/Private/Language/Backend.xlf';

    ExtensionManagementUtility::addTypoScriptSetup('
module.tx_form.settings.yamlConfigurations.1974 = EXT:recaptcha/Configuration/Yaml/FormSetup.yaml
    ');
});
