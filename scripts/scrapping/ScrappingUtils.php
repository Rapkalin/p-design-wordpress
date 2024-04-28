<?php

namespace Scrapping;

use Facebook\WebDriver\WebDriverBy;

class ScrappingUtils
{
    /**
     * Script JS to scroll down the page until button to add more products disappear
     * @param $driver
     * @return true
     */
    function scrollDown($driver): bool {
        $driver->executeScript('window.scrollTo(0,document.body.scrollHeight);');

        // If there is a load more button we keep scrolling
        if ($driver->findElement(WebDriverBy::className('categoria--load-more'))->isDisplayed()) {
            // We wait 15 seconds before to scroll down to let the time to the elements to appear on the screen
            sleep(15);
            $this->scrollDown($driver);
        }

        return true;
    }


}