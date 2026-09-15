document.addEventListener("DOMContentLoaded", function () {
    // Globals
    let selectedSection = null;
    let sortTimeout = null;
    let settingsTimeout = null;
    const settingsTimeoutSeconds = 200;
    const refreshSectionsSeconds = 100;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const resumeUUID = document
        .querySelector('meta[name="resume-uuid"]')
        .getAttribute("content");
    const changeEvent = new Event("change", { bubbles: true });
    // HTML Elements
    const rootBody = document.querySelector("body");
    const templateCanvas = document.querySelector(".new-builder-canvas");
    const popUpOverlay = document.querySelector(
        ".block-popup .block-popup-overlay",
    );
    const blockSettingsPopUp = document.querySelector(".block-settings-popup");
    const blockSettingsPopUpContent = blockSettingsPopUp?.querySelector(
        ".block-settings-popup-content",
    );
    const blockSettingsPopUpOverlay = document.querySelector(
        ".block-settings-popup .block-settings-popup-overlay",
    );

    // const toggleStylesContainer = document.querySelector(
    //     ".toggle-styles-container"
    // );
    // toggleStylesContainer?.addEventListener("click", () => {
    //     const stylesContainer = document.querySelector(".styles-container");
    //     stylesContainer?.classList.toggle("show");
    // });

    const stylesContainerButtons = document.querySelectorAll(
        ".styles-container button",
    );
    stylesContainerButtons?.forEach((button) => {
        button.addEventListener("click", (e) => {
            stylesContainerButtons.forEach((sb) => {
                sb.classList.remove("active");
            });
            const styleSlug = button.getAttribute("data-style");
            const data = {};
            data["resumeUUID"] = resumeUUID;
            data["styleSlug"] = styleSlug;
            const url = "/dashboard/switch-resume-style";
            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    if (data.status == "success") {
                        const callback = () => {
                            initBlockSorting();
                            refreshSections();
                            button.classList.add("active");
                        };
                        renderTemplate(callback);
                    } else {
                    }
                    // refreshSections();
                });
        });
    });
    rootBody?.addEventListener("click", (e) => {
        if (
            e.target.tagName != "BUTTON" ||
            !e.target.classList.contains("style")
        ) {
            const stylesContainer = document.querySelector(".styles-container");
            stylesContainer?.classList.remove("show");
        }
    });
    function initHugerte() {
        const hugerteEditors =
            blockSettingsPopUpContent.querySelectorAll(".hugerte-editor");

        hugerteEditors?.forEach((hugerteEditor) => {
            const textAreaFieldId = hugerteEditor.getAttribute("id");
            hugerte.get(textAreaFieldId)?.destroy();

            if (!textAreaFieldId) return;
            hugerte.init({
                selector: `#${textAreaFieldId}`,
                skin_url: "default",
                plugins: "advlist lists link",
                toolbar:
                    "undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist|link|removeformat",
                menubar: false,
                content_css: "default",
                setup: function (editor) {
                    editor.on("change", function () {
                        hugerteEditor.value = editor.getContent();
                        hugerteEditor.dispatchEvent(changeEvent);
                    });
                    editor.on("keyup", function () {
                        hugerteEditor.value = editor.getContent();
                        hugerteEditor.dispatchEvent(changeEvent);
                    });
                    editor.on("blur", function () {
                        hugerteEditor.value = editor.getContent();
                        hugerteEditor.dispatchEvent(changeEvent);
                    });
                },
            });
        });
    }

    function addSettingsInputEvents() {
        const inputs =
            blockSettingsPopUpContent.querySelectorAll("input, textarea");

        inputs?.forEach((input) => {
            const inputType = input.getAttribute("type");
            const inputMin = parseInt(input.getAttribute("min"));
            const inputMax = parseInt(input.getAttribute("max"));
            input.addEventListener("change", (e) => {
                if (settingsTimeout) {
                    clearTimeout(settingsTimeout);
                }
                settingsTimeout = setTimeout(function () {
                    saveSettings();
                }, settingsTimeoutSeconds);
            });

            if (inputType && inputType.toLowerCase() == "number") {
                input.addEventListener("wheel", (e) => {
                    const inputValue = input.value ? parseInt(input.value) : 1;
                    if (e.deltaY > 0) {
                        if (inputValue > inputMin) {
                            input.value = inputValue - 1;
                        } else {
                            input.value = 1;
                        }
                    } else {
                        if (inputValue < inputMax) {
                            input.value = inputValue + 1;
                        }
                    }
                    input.dispatchEvent(changeEvent);
                    // if (settingsTimeout) {
                    //     clearTimeout(settingsTimeout);
                    // }
                    // settingsTimeout = setTimeout(function () {
                    // }, 1000);
                });
            }
        });
    }
    function saveSettings(closeSettingsPopup = false) {
        const errorFields =
            blockSettingsPopUpContent.querySelectorAll(".settings-errors");
        errorFields?.forEach((el) => {
            el.innerHTML = "";
        });
        const form = blockSettingsPopUpContent.querySelector("form");

        if (!form) {
            return;
        }

        const formData = new FormData(form);
        const formObject = Object.fromEntries(formData.entries());
        const data = {};
        data["resumeUUID"] = resumeUUID;
        data["form"] = formObject;
        data["page"] = window.resumeEditorPage;
        const url = "/dashboard/update-block-settings";
        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(data),
        })
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if (data.status == "success") {
                    const callback = () => {
                        initBlockSorting();
                        refreshSections();
                        if (closeSettingsPopup) {
                            closeBlockSettingsPopUpWindow();
                        }
                    };
                    renderTemplate(callback);
                } else {
                    const errorTemplate = "";
                    for (let id in data.errors) {
                        const fieldErrors = data.errors[id];
                        const fieldId = `${id}-settings-errors`;
                        id = "title-settings-errors";

                        const fieldEl = blockSettingsPopUpContent.querySelector(
                            `.settings-errors[id="${fieldId}"]`,
                        );
                        if (fieldEl) {
                            for (let index in fieldErrors) {
                                const error = fieldErrors[index];
                                fieldEl.innerHTML += `<p class="text-rose-600 italic pt-1">${error}</p>`;
                            }
                        }
                    }
                }
                // refreshSections();
            });
    }
    // Events
    rootBody.addEventListener("keyup", function (event) {
        if (event.key === "Escape") {
            closePopUpWindow();
            saveSettings();
            closeBlockSettingsPopUpWindow();
            const stylesContainer = document.querySelector(".styles-container");
            stylesContainer?.classList.remove("show");
        }
    });
    blockSettingsPopUpContent?.addEventListener("click", (e) => {
        if (e.target.classList.contains("close-block-settings")) {
            saveSettings();
            closeBlockSettingsPopUpWindow();
        } else if (e.target.classList.contains("save-block-settings")) {
            e.preventDefault();
            saveSettings();
            closeBlockSettingsPopUpWindow();
            return false;
        }
    });
    popUpOverlay?.addEventListener("click", () => {
        closePopUpWindow();
    });
    blockSettingsPopUpOverlay?.addEventListener("click", () => {
        saveSettings();
        closeBlockSettingsPopUpWindow();
    });
    const popupWidgetButtons = document.querySelectorAll(".add-new-block");
    popupWidgetButtons?.forEach((widgetButton) => {
        widgetButton.addEventListener("click", (e) => {
            const parentEl = e.target.closest(".add-new-block");
            const widgetName = parentEl.getAttribute("name");
            const popup = document.querySelector(".block-popup");
            const sectionName = popup.getAttribute("section-name");
            addNewWidget(sectionName, widgetName);
        });
    });

    // Functions
    function renderTemplate(callback) {
        if (templateCanvas) {
            fetch(newBuilderRenderTemplateURL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            })
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    templateCanvas.innerHTML = data.html;
                    window.newBuilderWidgets = data.widgets;
                    callback();
                });
        }
    }

    function openPopUpWindow() {
        rootBody?.classList.add("popup-open");
    }

    function closePopUpWindow() {
        rootBody?.classList.remove("popup-open");
    }

    function openBlockSettingsPopUpWindow(blockId = null) {
        if (blockId) {
            const url = "/dashboard/block-settings";
            const data = {};
            data["resumeUUID"] = resumeUUID;
            data["blockId"] = blockId;
            data["page"] = window.resumeEditorPage;
            // data["sectionSlug"] = sectionSlug;
            // data["widgetName"] = widgetName;
            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    blockSettingsPopUpContent.innerHTML = data.html;
                    initHugerte();
                    window.initInputLimitCounter();
                    addSettingsInputEvents();
                    rootBody?.classList.add("block-settings-popup-open");
                });
        }
    }

    function closeBlockSettingsPopUpWindow() {
        rootBody?.classList.remove("block-settings-popup-open");
        blockSettingsPopUpContent.innerHTML = "";
    }
    function refreshSections(initial = false) {
        if (!initial) {
            if (sortTimeout) {
                clearTimeout(sortTimeout);
            }
            sortTimeout = setTimeout(function () {
                sort();
            }, refreshSectionsSeconds);
        }
        const sections = document.querySelectorAll(`.template.html .section`);
        sections?.forEach(function (section) {
            const widgets = section.querySelectorAll(".widget");
            if (widgets.length) {
                section.classList.remove("empty");
            } else {
                section.classList.add("empty");
            }
            widgets?.forEach((widget) => {
                const widgetName = widget.getAttribute("name");

                let childNodeCount = 0;
                widget.childNodes?.forEach((node) => {
                    const nodeType = node.nodeType;
                    if (nodeType === Node.ELEMENT_NODE) {
                        if (node.classList.contains("delete-block-button")) {
                            return;
                        }
                        if (node.childNodes.length) {
                            childNodeCount++;
                        }
                    } else if (nodeType === Node.TEXT_NODE) {
                        if (node.nodeValue.trim() != "") {
                            childNodeCount++;
                        }
                    }
                });
                if (childNodeCount == 0) {
                    widget.classList.add("empty-widget");
                }
            });
        });
        const links = document.querySelectorAll(`.template.html a`);
        links.forEach((link) => {
            link.setAttribute("href", "#");
            link.removeAttribute("target");
            link.addEventListener("click", (e) => {
                e.preventDefault();
                return false;
            });
        });
        const divs = document.querySelectorAll(".has-children");
        Object.keys(divs).forEach(function (index) {
            const item = divs[index];
            let childrenCount = 0;
            const visibleChildren = Array.from(item.children).filter(
                function (child) {
                    if (getComputedStyle(child).display == "none") {
                        // delete child.remove();
                    }
                    if (child.classList.contains("empty")) {
                        return false;
                    }

                    if (child.tagName == "svg") {
                        return false;
                    }
                    return getComputedStyle(child).display !== "none";
                },
            );
            // item.removeAttribute("class");
            // item.classList.add(`has-children`);
            const classNames = item.getAttribute("class").split(" ");

            for (let i in classNames) {
                let className = classNames[i];
                const re = /(has-[0-9]{1,}-children)/i;
                if (re.test(className)) {
                    item.classList.remove(className);
                }
            }

            item.classList.add(`has-${visibleChildren.length}-children`);
        });

        document
            .querySelectorAll(".new-builder.template.html.bill-gates a")
            .forEach((anchor) => {
                if (
                    anchor.childNodes.length <= 2 &&
                    anchor.firstChild.nodeType === Node.TEXT_NODE
                ) {
                    const originalText = anchor.textContent;
                    anchor.innerHTML = `<span>${originalText}</span>`;
                }
            });
    }
    function sort() {
        const data = {};
        data["resumeUUID"] = resumeUUID;
        data["page"] = window.resumeEditorPage;
        data["sections"] = {};
        const sections = document.querySelectorAll(".template.html .section");
        sections?.forEach((section) => {
            const sectionName = section.getAttribute("name");
            data["sections"][sectionName] = [];
            const blocks = section.querySelectorAll(".widget");
            blocks?.forEach((block) => {
                const blockId = block.getAttribute("blockId");
                const widgetName = block.getAttribute("name");
                data["sections"][sectionName].push({
                    id: blockId,
                    widgetName,
                });
            });
        });

        const url = "/dashboard/sortBlocks";

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(data),
        });
    }
    function addNewWidget(sectionSlug = null, widgetName = null) {
        // const template = `<ol name="ol" class="widget ol " style="" blockid="1333"><li data-order="1" class="" style="">ol</li><li data-order="2" class="" style="">ol</li><li data-order="3" class="" style="">ol</li></ol>`;
        const templateWidget = document.querySelector(
            `.template.html .section[name="${sectionSlug}"]`,
        );
        if (templateWidget) {
            const data = {};
            data["resumeUUID"] = resumeUUID;
            data["sectionSlug"] = sectionSlug;
            data["widgetName"] = widgetName;
            data["page"] = window.resumeEditorPage;
            const url = "/dashboard/add-new-block";
            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    if (data.status == "success") {
                        templateWidget.insertAdjacentHTML(
                            "beforeend",
                            data.blockHTML,
                        );
                        refreshSections();
                    } else {
                        console.error(data.message);
                    }
                });
        }

        closePopUpWindow();
    }

    function initBlockSorting() {
        const sections = document.querySelectorAll(".template.html .section");
        const html = document.querySelector(".template.html");
        const body = document.querySelector(".template.html .body.template");
        let mouseDown = false;
        window.addEventListener("mousedown", () => {
            mouseDown = true;
        });
        window.addEventListener("mouseup", () => {
            mouseDown = false;
            html.classList.remove("drag-over");
        });
        body.addEventListener("mousemove", () => {
            if (mouseDown) {
                html.classList.add("drag-over");
            } else {
                html.classList.remove("drag-over");
            }
        });
        body.addEventListener("mouseleave", () => {
            if (!mouseDown) {
                html.classList.remove("drag-over");
            }
        });
        body.addEventListener("click", function (e) {
            const block = e.target.parentNode;
            const parent = e.target.closest(".widget");
            if (!parent) return;
            const blockId = parent.getAttribute("blockId");

            if (e.target.classList.contains("delete-block-button")) {
                if (confirm(are_you_sure)) {
                    const data = {};
                    data["resumeUUID"] = resumeUUID;
                    data["blockId"] = blockId;
                    data["page"] = window.resumeEditorPage;
                    const url = "/dashboard/delete-block";
                    fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        body: JSON.stringify(data),
                    })
                        .then((response) => {
                            return response.json();
                        })
                        .then((data) => {
                            block.remove();
                            refreshSections();
                        });
                }
            } else {
                openBlockSettingsPopUpWindow(blockId);
            }
        });
        sections?.forEach(function (section) {
            const sectionName = section.getAttribute("name");

            const sortableSection = Sortable.create(section, {
                group: "sectionName",
                animation: 150,
                draggable: ".widget",
                onEnd: function (evt) {
                    html.classList.remove("drag-over");
                    mouseDown = false;
                },
                onSort: function (evt) {
                    refreshSections();
                },
                onAdd: function (evt) {},
                onRemove: function (evt) {},
            });
            section.addEventListener("dragenter", (event) => {
                event.preventDefault();
            });

            section.addEventListener("dragleave", () => {});

            section.addEventListener("drop", (event) => {
                event.preventDefault();
            });
        });
        var section = null;
        for (let key in window.newBuilderWidgets) {
            const blocks = window.newBuilderWidgets[key];
            section = document.querySelector(
                `.template.html .section[name="${key}"]`,
            );

            for (let blockId in blocks) {
                const blockHTML = blocks[blockId];
                section.insertAdjacentHTML("beforeend", `${blockHTML}`);
            }
        }
        const addButtons = document.querySelectorAll(
            ".template.html .section-add-widget",
        );
        addButtons?.forEach((addButton) => {
            addButton.addEventListener("click", (e) => {
                const sectionName = e.target.parentNode.getAttribute("name");
                selectedSection = sectionName;
                openPopUpWindow();
                const popup = document.querySelector(".block-popup");
                if (popup) {
                    popup.setAttribute("section-name", selectedSection);
                }
            });
        });
    }

    initBlockSorting();
    refreshSections(true);
});
