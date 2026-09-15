(async function () {
    const { Builder } = require("selenium-webdriver");
    const firefox = require("selenium-webdriver/firefox");
    const { writeFileSync } = require("fs");

    let options = new firefox.Options().addArguments("--headless");
    let driver = await new Builder()
        .forBrowser("firefox")
        .setFirefoxOptions(options)
        .build();

    try {
        await driver.get(process.argv[2]);

        let printResult = await driver.printPage({
            background: true,
            width: 21.0,
            height: 29.7,
            shrinkToFit: false
        }); // A4
        let printResultDecoded = Buffer.from(printResult, "base64");

        writeFileSync(process.argv[3], printResultDecoded);
    } finally {
        await driver.close();
    }
})();
// node pdf-driver.js https://example.com/URL/TO/PRINT ./PDF-RESULT.pdf
// https://stackoverflow.com/questions/48358556/firefox-headless-print-to-pdf-option
// pdfunite pdf11.pdf pdf12.pdf out.pdf
// chrome --headless  --disable-gpu --print-to-pdf http://127.0.0.1:44983/src/index.html
// firefox --headless --screenshot screenshot.png https://www.example.com