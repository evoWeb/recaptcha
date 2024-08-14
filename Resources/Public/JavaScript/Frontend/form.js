class Recaptcha {
  currentForm = null;

  recaptchaFieldSelector = '[data-recaptcha-form-field]';

  recaptchaField = null;

  constructor() {
    this.initializeEvents();
    this.registerCallback();
  }

  initializeEvents() {
    // used with visible recaptcha
    [...document.querySelectorAll('[data-recaptcha-form-submit]')]
      .map(button => button.addEventListener('click', event => this.visibleRecaptchaButtonClicked(event)));

    // used with invisible recaptcha
    [...document.querySelectorAll('[data-callback="onRecaptchaSubmit"]')]
      .map(button => button.addEventListener('click', event => this.invisibleRecaptchaButtonClicked(event)));
  }

  visibleRecaptchaButtonClicked(event) {
    let form = event.target.closest('form');
    if (form.checkValidity()) {
      event.preventDefault();
      form.submit();
    }
  }

  invisibleRecaptchaButtonClicked(event) {
    if (this.currentForm === null) {
      this.currentForm = event.target.closest('form');
      this.recaptchaField = this.currentForm.querySelector(this.recaptchaFieldSelector);
    }
  }

  registerCallback() {
    window.onRecaptchaSubmit = (response) => this.submitCurrentForm(response);
    window.onRecaptchaCallback = (response) => this.recaptchaConfirmed(response);
    window.onRecaptchaExpired = () => this.recaptchaExpired();
    window.onRecaptchaError = () => this.recaptchaError();
  }

  submitCurrentForm(response) {
    if (this.currentForm.checkValidity()) {
      this.recaptchaField.value = response;
      this.currentForm.submit();
      this.currentForm = null;
    }
  }

  recaptchaConfirmed(response) {
    [...document.querySelectorAll(this.recaptchaFieldSelector)].map(field => field.value = response);
  }

  recaptchaExpired() {
    [...document.querySelectorAll(this.recaptchaFieldSelector)].map(field => field.value = '');
  }

  recaptchaError() {
    [...document.querySelectorAll(this.recaptchaFieldSelector)].map(field => field.value = '');
  }
}
new Recaptcha();
