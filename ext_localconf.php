<?php

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:form/Resources/Private/Language/Database.xlf'][] =
        'EXT:recaptcha/Resources/Private/Language/Backend.xlf';

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        't3-form-icon-recaptcha',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:recaptcha/Resources/Public/Images/reCaptcha_sw.svg']
    );

    if (TYPO3_MODE == 'BE') {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('
module.tx_form {
    settings {
        yamlConfigurations {
            1974 = EXT:recaptcha/Configuration/Yaml/BaseSetup.yaml
            1975 = EXT:recaptcha/Configuration/Yaml/FormEditorSetup.yaml
        }
    }
}
        ');
    }
});
