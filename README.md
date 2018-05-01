recaptcha
=========

[![Build Status](https://travis-ci.org/evoWeb/recaptcha.svg?branch=master)](https://travis-ci.org/evoWeb/recaptcha)

TYPO3 Extension to make use of googles nocaptcha.
Now supports googles invisible reCAPTCHA.

## Installation

### via Composer

Its recommended to install the extension via composer. Either add it to your composer.json
in the TYPO3 project root or in the project root just enter 

composer require evoweb/recaptcha

### via TYPO3 Extension Repository

Download and install the extension with the extension manger module or directly from the
[TER](https://extensions.typo3.org/extension/recaptcha/).


## Integrate invisible reCAPTCHA in tx_form typoscript forms

To be able to use the captcha add the static include of this extension to your template.

After that, add in the typoscript of the form.

```
lib.contactForm = FORM
lib.contactForm {
	70 < lib.invisibleRecaptchaIntegration.10

	rules {
		7 < lib.invisibleRecaptchaIntegration.rules.1
	}
}
```

## Integrate invisible reCAPTCHA in own forms

To be able to use the captcha add the static include of this extension to your template.

Afterwards modify your form output by replacing the submit button with something like this:

```
<button 
	data-sitekey="6LfmFxQUAAAAAGiMRvzLHGYQ8KiQiqgBuY5NswDz"
	data-callback="onContactformCaptchaSubmit"
	class="g-recaptcha"
	type="button" name="tx_form_form[tx_form][id-11]"
	value="absenden">
		absenden
</button>
```

After that your are ready on the frontend but still need to call the validation in your php code.

```
$validCaptcha = true;

$status = \Evoweb\Recaptcha\Services\CaptchaService::getInstance()->validateReCaptcha();

if ($status == false || $status['error'] !== '') {
	$validCaptcha = false;
}
```
