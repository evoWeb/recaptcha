.. include:: /Includes.rst.txt

.. _introduction:

============
Introduction
============

This Documentation was written for version 8.2.0 of the extension.


Functionality
-------------

Only purpose is to render the reCAPTCHA and validate the response.
But provide these to other extensions which consumes the service.

Also there are limited integrations into other extensions implemented.
These act more as an living example and integrate only in extensions
used by the author self.

.. figure:: Images/recaptcha.png
   :alt: Show example of captcha output

Features
--------

As a very focused extension only the following features are integrated

 - service for display and validation
 - integration in form rendering of EXT:form
 - integration of invisible captcha in EXT:form
 - viewHelper for display in fluid templates
 - validator for usage in extbase domain model validation
 - adapter for in-/visible captcha in sf_register
 - adapter for in-/visible captcha in typoscript
