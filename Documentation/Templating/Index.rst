.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

Templating
==========

For templating there are only two interesting parts to consider. How
to use the ViewHelper and direct inclusion in custom extension are
the only parts to modify.


ViewHelper integration
----------------------

Using the ViewHelper has the benefit that it covers the hole configuration
discovery with fallback. Inside the ViewHelper three variables are set.


.. ##### BEGIN~OF~TABLE #####

.. container:: table-row

   Property
         name

   Data type
         string

   Default
         formName[formObject][captcha]

   Description
         This value depends heavily on fluids field name generation
         and will vary through out the templates where it gets used.


.. container:: table-row

   Property
         showCaptcha

   Data type
         bool

   Default
         true on Development and false on Production

   Description
         Contains whether the catpcha should be rendered at all and
         depends on the application mode.


.. container:: table-row

   Property
         configuration

   Data type
         array

   Default
         settings from TypoScript

   Description
         Provides the configuration set in TypoScript with fallback
         to extension configuration set in admin tools extensions.


.. ###### END~OF~TABLE ######

.. code-block:: html
   :caption: ViewHelper example integration

   <r:form.recaptcha>
      <f:if condition="{showCaptcha}">
         <f:then>
            <f:form.hidden property="{name}" value="1" />
            <div class="{configuration.captchaCssClass}" data-sitekey="{configuration.public_key}{f:if(condition: configuration.lang, then: '?hl=de')}"></div>
         </f:then>
         <f:else>
            <div class="recaptcha-development-mode">
               Development mode active. Do not expect the captcha to appear.
            </div>
         </f:else>
      </f:if>
   </r:form.recaptcha>


Integration in extension
------------------------

Here the extension delivers only limited settings. In TypoScript there
is the **public_key** defined which can be rendered as stdWrap to resolve
an div container with all needed information to output the captcha.

By setting **include_invisible_recaptcha_callback** in constants and
modifying the submit button of a form its possible to handle invisible
captcha.

Please keep in mind, that in both cases one need to integrate the php
sided validation. This is normally done by adding an hidden input to
the form and act on it if present.
