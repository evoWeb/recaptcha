..  include:: /Includes.rst.txt
..  index:: Content Security Policy
..  _csp:

=======================
Content Security Policy
=======================

Reason
======

Since TYPO3 12 handling of content security policies are introduced. Extension adds required policies automatically.

Template modifications
======================

In previous releases some onclick javascript was used. While the CSP rules are
in place, this should be omitted. Some code was moved to the Frontend/form.js.
Other was replaced with hidden inputs.

If you override the Frontend/Partials/Form/Navigation.html please check if it's
in line with the provided file.
