class RecaptchaForm {
  /**
   * @type {string}
   */
  #fieldSelector = '[data-recaptcha-form-field]';

  #submitSelector = '[data-recaptcha-form-submit]';

  #invisibleSubmitSelector = '[data-invisible-recaptcha-form-submit]';

  /**
   * @type {Recaptcha}
   */
  #recaptcha = null;

  /**
   * @type {HTMLFormElement}
   */
  #form = null;

  /**
   * @type {HTMLInputElement}
   */
  #field = null;

  /**
   * @param {Recaptcha} recaptcha
   * @param {HTMLFormElement} form
   */
  constructor(recaptcha, form) {
    this.#recaptcha = recaptcha;
    this.#form = form;

    this.initializeElements(form);
    this.initializeEvents();
  }

  /**
   * @param {HTMLFormElement} form
   */
  initializeElements(form) {
    this.#field = form.querySelector(this.#fieldSelector);
  }

  initializeEvents() {
    // used with visible recaptcha
    [...this.#form.querySelectorAll(this.#submitSelector)]
      .map(button => button.addEventListener('click', event => this.visibleRecaptchaButtonClicked(event)));
    // used with invisible recaptcha
    [...this.#form.querySelectorAll(this.#invisibleSubmitSelector)]
      .map(button => button.addEventListener('click', () => this.#recaptcha.setLastForm(this)));
  }

  /**
   * @param {PointerEvent} event
   */
  visibleRecaptchaButtonClicked(event) {
    if (!this.#form.reportValidity() || !this.fieldValid()) {
      event.preventDefault();
    }
  }

  /**
   * @return {boolean}
   */
  fieldValid() {
    return this.#field.value !== '' && this.#field.value === this.#recaptcha.getResponse();
  }

  setFieldValue(value) {
    this.#field.value = value;
  }

  /**
   * @return {boolean}
   */
  reportValidity() {
    return this.#form.reportValidity();
  }

  submit() {
    this.#form.submit();
  }
}

class Recaptcha {
  /**
   * @type {RecaptchaForm[]}
   */
  #forms = [];

  /**
   * @type {RecaptchaForm}
   */
  #lastForm = null;

  /**
   * @type {string}
   */
  #response = '';

  constructor() {
    this.initializeForms();
    this.registerCallback();
  }

  initializeForms() {
    [...document.querySelectorAll('form .g-recaptcha')]
      .map(element => {
        const form = element.closest('form');
        if (form) {
          this.#forms.push(new RecaptchaForm(this, form));
        }
      })
  }

  registerCallback() {
    // for box recaptcha
    window.onRecaptchaCallback = (response) => this.recaptchaCallback(response);
    window.onRecaptchaExpired = () => this.recaptchaExpired();
    window.onRecaptchaError = () => this.recaptchaError();
    // for invisible recaptcha
    window.onRecaptchaSubmit = (response) => this.recaptchaSubmit(response);
  }

  /**
   * @param {string} response
   */
  recaptchaCallback(response) {
    this.#response = response;
    this.#forms.forEach(form => form.setFieldValue(response));
  }

  recaptchaExpired() {
    this.#lastForm = null;
    this.#response = '';
    this.#forms.forEach(form => form.setFieldValue(''));
  }

  recaptchaError() {
    this.#lastForm = null;
    this.#response = '';
    this.#forms.forEach(form => form.setFieldValue(''));
  }

  /**
   * @param {string} response
   */
  recaptchaSubmit(response) {
    this.#response = response;
    if (this.#lastForm && this.#lastForm.reportValidity()) {
      this.#lastForm.setFieldValue(response);
      this.#lastForm.submit();
    }
  }

  /**
   * @returns {string}
   */
  getResponse() {
    return this.#response;
  }

  setLastForm(lastForm) {
    this.#lastForm = lastForm;
  }
}
new Recaptcha();
