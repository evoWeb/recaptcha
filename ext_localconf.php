<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(function () {
    ExtensionManagementUtility::addTypoScriptSetup('
module.tx_form.settings.yamlConfigurations.1974 = EXT:recaptcha/Configuration/Yaml/FormSetup.yaml
    ');
});
