<?php
namespace Evoweb\Recaptcha\Services;

/**
 * This file is developed by evoweb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class CaptchaService
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $configuration = [];

    public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->initialize();
    }

    public static function getInstance(): CaptchaService
    {
        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Object\ObjectManager::class
        );
        /** @var self $instance */
        $instance = $objectManager->get(self::class);
        return $instance;
    }

    /**
     * @throws \Evoweb\Recaptcha\Exception\MissingException
     */
    protected function initialize()
    {
        $configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
        )->get('recaptcha');

        if (!is_array($configuration)) {
            $configuration = [];
        }

        /** @var ConfigurationManagerInterface $configurationManager */
        $configurationManager = $this->objectManager->get(ConfigurationManagerInterface::class);
        $typoScriptConfiguration = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'recaptcha'
        );

        if (!empty($typoScriptConfiguration) && is_array($typoScriptConfiguration)) {
            /** @var TypoScriptService $typoScriptService */
            $typoScriptService = $this->objectManager->get(TypoScriptService::class);
            \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
                $configuration,
                $typoScriptService->convertPlainArrayToTypoScriptArray($typoScriptConfiguration),
                true,
                false
            );
        }

        if (!is_array($configuration) || empty($configuration)) {
            throw new \Evoweb\Recaptcha\Exception\MissingException(
                'Please configure plugin.tx_recaptcha. before rendering the recaptcha',
                1417680291
            );
        }

        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    protected function getContentObjectRenderer(): ContentObjectRenderer
    {
        /** @var ContentObjectRenderer $contentRenderer */
        $contentRenderer = $this->objectManager->get(ContentObjectRenderer::class);
        return $contentRenderer;
    }

    /**
     * Get development mode by TYPO3_CONTEXT
     * Based on this the captcha does not get rendered or validated
     */
    protected function isDevelopmentMode(): bool
    {
        return (bool) GeneralUtility::getApplicationContext()->isDevelopment();
    }

    /**
     * Get enforcing captcha rendering even if development mode is true
     */
    protected function isEnforceCaptcha(): bool
    {
        return (bool) $this->configuration['enforceCaptcha'];
    }

    public function getShowCaptcha(): bool
    {
        return TYPO3_MODE == 'BE' || !$this->isDevelopmentMode() || $this->isEnforceCaptcha();
    }

    /**
     * Build reCAPTCHA Frontend HTML-Code
     *
     * @return string reCAPTCHA HTML-Code
     */
    public function getReCaptcha(): string
    {
        if ($this->getShowCaptcha()) {
            $captcha = $this->getContentObjectRenderer()->stdWrap(
                $this->configuration['public_key'],
                $this->configuration['public_key.']
            );
        } else {
            $captcha = '<div class="recaptcha-development-mode">
                Development mode active. Do not expect the captcha to appear
                </div>';
        }

        return $captcha;
    }

    /**
     * Validate reCAPTCHA challenge/response
     *
     * @return array Array with verified- (boolean) and error-code (string)
     */
    public function validateReCaptcha(): array
    {
        if (!$this->getShowCaptcha()) {
            return [
                'verified' => true,
                'error' => ''
            ];
        }

        if (!isset($this->configuration) || empty($this->configuration)) {
            if (! $this->objectManager instanceof \TYPO3\CMS\Extbase\Object\ObjectManager) {
                /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
                $objectManager = GeneralUtility::makeInstance(
                    \TYPO3\CMS\Extbase\Object\ObjectManager::class
                );
                $this->injectObjectManager($objectManager);
            }
        }

        $request = [
            'secret' => $this->configuration['private_key'],
            'response' => trim(GeneralUtility::_GP('g-recaptcha-response')),
            'remoteip' => GeneralUtility::getIndpEnv('REMOTE_ADDR'),
        ];

        $result = ['verified' => false, 'error' => ''];
        if (empty($request['response'])) {
            $result['error'] = 'missing-input-response';
        } else {
            $response = $this->queryVerificationServer($request);
            if (!$response) {
                $result['error'] = 'validation-server-not-responding';
            }

            if ($response['success']) {
                $result['verified'] = true;
            } else {
                $result['error'] = is_array($response['error-codes']) ?
                    reset($response['error-codes']) :
                    $response['error-codes'];
            }
        }

        return $result;
    }

    /**
     * Query reCAPTCHA server for captcha-verification
     *
     * @param array $data
     *
     * @return array Array with verified- (boolean) and error-code (string)
     */
    protected function queryVerificationServer(array $data): array
    {
        $verifyServerInfo = @parse_url($this->configuration['verify_server']);

        if (empty($verifyServerInfo)) {
            return [
                'success' => false,
                'error-codes' => 'recaptcha-not-reachable'
            ];
        }

        $request = GeneralUtility::implodeArrayForUrl('', $data);
        $response = GeneralUtility::getUrl($this->configuration['verify_server'] . '?' . $request);

        return $response ? json_decode($response, true) : [];
    }
}
