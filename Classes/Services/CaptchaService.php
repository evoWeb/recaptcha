<?php

namespace Evoweb\Recaptcha\Services;

/*
 * This file is developed by evoWeb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Evoweb\Recaptcha\Exception\MissingException;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class CaptchaService
{
    protected array $configuration = [];

    public function __construct(
        protected ExtensionConfiguration $extensionConfiguration,
        protected ConfigurationManagerInterface $configurationManager,
        protected TypoScriptService $typoScriptService,
        protected ContentObjectRenderer $contentRenderer,
        protected RequestFactory $requestFactory
    ) {
        $this->initialize();
    }

    /**
     * @throws MissingException
     */
    protected function initialize(): void
    {
        $configuration = $this->extensionConfiguration->get('recaptcha');

        if (!is_array($configuration)) {
            $configuration = [];
        }

        $typoScriptConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'recaptcha'
        );

        if (!empty($typoScriptConfiguration)) {
            ArrayUtility::mergeRecursiveWithOverrule(
                $configuration,
                $this->typoScriptService->convertPlainArrayToTypoScriptArray($typoScriptConfiguration),
                true,
                false
            );
        }

        if (!is_array($configuration) || empty($configuration)) {
            throw new MissingException(
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

    /**
     * Get development mode for captcha rendering even if TYPO3_CONTENT is not development
     * Based on this the captcha does not get rendered or validated
     */
    protected function isInRobotMode(): bool
    {
        return (bool)($this->configuration['robotMode'] ?? false);
    }

    /**
     * Get development mode by TYPO3_CONTEXT
     * Based on this the captcha does not get rendered or validated
     */
    protected function isDevelopmentMode(): bool
    {
        return Environment::getContext()->isDevelopment();
    }

    /**
     * Get enforcing captcha rendering even if development mode is true
     */
    protected function isEnforceCaptcha(): bool
    {
        return (bool)($this->configuration['enforceCaptcha'] ?? false);
    }

    public function getShowCaptcha(): bool
    {
        return !$this->isInRobotMode()
            && (
                ApplicationType::fromRequest($this->getRequest())->isBackend()
                || !$this->isDevelopmentMode()
                || $this->isEnforceCaptcha()
            );
    }

    /**
     * Build reCAPTCHA Frontend HTML-Code
     *
     * @return string reCAPTCHA HTML-Code
     */
    public function getReCaptcha(): string
    {
        if ($this->getShowCaptcha()) {
            $captcha = $this->contentRenderer->stdWrap(
                $this->configuration['public_key'] ?? '',
                $this->configuration['public_key.'] ?? ''
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

        $request = [
            'secret' => $this->configuration['private_key'] ?? '',
            'response' => trim($this->getRequest()->getParsedBody()['g-recaptcha-response'] ?? ''),
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
        $verifyServerInfo = @parse_url($this->configuration['verify_server'] ?? '');

        if (empty($verifyServerInfo)) {
            return [
                'success' => false,
                'error-codes' => 'recaptcha-not-reachable'
            ];
        }

        $params = GeneralUtility::implodeArrayForUrl('', $data);
        $response = $this->requestFactory->request($this->configuration['verify_server'] . '?' . $params, 'POST');

        return (string)$response->getBody() ? json_decode((string)$response->getBody(), true) : [];
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
