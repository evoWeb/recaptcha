<?php
namespace TYPO3\CMS\Form\Validation;

/**
 * Class RecaptchaValidator
 */
class RecaptchaValidator extends AbstractValidator implements \TYPO3\CMS\Core\SingletonInterface {
	/**
	 * Captcha object
	 *
	 * @var \Evoweb\Recaptcha\Services\CaptchaService
	 */
	protected $captcha = NULL;

	/**
	 * @param array $arguments
	 * @return self
	 */
	public function __construct($arguments) {
		parent::__construct($arguments);

		$this->captcha = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Evoweb\\Recaptcha\\Services\\CaptchaService');
	}

	/**
	 * Validate the captcha value from the request and output an error if not valid
	 *
	 * @return bool
	 */
	public function isValid() {
		$validCaptcha = TRUE;

		if ($this->captcha !== NULL) {
			$status = $this->captcha->validateReCaptcha();

			if ($status == FALSE || $status['error'] !== '') {
				$validCaptcha = FALSE;
				$this->setError($status['error']);
			}
		}

		return $validCaptcha;
	}
}