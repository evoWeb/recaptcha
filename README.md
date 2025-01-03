# TYPO3 Extension ``recaptcha``

![build](https://github.com/evoWeb/recaptcha/actions/workflows/ci.yml/badge.svg?branch=develop)
[![Latest Stable Version](https://poser.pugx.org/evoweb/recaptcha/v/stable)](https://packagist.org/packages/evoweb/recaptcha)
[![Monthly Downloads](https://poser.pugx.org/evoweb/recaptcha/d/monthly)](https://packagist.org/packages/evoweb/recaptcha)
[![Total Downloads](https://poser.pugx.org/evoweb/recaptcha/downloads)](https://packagist.org/packages/evoweb/recaptcha)

TYPO3 Extension to make use of googles nocaptcha.\
Now supports googles invisible reCAPTCHA.

## Installation

### via Composer

The recommended way to install TYPO3 Console is by using [Composer](https://getcomposer.org):

    composer require evoweb/recaptcha

### via TYPO3 Extension Repository

Download and install the extension with the extension manager module or directly from the
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

After modify your form output by replacing the submit button with something like this:

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

After that you're ready on the frontend but still need to call the validation in your php code.

```
$validCaptcha = false;

$captchaService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Evoweb\Recaptcha\Services\CaptchaService::class);
$captchaServiceValidation = $captchaService->validateReCaptcha();
if (isset($captchaServiceValidation['verified'])) {
	if ($captchaServiceValidation['verified'] === true) {
		$validCaptcha = true;
	}
}
```
