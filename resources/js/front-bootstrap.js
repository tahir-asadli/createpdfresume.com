import Alpine from "alpinejs";

import { Fancybox } from "@fancyapps/ui";
import Splide from "@splidejs/splide";

import Glide from "@glidejs/glide";
// import SwipeListener from "swipe-listener";

// window.SwipeListener = SwipeListener;
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
window.Alpine = Alpine;
window.Glide = Glide;
window.Splide = Splide;

Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("hamburger")
        .addEventListener("click", function (e) {
            document.querySelector("body").classList.toggle("mmo");
        });
    document
        .getElementById("mobile-menu-overlay")
        .addEventListener("click", function (e) {
            document.querySelector("body").classList.remove("mmo");
        });
    document
        .getElementById("mobile-menu-overlay")
        .addEventListener("click", function (e) {
            document.querySelector("body").classList.remove("mmo");
        });
    const buttons = document.querySelectorAll(".close-mobile-menu");
    Object.keys(buttons).forEach((index) => {
        const button = buttons[index];
        button.addEventListener("click", function (e) {
            document.querySelector("body").classList.remove("mmo");
        });
    });

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
