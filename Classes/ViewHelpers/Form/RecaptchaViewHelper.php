<?php

declare(strict_types=1);

/*
 * This file is developed by evoWeb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Evoweb\Recaptcha\ViewHelpers\Form;

use Evoweb\Recaptcha\Services\CaptchaService;
use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper;

class RecaptchaViewHelper extends AbstractFormFieldViewHelper
{
    public function __construct(protected CaptchaService $captchaService)
    {
        parent::__construct();
    }

    public function render(): string
    {
        $name = $this->getName();
        $this->registerFieldNameForFormTokenGeneration($name);

        $container = $this->templateVariableContainer;
        $container->add('configuration', $this->captchaService->getConfiguration());
        $container->add('showCaptcha', $this->captchaService->getShowCaptcha());
        $container->add('name', $name);

        $content = $this->renderChildren();

        $container->remove('name');
        $container->remove('showCaptcha');
        $container->remove('configuration');

        return (string)$content;
    }
}
