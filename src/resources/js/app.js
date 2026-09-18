import "./bootstrap";
import "@wotz/livewire-sortablejs";
// import 'preline'
import TAUploader from "./uploader";
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";

// The DOM model
// import "hugerte/models/dom";

// The default icons. This is required, but you can import custom icons after it.
import hugerte from "hugerte";
import "hugerte/icons/default";
import "hugerte/models/dom";

// The oxide skin (or you can use a custom one).
import "hugerte/skins/ui/oxide/skin.js";
// The content skin provided by oxide (or a different skin you're using).
import "hugerte/skins/ui/oxide/content.js";
// The default content CSS. This can also be replaced by a custom file if needed.
import "hugerte/skins/content/default/content.js";
import "hugerte/themes/silver";

import "hugerte/plugins/advlist";
import "hugerte/plugins/anchor";

import "hugerte/plugins/link";

import "hugerte/plugins/lists";

window.hugerte = hugerte;
window.TAUploader = TAUploader;
Fancybox.bind("[data-fancybox]", {});

window.initInputLimitCounter = function () {
    const inputs = document.querySelectorAll(".has-limit");

    Object.keys(inputs).forEach(function (index) {
        const el = inputs[index];
        const limit = el.getAttribute("limit");
        const charsEl = el.querySelector(".chars");
        const inputEl = el.querySelector("input");
        const textareaEl = el.querySelector("textarea");
        if (charsEl) {
            console.log("charsEl", charsEl);
            function update(length) {
                charsEl.innerHTML = length;
                if (length > limit) {
                    el.classList.add("text-rose-500");
                } else {
                    el.classList.remove("text-rose-500");
                }
            }
            if (inputEl) {
                inputEl.addEventListener("keyup", function (e) {
                    update(e.target.value.length);
                });

                inputEl.addEventListener("change", function (e) {
                    update(e.target.value.length);
                });
            }
            if (textareaEl) {
                console.log("textareaEl", textareaEl);
                textareaEl.addEventListener("keyup", function (e) {
                    update(e.target.value.length);
                });

                textareaEl.addEventListener("change", function (e) {
                    console.log("keyup", textareaEl);
                    update(e.target.value.length);
                });
            }
        }
    });
};

document.addEventListener("DOMContentLoaded", function () {
    const toggleButtons = document.querySelectorAll(
        ".dashboard-settings-menu-toggle",
    );

    toggleButtons.forEach((toggleButton) => {
        toggleButton.addEventListener("click", function (e) {
            document.querySelector("body").classList.toggle("bmo");
        });
    });
    const dashboardToggleButtons = document.querySelectorAll(
        ".dashboard-menu-toggle",
    );

    dashboardToggleButtons.forEach((toggleButton) => {
        toggleButton.addEventListener("click", function (e) {
            document.querySelector("body").classList.toggle("dmo");
        });
    });

    // document
    //     .getElementById("mobile-menu-overlay")
    //     .addEventListener("click", function (e) {
    //         document.querySelector("body").classList.remove("bmo");
    //     });
    // document
    //     .getElementById("mobile-menu-overlay")
    //     .addEventListener("click", function (e) {
    //         document.querySelector("body").classList.remove("bmo");
    //     });
    // const buttons = document.querySelectorAll(".close-mobile-menu");
    // Object.keys(buttons).forEach((index) => {
    //     const button = buttons[index];
    //     button.addEventListener("click", function (e) {
    //         document.querySelector("body").classList.remove("bmo");
    //     });
    // });

    // const uploader = new window.TAUploader({
    //     fileInputId: "sekil",
    //     url: `/uploadPropertyImage/${_self.propertyId}`,
    //     headers: {
    //         "X-CSRF-TOKEN": _self.csrfToken,
    //     },
    //     started: (fileList) => {
    //         fileListOverlayEl.style.display = "block";
    //         const fileNames = [];
    //         if (fileListEl) {
    //             for (let i in fileList) {
    //                 const fileObj = fileList[i];
    //                 const fileEl = fileListEl.querySelector(
    //                     `#file-${fileObj.id}`,
    //                 );
    //                 if (!fileEl) {
    //                     const reader = new FileReader();
    //                     fileNames.push(`${fileObj.file.name}#${fileObj.id}`);
    //                     console.log(`${fileObj.file.name}#${fileObj.id}`);

    //                     reader.onload = function (e) {
    //                         fileListEl.innerHTML += `<div class="item" id="file-${fileObj.id}"><span class=""><b class="">0%</b></span><img style="" src="${e.target.result}" />
    //                                        <button type="button">&times;</button></div>`;
    //                     };
    //                     reader.readAsDataURL(fileObj.file);
    //                 }
    //             }
    //         }
    //     },
    //     ended: () => {
    //         fileListOverlayEl.style.display = "none";
    //     },
    //     progress: (fileId, percent, event) => {
    //         const fileEl = fileListEl.querySelector(`#file-${fileId}`);
    //         if (fileEl) {
    //             fileEl.classList.add("uploading");
    //             const imgEl = fileEl.querySelector("img");
    //             const percentEl = fileEl.querySelector("b");
    //             percentEl.innerHTML = `${percent}%`;
    //         }
    //     },
    //     completed: (fileId, message, event) => {
    //         const fileEl = fileListEl.querySelector(`#file-${fileId}`);
    //         if (fileEl) {
    //             try {
    //                 const imgEl = fileEl.querySelector("img");
    //                 if (imgEl) {
    //                     const jsonResponse = JSON.parse(event.target.response);

    //                     let path = jsonResponse?.path
    //                         ? jsonResponse?.path
    //                         : null;
    //                     let order = jsonResponse?.order
    //                         ? jsonResponse?.order
    //                         : null;
    //                     let id = jsonResponse?.id ? jsonResponse?.id : null;
    //                     if (path) {
    //                         imgEl.src = `/${path}`;
    //                         fileEl.setAttribute("data-order", order);
    //                         fileEl.setAttribute("data-id", id);
    //                     }
    //                 }
    //             } catch (error) {
    //                 console.error("Could not read json response", error);
    //             }
    //             fileEl.classList.remove("uploading");
    //             fileEl.classList.add("uploaded");
    //         }
    //     },
    //     error: (fileId, message, event) => {
    //         const fileEl = fileListEl.querySelector(`#file-${fileId}`);
    //         if (fileEl) {
    //             fileEl.classList.add("hasError");
    //             fileEl.classList.remove("uploaded");
    //             fileEl.classList.remove("uploading");
    //             fileEl.setAttribute("title", message);
    //         }
    //     },
    // });
});
