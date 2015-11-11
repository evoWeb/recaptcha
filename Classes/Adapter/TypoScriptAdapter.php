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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class RecaptchaAdapter
 */
class TypoScriptAdapter
{
    /**
     * Captcha object
     *
     * @var \Evoweb\Recaptcha\Services\CaptchaService
     */
    protected $captcha = null;

    /**
     * @return self
     */
    public function __construct()
    {
        $this->captcha = GeneralUtility::makeInstance('Evoweb\\Recaptcha\\Services\\CaptchaService');
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

            /** @var \TYPO3\CMS\Form\Validation\RecaptchaValidator $recaptchaValidator */
            $recaptchaValidator = GeneralUtility::makeInstance('TYPO3\\CMS\\Form\\Validation\\RecaptchaValidator');
            $validationError = $recaptchaValidator->getError();
            if (count($validationError)) {
                /** @var ContentObjectRenderer $content */
                $content = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

                $output .= '<strong class="error">' . $content->cObjGetSingle($validationError['cObj'],
                        $validationError['cObj.']) .
                    '</strong>';
            }
        } else {
            $output = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'error_captcha.notinstalled', 'Recaptcha', ['recaptcha']
            );
        }

        return $output;
    }
}
