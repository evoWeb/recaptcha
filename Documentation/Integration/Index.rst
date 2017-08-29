.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

Integration
===========


Use in your extension
---------------------

To make use of this extension is quite easy as there is only one
service needed. By instantiating the
\Evoweb\Recaptcha\Services\CaptchaService it's possible to render
and validate the captcha.
Beside the service there are a ViewHelper and a Validator for use in
extbase extensions.


Integration in your own code
----------------------------


Rendering hole captcha
~~~~~~~~~~~~~~~~~~~~~~

To render you are able to let the service take care of the output
by calling getReCaptcha.

.. code-block:: php

   $captchaService = GeneralUtility::makeInstance(\Evoweb\Recaptcha\Services\CaptchaService::class);
   $output = $captchaService->getReCaptcha();

Please keep in mind that it only renders the captcha. If you need
something to trigger the validation in your controller it's up to
you to add the code.


Render on your own
~~~~~~~~~~~~~~~~~~

If you prefer to render on your own its possible to let the service
prepare the settings for you.

.. code-block:: php

   $captchaService = GeneralUtility::makeInstance(\Evoweb\Recaptcha\Services\CaptchaService::class);
   $configuration = $captchaService->getConfiguration();
   $showCaptcha = $captchaService->getShowCaptcha();

By using this you need to render the html completely on your own.


Validate submitted form
~~~~~~~~~~~~~~~~~~~~~~~

To validate just call the validateReCaptcha method and you get the
result of the validation to check against.

.. code-block:: php

   $captchaService = GeneralUtility::makeInstance(\Evoweb\Recaptcha\Services\CaptchaService::class);
   $status = $captchaService->validateReCaptcha();
   $valid = $status['error'] !== '';


Integration in extbase extension
--------------------------------

Rendering the captcha
~~~~~~~~~~~~~~~~~~~~~

For rendering the captcha in a fluid template there is a ViewHelper
that prepares the configuration and then renders the captcha.

.. code-block:: html

   <r:form.recaptcha>
      <f:if condition="{showCaptcha}">
         <f:then>
            <f:form.hidden property="{name}" value="1" />
            <div class="{configuration.captchaCssClass}" data-sitekey="{configuration.public_key}"></div>
         </f:then>
         <f:else>
            <div class="recaptcha-development-mode">
               Development mode active. Do not expect the captcha to appear.
            </div>
         </f:else>
      </f:if>
   </r:form.recaptcha>


Validation in model
~~~~~~~~~~~~~~~~~~~

After the form was submitted the validation of the form model is quite
easy. Just annotate as usual.

.. code-block:: php

    /**
     * virtual not stored in database
     *
     * @var string
     * @validate \Evoweb\Recaptcha\Validation\RecaptchaValidator
     */
    protected $captcha;

