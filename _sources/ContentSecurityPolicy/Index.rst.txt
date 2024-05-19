.. include:: /Includes.rst.txt

.. _csp:

=======================
Content Security Policy
=======================


Reason
======

Since TYPO3 12 handling of content security policies are introduced. If this
feature is active, the recaptcha javascript can not be loaded without additional
configuration.


CSP Configuration
=================

To get the recaptcha working with csp feature active, it's necessary to add an
extending mutation to the site configuration in a csp.yaml named file.

..  code-block:: yaml
    :caption: project_root/config/sites/main/csp.yaml

    inheritDefault: true
    mutations:
      - mode: extend
        directive: 'frame-src'
        sources:
          - 'https://www.google.com/recaptcha/'
          - 'https://recaptcha.google.com/recaptcha/'

      - mode: extend
        directive: 'script-src'
        sources:
          - 'https://www.google.com/recaptcha/'
          - 'https://www.gstatic.com/recaptcha/'


Template modifications
======================

In previous releases some onclick javascript was used. While the CSP rules are
in place, this should be omitted. Some code was moved to the Frontend/form.js.
Other was replaced with hidden inputs.

If you override the Frontend/Partials/Form/Navigation.html please check if it's
in line with the provided file.
