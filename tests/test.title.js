const { Builder, By, Key, until } = require('selenium-webdriver');
var expect = require('chai').expect;

describe( 'title should equal "Andrew JD Hudson – Just another WordPress site"', function() {
	const url = 'http://big-andy.test';
	const searchTerm = "Andrew JD Hudson – Just another WordPress site";
	let driver = new Builder().forBrowser('chrome').build();

    before(function(){
        driver.get( url );
        // driver.findElement(webdriver.By.id(username)).sendKeys(my_username);
        // driver.findElement(webdriver.By.id(submit)).click();
    });

    after(function(){
        driver.quit();
    });

    it( 'Test Case' , function(){
        driver.getTitle().then(function(title){
            expect(title).equals(searchTerm);
        })

        driver.sleep();
    });

});



