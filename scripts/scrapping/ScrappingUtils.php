<?php

namespace Scrapping;

use Facebook\WebDriver\WebDriverBy;

/*
 * Load the WordPress environment
 * So we have access to WP and ACF functions
 */
define( 'WPMEDIA', __DIR__ . '/../../website/wordpress-core/wp-admin/includes/media.php' );
define( 'WPFILE', __DIR__ . '/../../website/wordpress-core/wp-admin/includes/file.php' );
define( 'WPIMAGE', __DIR__ . '/../../website/wordpress-core/wp-admin/includes/image.php' );
if (file_exists(WPMEDIA)) {
    require WPMEDIA;
    echo 'Wordpress media.php successfully loaded for: ' . get_bloginfo() . "\n\n";
} else {
    die('Failed to load Wordpress media.php.');
}

if (file_exists(WPFILE)) {
    require WPFILE;
    echo 'Wordpress file.php successfully loaded for: ' . get_bloginfo() . "\n\n";
} else {
    die('Failed to load Wordpress file.php.');
}

if (file_exists(WPIMAGE)) {
    require WPIMAGE;
    echo 'Wordpress image.php successfully loaded for: ' . get_bloginfo() . "\n\n";
} else {
    die('Failed to load Wordpress image.php.');
}

class ScrappingUtils
{
    /**
     * Script JS to scroll down the page until button to add more products disappear
     * @param $driver
     * @param string $scrollDownClassName
     * @return true
     */
    public function scrollDown($driver, string $scrollDownClassName): bool {
        $driver->executeScript('window.scrollTo(0,document.body.scrollHeight);');

        // If there is a load more button we keep scrolling
        if ($driver->findElement(WebDriverBy::className($scrollDownClassName))->isDisplayed()) {
            // We wait 15 seconds before to scroll down to let the time to the elements to appear on the screen
            sleep(5);
            $this->scrollDown($driver, $scrollDownClassName);
        }

        return true;
    }

    /**
     * @param $text
     * @param string $divider
     * @return string
     */
    public function slugify($text, string $divider = '-'): string {
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

    /**
     *  It will return the downloaded image id
     *
     * @param string $imageUrl
     * @param int $postId
     * @param string $title
     * @return int|string|\WP_Error
     */
    public function downloadImage (string $imageUrl, int $postId, string $title): \WP_Error|int|string {
        return media_sideload_image($imageUrl, $postId, $title, 'id');
    }


}