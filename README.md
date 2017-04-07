recaptcha
=========

[![Build Status](https://travis-ci.org/evoWeb/recaptcha.svg?branch=master)](https://travis-ci.org/evoWeb/recaptcha)

TYPO3 Extension to make use of googles nocaptcha.
Now supports googles invisible reCAPTCHA.

To install the extension add "evoweb/recaptcha" to your project composer.json or download it from the TER https://typo3.org/extensions/repository/view/recaptcha and activate the extension in the extension manager.

## Integrate invisible recaptcha in tx_form typoscript forms

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

## Integrate invisible recaptcha in own forms

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

$captcha = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
	\Evoweb\Recaptcha\Services\CaptchaService::class
);
$status = $captcha->validateReCaptcha();

if ($status == false || $status['error'] !== '') {
	$validCaptcha = false;
}
```