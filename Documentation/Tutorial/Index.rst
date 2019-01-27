.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

Tutorial
========

Use invisible recaptcha in EXT:form forms
-----------------------------------------

To use the invisible captcha in the form you need to add the advanced
element "reCAPTCHA". This field has the validation automatically added.
Next step is to open the "Settings" of the form and need to check the
"Use invisible recaptcha".

At last you need to add the TypoScript constants. One is to enable to
the inclusion of the invisible recaptcha callback and the other is to
tell the callback which id the form to handle has.

.. code-block:: typoscript
    :linenos:

    plugin.tx\_recaptcha {
        include\_invisible\_recaptcha\_callback = 1
        invisible\_recaptcha\_formname = captchaForm-4
    }
