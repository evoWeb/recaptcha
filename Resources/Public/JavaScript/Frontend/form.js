class Recaptcha {
  /**
   * @type {string}
   */
  fieldSelector = '[data-recaptcha-form-field]';

  /**
   * @type {HTMLFormElement}
   */
  form = null;

  /**
   * @type {HTMLInputElement}
   */
  field = null;

  /**
   * @type {string}
   */
  response = '';

  /**
   * @param {HTMLFormElement} form
   */
  constructor(form) {
    this.initializeElements(form);
    this.initializeEvents();
    this.registerCallback();
  }

  /**
   * @param {HTMLFormElement} form
   */
  initializeElements(form) {
    this.form = form;
    this.field = this.form.querySelector(this.fieldSelector);
  }

  initializeEvents() {
    // used with visible recaptcha
    [...this.form.querySelectorAll('[data-recaptcha-form-submit]')]
      .map(button => button.addEventListener('click', event => this.visibleRecaptchaButtonClicked(event)));
  }

  /**
   * @param {PointerEvent} event
   */
  visibleRecaptchaButtonClicked(event) {
    if (!this.form.checkValidity() || !this.recaptchaFieldValid()) {
      event.preventDefault();
    }
  }

  /**
   * @return {boolean}
   */
  recaptchaFieldValid() {
    return this.field.value !== '' && this.field.value === this.response;
  }

  registerCallback() {
    // for box recaptcha
    window.onRecaptchaCallback = (response) => this.callback(response);
    window.onRecaptchaExpired = () => this.recaptchaExpired();
    window.onRecaptchaError = () => this.recaptchaError();
    // for invisible recaptcha
    window.onRecaptchaSubmit = (response) => this.submitForm(response);
  }

  /**
   * @param {string} response
   */
  submitForm(response) {
    this.response = response;
    if (this.form.checkValidity()) {
      this.field.value = response;
      this.form.submit();
    }
  }

  /**
   * @param {string} response
   */
  callback(response) {
    this.response = response;
    this.field.value = response;
  }

  recaptchaExpired() {
    this.response = '';
    this.field.value = '';
  }

  recaptchaError() {
    this.response = '';
    this.field.value = '';
  }

  static initializeForms() {
    [...document.querySelectorAll('form .g-recaptcha')]
      .map(element => {
        const form = element.closest('form');
        new Recaptcha(form);
      })
  }
}
Recaptcha.initializeForms();
