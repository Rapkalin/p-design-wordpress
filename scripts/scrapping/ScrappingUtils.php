<?php

namespace Scrapping;

use Facebook\WebDriver\WebDriverBy;



final class ScrappingUtils
{
    private string $tableName;

    public function __construct (
       bool $loadWordpressMedia = false,
       bool $loadWordpress = false,
    ) {
        if ($loadWordpressMedia) {
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
        }
        if ($loadWordpress) {
            /*
             * Load the WordPress environment
             * So we have access to WP and ACF functions
             */
            define( 'WPPATH', __DIR__ . '/../../website/wordpress-core/wp-load.php' );
            if (file_exists(WPPATH)) {
                require WPPATH;
                echo 'Wordpress successfully loaded for: ' . get_bloginfo() . "\n\n";
            } else {
                die('Failed to load Wordpress.');
            }
        }
        $this->tableName = 'pdesign_urls';
    }

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

   public function checkArguments(array $argv) : void {
        /*
        * List of valid arguments
        */
       $authorizedFileArguments = [
           'pedrali',
           'iconchairs',
           'flexmob',
           'woodlabpoland',
           'misterwils',
           'nardioutdoor',
           'fenabel',
           'fameg',
           'euroterrasse',
       ];

       /*
        * Arguments validity checking step
        */
       $invalidArgument = [];

       if ($argv && count($argv) > 1 && $argv[0] === 'scripts/scrapping/scrapping.php') {
           unset($argv[0]);

           foreach ($argv as $argument) {
               if (!in_array($argument, $authorizedFileArguments)) {
                   echo 'invalid argument: ' . $argument  . "\n";
                   $invalidArgument[] = $argument;
               }
           }

       } else {
           echo "Something went wrong. \n";
           echo "Please make sure you called scripts/scrapping.php argument1 ... \n";
           echo "Or check that your arguments are valid";
           die('Script stopped');
       }

       $numnberOfvalidArguments = count($argv) - count($invalidArgument);

       if ($numnberOfvalidArguments === 0) {
           echo count($invalidArgument) . ' invalid argument(s) found' . "\n";
           echo 'No valid arguments found' . "\n";
       } else {
           echo count($invalidArgument) . ' invalid argument(s) found' . "\n";
           echo $numnberOfvalidArguments . ' valid argument(s) found' . "\n";
       }
       echo '***********************************' . "\n";
   }

    public function getUrlsFromScrapping($website) {
        $website->scrapCategoryUrls();
    }

    public function saveCategoryUrls(
        array $urls,
        $categoryName,
        $siteName
    ) {
        global $wpdb;
        try {
            $query = $wpdb->prepare("INSERT INTO %i (last_updated, category_name, site_name, url)", $this->tableName);
            $date = date('now');
            foreach ($urls as $url) {
                $query .= " VALUES ($date, $categoryName, $siteName, $url)";
            }

            dump('query', $query);
            die();
        } catch (\Exception $e) {
            echo 'Query saveCategoryUrls failed: ' . $e->getMessage() . "\n";
            return null;
        }

        return $wpdb->query($query);
    }

    public function getUrlsFromDb() {
        $this->checkIfTableExists();
        return $this->getUrls();
    }

    private function checkIfTableExists(): void {
        global $wpdb;
        try {
            $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($this->tableName));
        } catch (\Exception $e) {
            echo 'Query getUrlsFromDb failed: ' . $e->getMessage() . "\n";
            return;
        }

        if ($wpdb->get_var( $query ) !== $this->tableName ) {
            echo "Creating table $this->tableName \n";

            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $this->tableName (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              last_updated  datetime DEFAULT NULL,
              category_name tinytext NOT NULL,
              site_name tinytext NOT NULL,
              url varchar(55) DEFAULT '' NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }

    private function getUrls(): \mysqli_result|bool|int|null {
        global $wpdb;
        try {
            $query = $wpdb->prepare("SELECT * FROM %i WHERE `last_updated` = null", $this->tableName);
        } catch (\Exception $e) {
            echo 'Query getUrlsFromDb failed: ' . $e->getMessage() . "\n";
            return null;
        }

        return $wpdb->query($query);
    }
}