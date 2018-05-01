.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

Configuration
=============

Backend related
---------------

If you like to use the recaptcha service in backend context, you have
to make sure that the settings are set in the admin tools extensions.


Frontend related
----------------

For all frontend usage of the recaptcha service the backend settings
are taken and then overridden with TypoScript. But setting the backend
portion is optional. If these are empty the TypoScript configuration is
mandatory.


Reference
---------

Following parameter are available as constants. And with the same
name also as setup parameter.

plugin.tx\_recaptcha:

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         api_server

   Data type
         string

   Default
         https://www.google.com/recaptcha/api.js

   Description
         This url is used to include the basic frontend functionality.
         In general it's not necessary to modify this url. In case
         that the api changes and the extension is not updated quick
         enough changing this parameter keeps the frontend working.


.. container:: table-row

   Property
         verify_server

   Data type
         string

   Default
         https://www.google.com/recaptcha/api/siteverify

   Description
         Every verification uses this url to check if the captcha was
         solved.
         In general it's not necessary to modify this url. In case
         that the api changes and the extension is not updated quick
         enough changing this parameter keeps the validation working.


.. container:: table-row

   Property
         public_key

   Data type
         string

   Default
         6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI

   Description
         The public key is needed for the frontend rendering.

         Google provides a key for automatic testing purpose and this
         is used as default.
         To remove the warning that is caused by using the default key
         please go to the recaptcha admin_ and register an own key for
         use in your site.


.. container:: table-row

   Property
         private_key

   Data type
         string

   Default
         6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe

   Description
         The public key is needed for the validation of submits.

         Google provides a key for automatic testing purpose and this
         is used as default.
         To remove the warning that is caused by using the default key
         please go to the recaptcha admin_ and register an own key for
         use in your site.


.. container:: table-row

   Property
         lang

   Data type
         string

   Default
         empty

   Description
         Recaptcha is very good in automatic detecting which language
         to use based on the users browser settings and with which IP
         the page get's visited.
         Only in case if it is really necessary set this parameter to
         take influence on the rendered language.


.. container:: table-row

   Property
         enforceCaptcha

   Data type
         bool

   Default
         0


   Description
         This configuration parameter will enforce the captcha to test
         the functionality on development systems.
         EXT:recaptcha uses the application context to detect if an
         installation is running in Development mode. If so the captcha
         is not rendered or validated.
         This is a to reduce external calls and b to not bother the
         integrator every time he has to test forms. Because after a
         limited amount of tests the captcha testing games must always
         be solved instead only clicking a checkbox.


.. container:: table-row

   Property
         include_invisible_recaptcha_callback

   Data type
         bool

   Default
         0

   Description
         [Only TypoScript][Only as constant]
         The latest iteration of EXT:recaptcha is capable of integrating
         the invisible mode of reCAPTCHA. To be able to submit forms
         after the captcha was solved a JavaScript callback function is
         needed. To get this included either activate this parameter or
         have a look at the extensions setup.txt and copy the javascript
         part to a place of your liking.

   Example
         .. code-block:: typoscript
            :caption: Add callback for invisible reCAPTCHA as inline JavaScript

            page.jsInline.recaptcha = TEXT
            page.jsInline.recaptcha.value (
               function onRecaptchaSubmit() {
                  document.getElementById('contactform').submit();
                  return false;
               }
            )


         By this the method onRecaptchaSubmit gets inserted as inline JavaScript.


.. container:: table-row

   Property
         invisibleCallback

   Data type
         string

   Default
         onRecaptchaSubmit

   Description
         [Only TypoScript]
         For invisible reCAPTCHA its necessary to have a callback
         function in JavaScript. It is possible to have own code
         that derives from the included. If in this case the name
         of the callback is changed please adjust this parameter
         to match the new name.


.. container:: table-row

   Property
         captchaCssClass

   Data type
         string

   Default
         g-recaptcha

   Description
         [Only TypoScript]
         By default google detects with this css class the contianer
         to initialize the inclusion of the captcha. So removing
         the value would break it.
         But its possible to have additional css classes attached
         to the captcha container for styling purposes.


.. ###### END~OF~TABLE ######

.. _admin: https://www.google.com/recaptcha/admin
