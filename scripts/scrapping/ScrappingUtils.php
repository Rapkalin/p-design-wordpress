<?php

namespace Scrapping;

use Facebook\WebDriver\WebDriverBy;

class ScrappingUtils
{
    /**
     * Script JS to scroll down the page until button to add more products disappear
     * @param $driver
     * @param string $scrollDownClassName
     * @return true
     */
    function scrollDown($driver, string $scrollDownClassName): bool {
        $driver->executeScript('window.scrollTo(0,document.body.scrollHeight);');

        // If there is a load more button we keep scrolling
        if ($driver->findElement(WebDriverBy::className($scrollDownClassName))->isDisplayed()) {
            // We wait 15 seconds before to scroll down to let the time to the elements to appear on the screen
            sleep(5);
            $this->scrollDown($driver, $scrollDownClassName);
        }

        return true;
    }

    public static function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }


}