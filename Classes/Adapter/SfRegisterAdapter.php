<?php
namespace Evoweb\Recaptcha\Adapter;

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
use Evoweb\SfRegister\Services\Session;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RecaptchaAdapter
 */
class SfRegisterAdapter extends \Evoweb\SfRegister\Services\Captcha\AbstractAdapter
{
    /**
     * Captcha object
     *
     * @var CaptchaService
     */
    protected $captcha;

    /**
     * @var Session
     */
    protected $session;

    /**
     * SfRegisterAdapter constructor.
     */
    public function __construct()
    {
        $this->captcha = \Evoweb\Recaptcha\Services\CaptchaService::getInstance();
        $this->session = GeneralUtility::makeInstance(Session::class);
    }

    /**
     * Rendering the output of the captcha
     *
     * @return string
     */
    public function render()
    {
        $this->session->remove('captchaWasValidPreviously');

        if ($this->captcha !== null) {
            $output = $this->captcha->getReCaptcha();
        } else {
            $output = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'error_captcha.notinstalled',
                'Recaptcha',
                ['recaptcha']
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
    public function isValid($value)
    {
        $validCaptcha = true;

        $captchaWasValidPreviously = $this->session->get('captchaWasValidPreviously');
        if ($this->captcha !== null && $captchaWasValidPreviously !== true) {
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

        $this->session->set('captchaWasValidPreviously', $validCaptcha);

        return $validCaptcha;
    }
}
