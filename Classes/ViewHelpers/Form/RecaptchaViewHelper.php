<?php
namespace Evoweb\Recaptcha\ViewHelpers\Form;

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

class RecaptchaViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper
{
    public function render(): string
    {
        $name = $this->getName();
        $this->registerFieldNameForFormTokenGeneration($name);

        $captchaService = \Evoweb\Recaptcha\Services\CaptchaService::getInstance();

        $this->templateVariableContainer->add('configuration', $captchaService->getConfiguration());
        $this->templateVariableContainer->add('showCaptcha', $captchaService->getShowCaptcha());
        $this->templateVariableContainer->add('name', $name);

        $content = $this->renderChildren();

        $this->templateVariableContainer->remove('name');
        $this->templateVariableContainer->remove('showCaptcha');
        $this->templateVariableContainer->remove('configuration');

        return $content;
    }
}
