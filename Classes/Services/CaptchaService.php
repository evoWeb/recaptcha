<?php

declare(strict_types=1);

/*
 * This file is developed by evoWeb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Evoweb\Recaptcha\Services;

use Evoweb\Recaptcha\Exception\MissingException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\NoServerRequestGivenException;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class CaptchaService
{
    public function __construct(
        #[Autowire(expression: 'service("extension-configuration").get("recaptcha")')]
        private array $extensionConfiguration,
        protected ConfigurationManagerInterface $configurationManager,
        protected TypoScriptService $typoScriptService,
        protected ContentObjectRenderer $contentRenderer,
        protected RequestFactory $requestFactory
    ) {
        $this->initialize();
    }

    /**
     * @throws MissingException
     * @throws NoServerRequestGivenException
     */
    protected function initialize(): void
    {
        if (!is_array($this->extensionConfiguration)) {
            $this->extensionConfiguration = [];
        }

        $typoScriptConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'recaptcha'
        );

        if (!empty($typoScriptConfiguration)) {
            ArrayUtility::mergeRecursiveWithOverrule(
                $this->extensionConfiguration,
                $this->typoScriptService->convertPlainArrayToTypoScriptArray($typoScriptConfiguration),
                true,
                false
            );
        }

        if (!is_array($this->extensionConfiguration) || empty($this->extensionConfiguration)) {
            throw new MissingException(
                'Please configure plugin.tx_recaptcha. before rendering the recaptcha',
                1417680291
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(): array
    {
        return $this->extensionConfiguration;
    }

    /**
     * Get development mode for captcha rendering even if TYPO3_CONTENT is not development
     * Based on this, the captcha does not get rendered or validated
     */
    protected function isInRobotMode(): bool
    {
        return (bool)($this->extensionConfiguration['robotMode'] ?? false);
    }

    /**
     * Get development mode by TYPO3_CONTEXT
     * Based on this, the captcha does not get rendered or validated
     */
    protected function isDevelopmentMode(): bool
    {
        return Environment::getContext()->isDevelopment();
    }

    /**
     * Get enforcing captcha rendering even if the development mode is true
     */
    protected function isEnforceCaptcha(): bool
    {
        return (bool)($this->extensionConfiguration['enforceCaptcha'] ?? false);
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
                $this->extensionConfiguration['public_key'] ?? '',
                $this->extensionConfiguration['public_key.'] ?? ''
            );
        } else {
            $captcha = '<div class="recaptcha-development-mode">
                Development mode active. Do not expect the captcha to appear
            </div>';
        }

        return $captcha ?? '';
    }

    /**
     * Validate reCAPTCHA challenge/response
     *
     * @return array<string, string|bool> Array with verified- (boolean) and error-code (string)
     */
    public function validateReCaptcha(string $value = ''): array
    {
        if (!$this->getShowCaptcha()) {
            return [
                'verified' => true,
                'error' => '',
            ];
        }

        $privateKey = $this->getRequest()->getParsedBody()['recaptcha-invisible'] ?? false
            ? $this->extensionConfiguration['invisible_private_key']
            : $this->extensionConfiguration['private_key'];
        $privateKey = $privateKey ?: $this->extensionConfiguration['private_key'];

        $request = [
            'secret' => $privateKey,
            'response' => trim(
                !empty($value) ? $value : (string)($this->getRequest()->getParsedBody()['g-recaptcha-response'] ?? '')
            ),
            'remoteip' => GeneralUtility::getIndpEnv('REMOTE_ADDR'),
        ];

        $result = [
            'verified' => false,
            'error' => '',
        ];
        if (empty($request['response'])) {
            $result['error'] = 'missing-input-response';
        } else {
            $response = $this->queryVerificationServer($request);
            if (!$response) {
                $result['error'] = 'validation-server-not-responding';
            }

            if ($response['success']) {
                $result['verified'] = true;
                if (($this->extensionConfiguration['threshold'] ?? 0) > 0.0) {
                    // Reject if the score is below a threshold
                    if (isset($response['score']) && $response['score'] < $this->extensionConfiguration['threshold']) {
                        $result['verified'] = false;
                        $result['error'] = 'score-threshold-not-met';
                    }
                }
            } else {
                $result['error'] = (string)(
                    is_array($response['error-codes']) ?
                    reset($response['error-codes']) :
                    $response['error-codes']
                );
            }
        }

        return $result;
    }

    /**
     * Query reCAPTCHA server for captcha-verification
     *
     * @param array<string, string> $data
     * @return array<string, string|bool|array<string>> Array with verified- (boolean) and error-code (string)
     */
    protected function queryVerificationServer(array $data): array
    {
        $verifyServerInfo = @parse_url($this->extensionConfiguration['verify_server'] ?? '');

        if (empty($verifyServerInfo)) {
            return [
                'success' => false,
                'error-codes' => 'recaptcha-not-reachable',
            ];
        }

        $params = GeneralUtility::implodeArrayForUrl('', $data);
        $response = $this->requestFactory->request(
            $this->extensionConfiguration['verify_server'] . '?' . $params,
            'POST'
        );

        $body = (string)$response->getBody();
        return $body ? json_decode($body, true) : [];
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
