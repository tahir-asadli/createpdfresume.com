class ResumeDownloader {
    constructor(elementId = "download-resume", csrfToken = null) {
        this.resumeDownloadIntervalLimit = 10;
        this.resumeDownloadIntervalIterator = 1;
        this.pollingInterval = 5000;
        this.pdfGenTrackingInterval = null;
        this.csrfToken = csrfToken;

        this.downloadResume = document.getElementById(elementId);
        this.init();
    }

    init() {
        if (this.downloadResume) {
            this.downloadResume.addEventListener(
                "click",
                this.handleDownloadClick.bind(this),
            );
        }
    }

    changeDownloadButtonState(state = "loading") {
        if (!this.downloadResume) {
            return;
        }

        const downloadResumeWaitText = this.downloadResume.querySelector(
            "#download-resume-wait-text",
        );
        const downloadResumeIdleText = this.downloadResume.querySelector(
            "#download-resume-idle-text",
        );

        if (state === "loading") {
            downloadResumeWaitText?.classList.remove("hidden");
            downloadResumeIdleText?.classList.add("hidden");
            this.downloadResume.setAttribute("disabled", true);
            this.downloadResume.classList.add("loading");
        } else if (state === "loaded") {
            this.downloadResume.classList.remove("loaded");
            this.downloadResume.classList.remove("loading");
            downloadResumeWaitText?.classList.add("hidden");
            downloadResumeIdleText?.classList.remove("hidden");
            this.downloadResume.removeAttribute("disabled");
        } else if (state === "initial") {
            // Initial state - no changes needed
        } else if (state === "disable") {
            this.downloadResume.classList.remove("loaded");
            this.downloadResume.classList.remove("loading");
            downloadResumeWaitText?.classList.add("hidden");
            downloadResumeIdleText?.classList.remove("hidden");
            this.downloadResume.setAttribute("disabled", true);
        }
    }

    trackPDFGen(trackingId) {
        const url = "/dashboard/pdfgenping";
        const data = { trackingId };

        if (
            this.resumeDownloadIntervalIterator ===
            this.resumeDownloadIntervalLimit
        ) {
            clearInterval(this.pdfGenTrackingInterval);

            alert(
                downloadButtonTranslations?.work_in_background
                    ? downloadButtonTranslations.work_in_background
                    : "The PDF is taking a little longer than usual to generate. We're still working on it in the background! You don't need to stay on this page—we'll send an email to your inbox as soon as it's ready for download.",
            );
            this.changeDownloadButtonState("disable");
            return;
        }

        try {
            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    clearInterval(this.pdfGenTrackingInterval);
                    return;
                })
                .then((data) => {
                    if (
                        data?.status === "completed" &&
                        data?.download_url !== ""
                    ) {
                        clearInterval(this.pdfGenTrackingInterval);
                        setTimeout(() => {
                            this.changeDownloadButtonState("loaded");
                            location = data?.download_url;
                        }, 1000);
                    } else {
                        if (data?.message) {
                            alert(data?.message);
                        }
                    }
                });
        } catch (error) {
            clearInterval(this.pdfGenTrackingInterval);
            alert(error);
        }

        this.resumeDownloadIntervalIterator++;
    }

    handleDownloadClick(e) {
        if (this.downloadResume.dataset.resumeUuid) {
            const url = "/dashboard/pdfgen";
            const data = { resumeUUID: this.downloadResume.dataset.resumeUuid };

            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    this.changeDownloadButtonState("disable");
                    alert(
                        downloadButtonTranslations?.error
                            ? downloadButtonTranslations.error
                            : "Server error!",
                    );
                    return;
                })
                .then((data) => {
                    if (data?.status === "ok" && data?.trackingId !== "") {
                        this.pdfGenTrackingInterval = setInterval(() => {
                            this.trackPDFGen(data.trackingId);
                        }, this.pollingInterval);
                    } else {
                        this.changeDownloadButtonState("disable");
                        if (data?.message) {
                            alert(data?.message);
                        }
                    }
                });
        }

        this.changeDownloadButtonState("loading");
    }

    destroy() {
        if (this.pdfGenTrackingInterval) {
            clearInterval(this.pdfGenTrackingInterval);
        }
        if (this.downloadResume) {
            this.downloadResume.removeEventListener(
                "click",
                this.handleDownloadClick.bind(this),
            );
        }
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    const resumeDownloader = new ResumeDownloader("download-resume", csrfToken);
});

// Initialize the class (you can pass custom elementId and csrfToken if needed)
// Example usage:
