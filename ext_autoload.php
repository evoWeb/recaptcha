<?php

$classesPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('recaptcha') . 'Classes/';

return array(
    'TYPO3\\CMS\\Form\\Validation\\RecaptchaValidator' => $classesPath . 'Validation/RecaptchaValidator.php',
);
