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

namespace Evoweb\Recaptcha\Validation;

use Evoweb\Recaptcha\Services\CaptchaService;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class RecaptchaValidator extends AbstractValidator
{
    protected $acceptsEmptyValues = false;

    public function __construct(protected CaptchaService $captchaService) {}

    /**
     * Validate the captcha value from the request and add an error if not valid
     */
    public function isValid(mixed $value): void
    {
        $status = $this->captchaService->validateReCaptcha((string)$value);
        if ($status['error'] !== '') {
            $errorText = $this->translateErrorMessage(
                'LLL:EXT:recaptcha/Resources/Private/Language/locallang.xlf:error_recaptcha_' . $status['error']
            );

            if (empty($errorText)) {
                $errorText = htmlspecialchars($status['error']);
            }

            $this->addError($errorText, 1519982125);
        }
    }
}
