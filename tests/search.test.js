const { Builder, By, Key, until } = require('selenium-webdriver');

(async function searchGoogleFor() {
	let driver = await new Builder().forBrowser('chrome').build();
	var xpath = '//*[@id="rso"]/div[1]/div/div[1]/div/div/h3/a';  // reference to first link in google SRP.

	try {
		const name = 'big-andy';
		await driver.get('http://www.google.com/ncr'); // opens google
		await driver.findElement(By.name('q')).sendKeys(name, Key.RETURN); // searches for name
		await driver.getTitle()
			.then(t => console.log('The Google Search title is :', t)); // gets the Google SRP page title.
		await driver.wait(until.elementLocated({ xpath })) // waits until the page has loaded and the link is located.
		await driver.findElement({ xpath }).click(); // click on the first results link and navigates to it.
		await driver.getTitle()
			.then(t => console.log('The result title is :', t)); // log the title of the page.
	} finally {
		await driver.quit(); // closes the browser.
	}
})();
