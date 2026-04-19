app.post("/generate-pdf", async (req, res) => {
    let browser;

    try {
        const { html } = req.body;

        browser = await puppeteer.launch({
            headless: "new",
            args: ["--no-sandbox", "--disable-setuid-sandbox"]
        });

        const page = await browser.newPage();

        await page.setContent(html, {
            waitUntil: "networkidle0"
        });

        // IMPORTANT: wait for fonts
        await page.evaluate(async () => {
            await document.fonts.ready;
        });

        // small delay for rendering stability
        await page.waitForTimeout(300);

        const pdf = await page.pdf({
            format: "A4",
            landscape: true,
            printBackground: true
        });

        await browser.close();

        res.setHeader("Content-Type", "application/pdf");
        res.send(pdf);

    } catch (err) {
        console.error(err);

        if (browser) await browser.close();

        res.status(500).send("PDF generation failed");
    }
});