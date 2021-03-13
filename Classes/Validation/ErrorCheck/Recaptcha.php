<?php

namespace Evoweb\Recaptcha\Validation\ErrorCheck;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Typoheads\Formhandler\Validator\ErrorCheck\AbstractErrorCheck;

/**
 * EXT:formhandler ErrorCheck for Recaptcha.
 *
 * @deprecated with 11.x
 */
class Recaptcha extends AbstractErrorCheck
{
    /**
     * Checks the ReCaptcha.
     *
     * @return string
     */
    public function check(): string
    {
        /** @var CaptchaService $captcha */
        $captcha = GeneralUtility::getContainer()->get(CaptchaService::class);

        $checkFailed = '';
        if ($captcha !== null) {
            $status = $captcha->validateReCaptcha();
            if ($status == false || $status['error'] !== '') {
                $checkFailed = $this->getCheckFailed();
            }
        }

        return $checkFailed;
    }
}
