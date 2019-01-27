<?php
namespace Evoweb\Recaptcha\Validation\ErrorCheck;

/**
 * EXT:formhandler ErrorCheck for Recaptcha.
 */
class Recaptcha extends \Typoheads\Formhandler\Validator\ErrorCheck\AbstractErrorCheck
{
    /**
     * Checks the ReCaptcha.
     *
     * @return string
     */
    public function check()
    {
        $captcha = \Evoweb\Recaptcha\Services\CaptchaService::getInstance();

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
