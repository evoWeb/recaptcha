<?php

declare(strict_types=1);

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

namespace Evoweb\Recaptcha\Adapter;

use Evoweb\Recaptcha\Services\CaptchaService;
use Evoweb\SfRegister\Services\Captcha\AbstractAdapter;
use Evoweb\SfRegister\Services\Session;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class SfRegisterAdapter extends AbstractAdapter
{
    public function __construct(protected CaptchaService $captchaService, protected Session $session)
    {
    }

    /**
     * Rendering the output of the captcha
     */
    public function render(): array|string
    {
        $this->session->remove('captchaWasValid');
        return $this->captchaService->getReCaptcha();
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

        if ($this->session->get('captchaWasValid') !== true) {
            $status = $this->captchaService->validateReCaptcha($value);

            if ($status['error'] !== '') {
                $validCaptcha = false;
                $this->addError(
                    LocalizationUtility::translate(
                        'error_recaptcha_' . $status['error'],
                        'Recaptcha'
                    ),
                    1307421960
                );
            }

            $this->session->set('captchaWasValid', $validCaptcha);
        }

        return $validCaptcha;
    }
}
