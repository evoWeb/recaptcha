/*
 * This file is developed by evoWeb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Module: @evoweb/recaptcha/backend/form-editor/view-model.js
 */

import $ from 'jquery';
import * as Helper from '@typo3/form/backend/form-editor/helper.js';

const {
  bootstrap
} = factory($, Helper);

export {
  bootstrap
};

function factory($, Helper) {
  return (function ($, Helper) {

    /**
     * @private
     *
     * @var object
     */
    var _formEditorApp = null;

    /* *************************************************************
     * Private Methods
     * ************************************************************/

    /**
     * @private
     *
     * @return void
     * @throws 1478268638
     */
    function _helperSetup() {
      assert('function' === $.type(Helper.bootstrap),
        'The view model helper does not implement the method "bootstrap"',
        1478268638
      );

      Helper.bootstrap(getFormEditorApp());
    }

    /**
     * @public
     *
     * @return object
     */
    function getFormEditorApp() {
      return _formEditorApp;
    }

    /**
     * @private
     *
     * @param {boolean} test
     * @param {string} message
     * @param {int} messageCode
     * @return object
     */
    function assert(test, message, messageCode) {
      return getFormEditorApp().assert(test, message, messageCode);
    }

    /**
     * @private
     *
     * @return object
     */
    function getPublisherSubscriber() {
      return getFormEditorApp().getPublisherSubscriber();
    }

    /**
     * @private
     *
     * @return void
     */
    function _subscribeEvents() {
      /**
       * @private
       *
       * @param string
       * @param array
       *              args[0] = formElement
       *              args[1] = template
       *
       * @return void
       */
      getPublisherSubscriber().subscribe('view/stage/abstract/render/template/perform', function (topic, args) {
        if (args[0].get('type') === 'Recaptcha') {
          getFormEditorApp().getViewModel().getStage().renderSimpleTemplateWithValidators(args[0], args[1]);
        }
      });
    }

    /* *************************************************************
     * Public Methods
     * ************************************************************/

    /**
     * @public
     *
     * @param {object} formEditorApp
     * @return void
     */
    function bootstrap(formEditorApp) {
      _formEditorApp = formEditorApp;

      _helperSetup();
      _subscribeEvents();
    }

    /**
     * Implements the "Revealing Module Pattern".
     */
    return {
      /**
       * Publish the public methods.
       */
      bootstrap: bootstrap
    };
  })($, Helper);
}
