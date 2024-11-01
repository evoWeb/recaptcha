..  include:: /Includes.rst.txt
..  index:: Tutorial
..  _tutorial:

========
Tutorial
========

Use invisible recaptcha in EXT:form forms
=========================================

To use the invisible captcha in the form you need to add the advanced
element "reCAPTCHA". This field has the validation automatically added.
Next step is to open the "Settings" of the form and need to check the
"Use invisible recaptcha".

At last you need to add the TypoScript constants. One is to enable to
the inclusion of the invisible recaptcha callback and the other is to
tell the callback which id the form to handle has.

..  code-block:: typoscript
    :caption: EXT:site_package/Configuration/TypoScript/setup.typoscript

    plugin.tx_recaptcha {
        include_invisible_recaptcha_callback = 1
        invisible_recaptcha_formname = captchaForm-4
    }
