<?php
namespace Evoweb\Recaptcha\Services;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Sebastian Fischer <typo3@evoweb.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RecaptchaService
 */
class CaptchaService
{

    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $contentObject;

    /**
     * @return self
     * @throws \Exception
     */
    public function __construct()
    {
        /**
         * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $frontend
         */
        $frontend = $GLOBALS['TSFE'];
        $this->configuration = $frontend->tmpl->setup['plugin.']['tx_recaptcha.'];

        if (!is_array($this->configuration) || empty($this->configuration)) {
            throw new \Exception('Please configure plugin.tx_recaptcha. before rendering the recaptcha', 1417680291);
        }

        $this->contentObject = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
    }

    /**
     * Build reCAPTCHA Frontend HTML-Code
     *
     * @return string reCAPTCHA HTML-Code
     */
    public function getReCaptcha()
    {
        return $this->contentObject->stdWrap($this->configuration['public_key'], $this->configuration['public_key.']);
    }

    /**
     * Validate reCAPTCHA challenge/response
     *
     * @return array Array with verified- (boolean) and error-code (string)
     */
    public function validateReCaptcha()
    {
        $request = [
            'secret' => $this->configuration['private_key'],
            'response' => trim(GeneralUtility::_GP('g-recaptcha-response')),
            'remoteip' => GeneralUtility::getIndpEnv('REMOTE_ADDR'),
        ];

        $result = ['verified' => false, 'error' => ''];
        if (empty($data['response'])) {
            $result['error'] = 'Recaptcha response missing';
        } else {
            $response = $this->queryVerificationServer($request);
            if (!$response) {
                $result['error'] = 'Verification server did not responde';
            }

            if ($result['success']) {
                $result['verified'] = true;
            } else {
                $result['error'] = $result['error-codes'];
            }
        }

        return $result;
    }

    /**
     * Query reCAPTCHA server for captcha-verification
     *
     * @param array $data
     * @return array Array with verified- (boolean) and error-code (string)
     */
    protected function queryVerificationServer($data)
    {
        $verifyServerInfo = @parse_url($this->configuration['verify_server']);

        if (empty($verifyServerInfo)) {
            return [false, 'recaptcha-not-reachable'];
        }

        $request = GeneralUtility::implodeArrayForUrl('', $data);
        $response = GeneralUtility::getURL($this->configuration['verify_server'] . '?' . $request);

        return json_decode($response);
    }
}