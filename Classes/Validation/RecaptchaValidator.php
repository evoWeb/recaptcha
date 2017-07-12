<?php
namespace Evoweb\Recaptcha\Validation;

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

use Evoweb\Recaptcha\Services\CaptchaService;

/**
 * Class RecaptchaValidator
 */
class RecaptchaValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**
     * Captcha service
     *
     * @var CaptchaService
     */
    protected $captcha;

    /**
     * RecaptchaValidator constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->captcha = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(CaptchaService::class);
    }

    /**
     * Validate the captcha value from the request and add an error if not valid
     *
     * @param mixed $value
     *
     * @return void
     */
    public function isValid($value)
    {
        if ($this->captcha !== null) {
            $status = $this->captcha->validateReCaptcha();

            if ($status == false || $status['error'] !== '') {
                $this->addError(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        $status['error'],
                        'recaptcha'
                    ),
                    1447258047591
                );
            }
        }
    }
}
