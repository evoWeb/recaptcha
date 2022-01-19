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
use Evoweb\SfRegister\Services\Captcha\AbstractAdapter;
use Evoweb\SfRegister\Services\Session;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class SfRegisterAdapter extends AbstractAdapter
{
    /**
     * @var CaptchaService
     */
    protected ?object $captcha = null;

    protected Session $session;

    public function __construct(CaptchaService $captcha, Session $session)
    {
        $this->captcha = $captcha;
        $this->session = $session;
    }

    /**
     * Rendering the output of the captcha
     *
     * @return string
     */
    public function render(): string
    {
        $this->session->remove('captchaWasValid');

        if ($this->captcha !== null) {
            $output = $this->captcha->getReCaptcha();
        } else {
            $output = LocalizationUtility::translate(
                'error_captcha.notinstalled',
                'Recaptcha'
            );
        }

        return $output;
    }

    /**
     * Validate the captcha value from the request and output an error if not valid
     *
     * @param string $value
     *
     * @return bool
     */
    public function isValid(string $value): bool
    {
        $validCaptcha = true;

        if ($this->captcha !== null && $this->session->get('captchaWasValid') !== true) {
            $status = $this->captcha->validateReCaptcha();

            if ($status == false || (string)$status['error'] !== '') {
                $validCaptcha = false;
                $this->addError(
                    LocalizationUtility::translate(
                        'error_recaptcha_' . $status['error'],
                        'Recaptcha'
                    ),
                    1307421960
                );
            }
        }

        $this->session->set('captchaWasValid', $validCaptcha);

        return $validCaptcha;
    }
}
