<?php

namespace Evoweb\Recaptcha\Adapter;

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

use Evoweb\Recaptcha\Services\CaptchaService;

class TypoScriptAdapter
{
    /**
     * @var CaptchaService
     */
    protected $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    public function render(): string
    {
        if ($this->captchaService !== null) {
            $output = $this->captchaService->getReCaptcha();

            $status = $this->captchaService->validateReCaptcha();
            if ($status == false || $status['error'] !== '') {
                $output .= '<span class="error">' .
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'error_recaptcha_' . $status['error'],
                        'Recaptcha'
                    ) .
                    '</span>';
            }
        } else {
            $output = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'error_captcha.notinstalled',
                'Recaptcha'
            );
        }

        return $output;
    }
}
