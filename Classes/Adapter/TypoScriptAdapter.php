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

namespace Evoweb\Recaptcha\Adapter;

use Evoweb\Recaptcha\Services\CaptchaService;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class TypoScriptAdapter
{
    public function __construct(protected CaptchaService $captchaService)
    {
    }

    public function render(): string
    {
        $output = $this->captchaService->getReCaptcha();

        $status = $this->captchaService->validateReCaptcha();
        if ($status['error'] !== '') {
            $output .= '<span class="error">'
                . LocalizationUtility::translate('error_recaptcha_' . $status['error'], 'Recaptcha')
                . '</span>';
        }

        return $output;
    }
}
