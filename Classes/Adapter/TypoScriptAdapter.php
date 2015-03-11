<?php
namespace Evoweb\Recaptcha\Adapter;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Sebastian Fischer <typo3@evoweb.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class RecaptchaAdapter
 */
class TypoScriptAdapter {
	/**
	 * Captcha object
	 *
	 * @var \Evoweb\Recaptcha\Services\CaptchaService
	 */
	protected $captcha = NULL;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->captcha = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Evoweb\\Recaptcha\\Services\\CaptchaService');
	}

	/**
	 * Rendering the output of the captcha
	 *
	 * @return string
	 */
	public function render() {
		if ($this->captcha !== NULL) {
			$output = $this->captcha->getReCaptcha();
		} else {
			$output = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
				'error_captcha.notinstalled', 'Recaptcha', array('recaptcha')
			);
		}

		return $output;
	}

	/**
	 * Validate the captcha value from the request and output an error if not valid
	 *
	 * @return bool
	 */
	public function validate() {
		$validCaptcha = TRUE;

		if ($this->captcha !== NULL) {
			$status = $this->captcha->validateReCaptcha();

			if ($status == FALSE || $status['error'] !== '') {
				$validCaptcha = FALSE;
				$this->renderFlashMessage(
					\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error_recaptcha_' . $status['error'], 'Recaptcha'),
					1307421960
				);
			}
		}

		return $validCaptcha;
	}

	/**
	 * @param string $message
	 * @param int $type
	 * @throws \TYPO3\CMS\Core\Exception
	 */
	protected function renderFlashMessage($message, $type = \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING) {
		$code  = '
		.typo3-message .message-header{padding: 10px 10px 0 30px;font-size:0.9em;}
		.typo3-message .message-body{padding: 10px;font-size:0.9em;}
		';

		/**
		 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $frontend
		 */
		$frontend = $GLOBALS['TSFE'];
		$frontend->getPageRenderer()->addCssFile(
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('t3skin') . 'stylesheets/standalone/errorpage-message.css'
		);
		$frontend->getPageRenderer()->addCssInlineBlock('flashmessage', $code);

		/** @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
		$flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
			$message,
			'',
			$type
		);

		/** @var \TYPO3\CMS\Core\Messaging\FlashMessageQueue $flashMessageQueue */
		$flashMessageQueue = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Core\\Messaging\\FlashMessageQueue'
		);
		$flashMessageQueue->enqueue($flashMessage);
	}
}
