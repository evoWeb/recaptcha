.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

Introduction
============

This Documentation was written for version 8.2.0 of the extension.


What does it do?
----------------


Provide reCAPTCHA functionality:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Only purpose is to render the reCAPTCHA and validate the response.
But provide this to other extensions which consumes the service. To
do so there are limited integrations into other extensions implemented.
These act more as an living example and integrate only in extensions
used by the author self.

Features:
~~~~~~~~~

As a very focused extension only the following features are integrated

 - service for display and validation
 - integration in form rendering of EXT:form
 - integration of invisible captcha in EXT:form
 - viewHelper for display in fluid templates
 - validator for usage in extbase domain model validation
 - adapter for in-/visible captcha in sf_register
 - adapter for in-/visible captcha in typoscript
