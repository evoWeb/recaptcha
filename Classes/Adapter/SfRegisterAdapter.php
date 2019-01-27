<?php
namespace Evoweb\Recaptcha\Adapter;

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

class SfRegisterAdapter extends \Evoweb\SfRegister\Services\Captcha\AbstractAdapter
{
    /**
     * Captcha object
     *
     * @var \Evoweb\Recaptcha\Services\CaptchaService
     */
    protected $captcha;

    /**
     * @var \Evoweb\SfRegister\Services\Session
     */
    protected $session;

    public function injectCaptcha(\Evoweb\Recaptcha\Services\CaptchaService $captcha)
    {
        $this->captcha = $captcha;
    }

    public function injectSession(\Evoweb\SfRegister\Services\Session $session)
    {
        $this->session = $session;
    }

    /**
     * Rendering the output of the captcha
     *
     * @return string|array
     */
    public function render()
    {
        $this->session->remove('captchaWasValid');

        if ($this->captcha !== null) {
            $output = $this->captcha->getReCaptcha();
        } else {
            $output = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
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
    public function isValid($value): bool
    {
        $validCaptcha = true;

        if ($this->captcha !== null && $this->session->get('captchaWasValid') !== true) {
            $status = $this->captcha->validateReCaptcha();

            if ($status == false || $status['error'] !== '') {
                $validCaptcha = false;
                $this->addError(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
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
