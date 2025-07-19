..  include:: /Includes.rst.txt
..  index:: Configuration
..  _configuration:

=============
Configuration
=============

Backend related
===============

If you like to use the recaptcha service in backend context, you have
to make sure that the settings are set in the admin tools extensions.

Frontend related
================

For all frontend usage of the recaptcha service the backend settings
are taken and then overridden with TypoScript. But setting the backend
portion is optional. If these are empty the TypoScript configuration is
mandatory.

Reference
=========

Following parameter are available as constants. And with the same
name also as setup parameter.

plugin.tx\_recaptcha:

..  confval-menu::
    :name: constants-reference
    :display: table
    :type:
    :Default:

    ..  _api_server:

    ..  confval:: api_server
        :type: :ref:`string <t3tsref:data-type-string>`
        :Default: https://www.google.com/recaptcha/api.js

        This url is used to include the basic frontend functionality.
        In general it's not necessary to modify this url. In case
        that the api changes and the extension is not updated quick
        enough changing this parameter keeps the frontend working.

    ..  _verify_server:

    ..  confval:: verify_server
        :type: :ref:`string <t3tsref:data-type-string>`
        :Default: https://www.google.com/recaptcha/api/siteverify

        Every verification uses this url to check if the captcha was solved.
        In general it's not necessary to modify this url. In case that the
        api changes and the extension is not updated quick enough changing
        this parameter keeps the validation working.

    ..  _public_key:

    ..  confval:: public_key
        :type: :ref:`string <t3tsref:data-type-string>`
        :Default: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI

        The public key is needed for the frontend rendering.

        Google provides a key for automatic testing purpose and this is used
        as default. To remove the warning that is caused by using the default
        key please go to the recaptcha admin_ and register an own key for use
        in your site.

    ..  _private_key:

    ..  confval:: private_key
        :type: :ref:`string <t3tsref:data-type-string>`
        :Default: 6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe

        The private key is needed for the validation of submits.

        Google provides a key for automatic testing purpose and this is used
        as default. To remove the warning that is caused by using the default
        key please go to the recaptcha admin_ and register an own key for use
        in your site.

    ..  _lang:

    ..  confval:: lang
        :type: :ref:`string <t3tsref:data-type-string>`

        Recaptcha is very good in automatic detecting which language to use
        based on the users browser settings and with which IP the page gets
        visited.
        Only in case if it is really necessary set this parameter to take
        influence on the rendered language.

    ..  _enforceCaptcha:

    ..  confval:: enforceCaptcha
        :type: :ref:`string <t3tsref:data-type-boolean>`
        :Default: 0

        This configuration parameter will enforce the captcha to test
        the functionality on development systems.
        EXT:recaptcha uses the application context to detect if an
        installation is running in Development mode. If so the captcha
        is not rendered or validated.
        This is a to reduce external calls and b to not bother the
        integrator every time he has to test forms. Because after a
        limited amount of tests the captcha testing games must always
        be solved instead only clicking a checkbox.

    ..  _theme:

    ..  confval:: theme
        :type: :ref:`string <t3tsref:data-type-string>`
        :Default: light
        :Possible values: light, dark

        [Only TypoScript]
        For change the theme for recaptcha (light or dark).

    ..  _robotMode:

    ..  confval:: robotMode
        :type: :ref:`string <t3tsref:data-type-boolean>`
        :Default: 0

        [Only TypoScript]
        Add the possibility to set the recaptcha into robot mode in production environment.
        The recaptcha will not be displayed like in development mode.

    ..  _threshold:

    ..  confval:: threshold
        :type: :ref:`string <t3tsref:data-type-boolean>`
        :Default: 0

        [Only TypoScript]
        Add the threshold for recaptcha v3 from which to show an error.

..  _admin: https://www.google.com/recaptcha/admin
