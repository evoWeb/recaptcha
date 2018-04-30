<?php
namespace Evoweb\Recaptcha\Validation;

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

class RecaptchaValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**
     * Validate the captcha value from the request and add an error if not valid
     *
     * @param mixed $value
     */
    public function isValid($value)
    {
        $captcha = \Evoweb\Recaptcha\Services\CaptchaService::getInstance();

        if ($captcha !== null) {
            $status = $captcha->validateReCaptcha();

            if ($status == false || $status['error'] !== '') {
                $errorText = $this->translateErrorMessage('error_recaptcha_' . $status['error'], 'recaptcha');

                if (empty($errorText)) {
                    $errorText = htmlspecialchars($status['error']);
                }

                $this->addError($errorText, 1519982125);
            }
        }
    }
}
