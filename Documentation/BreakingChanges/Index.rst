.. include:: /Includes.rst.txt

.. _breaking-changes:

================
Breaking Changes
================

12. October 2024
================

The possibility for configuring extra keys for invisible recaptcha was added. For
this to work, the partials needed to be modified. If you override the partials,
please check that they contain the changes.

In addition it was made possible to have more than one form with recaptcha on
a page. These needed a complete refactoring of form.js for the frontend. Check
that the inclusion via f:asset.script is the same in your site.

Lastly the inclusion of the recaptcha.js was change from TypoScript includeFooterJs
to f:asset.script. By this only on page with a form that is protected with
recaptcha, the js file gets loaded. For this to work, you need to synchronize
the partials if you override them.


13. August 2023
===============

Partials
--------

Frontend partials have be changed. If modified check if your partials on changed
made to hidden fields.

Configuration removed
---------------------

TypoScript setup
 - invisibleCallback
 - captchaCssClass

TypoScript constants
 - invisible_recaptcha_formname
 - include_invisible_recaptcha_callback

Remove support for EXT:formhandler
