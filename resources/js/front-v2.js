class MobileMenu extends HTMLElement {
  constructor() {
    super();
    this.openDialog = this.openDialog.bind(this)
    this.dialogToggled = this.dialogToggled.bind(this)
    this.button = null;
  }

  openDialog(e) {
    this.dialog.showModal()
  }

  dialogToggled(event) {
    if (this.dialog.open) {
      this.body.classList.add('dialog-open')
    } else {
      this.body.classList.remove('dialog-open')
    }
  }

  connectedCallback() {
    this.button = document.getElementById('mobile-button')
    this.body = document.querySelector('body')
    this.dialog = this.querySelector('dialog')
    this.button?.addEventListener('click', this.openDialog);
    this.dialog?.addEventListener("toggle", this.dialogToggled);
  }
  disconnectedCallback() {

  }
}

customElements.define('mobile-menu', MobileMenu);



import { Fancybox } from "@fancyapps/ui";

import "@fancyapps/ui/dist/fancybox/fancybox.css";
Fancybox.bind("[data-fancybox]", {
  Html: {
    youtube: {
      autoplay: 1,
      rel: 0,
      modestbranding: 1,
      vq: "hd1080",
    },
  },
});




document.addEventListener("DOMContentLoaded", function () {

  const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
  const uuidToken = document
    .querySelector('meta[name="uuid-token"]')
    .getAttribute("content");
  function ping() {
    if (uuidToken == "") {
      return;
    }
    fetch("/ping", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": csrfToken,
        "X-UUID-TOKEN": uuidToken,
      },
    });
  }
  const FIRST_PING_TIMEOUT = 1000;
  const SECOND_PING_TIMEOUT = 1000 * 60;
  const THIRD_PING_TIMEOUT = 1000 * 60 * 10;
  setTimeout(() => {
    ping();
  }, FIRST_PING_TIMEOUT);
  setTimeout(() => {
    ping();
  }, SECOND_PING_TIMEOUT);
  setTimeout(() => {
    ping();
  }, THIRD_PING_TIMEOUT);

  window.addEventListener("beforeunload", (event) => {
    ping();
  });
  document
    .querySelector(".language-switcher button")
    ?.addEventListener("click", function (e) {
      e.target.parentNode.classList.toggle("open");
    });
});
