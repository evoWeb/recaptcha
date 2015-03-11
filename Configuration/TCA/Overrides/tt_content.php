<?php
defined('TYPO3_MODE') or die('Access denied.');

// Add Default TS to Include static (from extensions)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	'recaptcha', 'Configuration/TypoScript/', 'reCAPTCHA'
);
