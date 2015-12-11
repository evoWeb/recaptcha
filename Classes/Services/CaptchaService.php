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

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class RecaptchaService
 */
class CaptchaService
{
    /**
     * @var array
     */
    protected $configuration = array();

    /**
     * @var ContentObjectRenderer
     */
    protected $contentObject;

    /**
     * @return self
     * @throws \Exception
     */
    public function __construct()
    {
        $configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['recaptcha']);

        if (!is_array($configuration)) {
            $configuration = array();
        }

        $frontend = $this->getTypoScriptController();
        if (!empty($frontend)
            && isset($frontend->tmpl->setup['plugin.']['tx_recaptcha.'])
            && is_array($frontend->tmpl->setup['plugin.']['tx_recaptcha.'])
        ) {
            ArrayUtility::mergeRecursiveWithOverrule(
                $configuration,
                $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_recaptcha.']
            );
        }

        if (!is_array($configuration) || empty($configuration)) {
            throw new \Exception('Please configure plugin.tx_recaptcha. before rendering the recaptcha', 1417680291);
        }

        $this->configuration = $configuration;
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
        $request = array(
            'secret' => $this->configuration['private_key'],
            'response' => trim(GeneralUtility::_GP('g-recaptcha-response')),
            'remoteip' => GeneralUtility::getIndpEnv('REMOTE_ADDR'),
        );

        $result = array('verified' => false, 'error' => '');
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
                $result['error'] = $response['error-codes'];
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
            return array(false, 'recaptcha-not-reachable');
        }

        $request = GeneralUtility::implodeArrayForUrl('', $data);
        $url = $this->configuration['verify_server'] . '?' . $request;

        /**
         $curlHandler = curl_init();
         curl_setopt($curlHandler, CURLOPT_URL, $url);
         curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, TRUE);
         curl_setopt($curlHandler, CURLOPT_TIMEOUT, 15);
         curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, FALSE);
         $response = curl_exec($curlHandler);
         curl_close($curlHandler);
         */

        $response = GeneralUtility::getUrl($url);

        return json_decode($response, true);
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptController()
    {
        return $GLOBALS['TSFE'];
    }
}
