..  include:: /Includes.rst.txt
..  index:: Templating
..  _templating:

==========
Templating
==========

For templating there are only two interesting parts to consider. How
to use the ViewHelper and direct inclusion in custom extension are
the only parts to modify.

ViewHelper integration
======================

Using the ViewHelper has the benefit that it covers the hole configuration
discovery with fallback. Inside the ViewHelper three variables are set.

..  confval-menu::
    :name: viewhelper-attributes
    :display: table
    :type:
    :Default:

    ..  _name-attributes:

    ..  confval:: name
        :type: :ref:`string <t3tsref:data-type-string>`
        :Default: formName[formObject][captcha]

         This value depends heavily on fluids field name generation
         and will vary through out the templates where it gets used.

    ..  _showCaptcha-attributes:

    ..  confval:: showCaptcha
        :type: :ref:`boolean <t3tsref:data-type-boolean>`
        :Default: true on Development and false on Production

         Contains whether the catpcha should be rendered at all and
         depends on the application mode.

    ..  _configuration-attributes:

    ..  confval:: configuration
        :type: array
        :Default: settings from TypoScript

         Provides the configuration set in TypoScript with fallback
         to extension configuration set in admin tools extensions.

ViewHelper usage
================

..  code-block:: html
    :caption: ViewHelper example integration

    <r:form.recaptcha>
      <f:if condition="{showCaptcha}">
        <f:then>
          <f:form.hidden property="{name}" value="1" />
          <div class="g-recaptcha" data-sitekey="{configuration.public_key}"></div>
        </f:then>
        <f:else>
          <div class="recaptcha-development-mode">
            Development mode active. Do not expect the captcha to appear.
          </div>
        </f:else>
      </f:if>
    </r:form.recaptcha>

Integration in extension
========================

Here the extension delivers only limited settings. In TypoScript there
is the **public_key** defined which can be rendered as stdWrap to resolve
an div container with all needed information to output the captcha.
