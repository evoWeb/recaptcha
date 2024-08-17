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
import * as Helper from '@typo3/form/backend/form-editor/helper.js';

export function bootstrap(formEditorApp) {
  Helper.bootstrap(formEditorApp);

  formEditorApp.getPublisherSubscriber().subscribe(
    'view/stage/abstract/render/template/perform',
    /**
     * @param {string} topic
     * @param {[FormElement,JQuery]} args
     * @return {void}
     */
    (topic, args) => {
      if (args[0].get('type') === 'Recaptcha') {
        formEditorApp
          .getViewModel()
          .getStage()
          .renderSimpleTemplateWithValidators(args[0], args[1]);
      }
    }
  );
}
