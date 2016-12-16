<?php
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
namespace Evoweb\Recaptcha\Adapter;

use Evoweb\Recaptcha\Services\CaptchaService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RecaptchaAdapter
 */
class TypoScriptAdapter
{
    /**
     * Captcha object
     *
     * @var CaptchaService
     */
    protected $captcha;

    /**
     * TypoScriptAdapter constructor.
     */
    public function __construct()
    {
        $this->captcha = GeneralUtility::makeInstance(CaptchaService::class);
    }

    /**
     * Rendering the output of the captcha
     *
     * @return string
     */
    public function render()
    {
        if ($this->captcha !== null) {
            $output = $this->captcha->getReCaptcha();

            $status = $this->captcha->validateReCaptcha();
            if ($status == false || $status['error'] !== '') {
                $output .= '<strong class="error">' .
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'error_recaptcha_' . $status['error'],
                        'Recaptcha'
                    ) .
                    '</strong>';
            }
        } else {
            $output = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'error_captcha.notinstalled',
                'Recaptcha',
                ['recaptcha']
            );
        }

        return $output;
    }
}
