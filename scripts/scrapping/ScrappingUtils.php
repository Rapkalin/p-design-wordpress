<?php

namespace Scrapping;

use Facebook\WebDriver\WebDriverBy;



final class ScrappingUtils
{
    private string $tableName = 'pdesign_urls';

    protected array $colors = [
        'black' => "\033[30m",
        'red' => "\033[31m",
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'cyan' => "\033[36m",
        'white' => "\033[39m",
    ];

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

    public function turnPage(
        $driver,
        string $turnPagesClassName,
        string $categoryId,
        string $categoryName,
        array &$itemUrls,
        string $itemHrefElement
    ) : bool {
        $nextPageButton = $driver->findElement(WebDriverBy::className($turnPagesClassName));
        echo "Turning the pages..." . PHP_EOL;

        // If there is a load more button we keep scrolling
        if (!$nextPageButton->getDomProperty('ariaDisabled')) {
            $nextPageButton->click();
            // We wait 3 seconds let the time to the elements to appear on the screen
            sleep(3);
            $categoryItems = $driver->findElements(WebDriverBy::className($categoryId));

            $this->getItemURls($categoryName,
                $categoryItems,
                $itemUrls,
                $itemHrefElement
            );

            $this->turnPage(
                $driver,
                $turnPagesClassName,
                $categoryId,
                $categoryName,
            $itemUrls,
                $itemHrefElement
            );

        }

        return true;
    }

    public function getItemURls(string $categoryName, array $categoryItems, array &$itemUrls, $itemHrefElement): void {
        echo "\n";
        echo '***********************************' . "\n";
        echo '*                                 *' . "\n";
        echo '*  Category: ' . $categoryName . "\n";
        echo '*  '. count($categoryItems) . ' items found' . "\n";
        echo '*                                 *' . "\n";
        echo '***********************************' . "\n";
        echo "\n";

        // @todo: Add percentage for number of products done
        if ($categoryItems && count($categoryItems) > 0) {
            foreach ($categoryItems as $categoryItem) {
                $itemUrls[] = $categoryItem->getAttribute($itemHrefElement);
            }
        }
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

       if ($argv && count($argv) > 1 && str_contains(__DIR__ . '/scrapping.php', $argv[0])) {
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
            $query = $wpdb->prepare("INSERT INTO %i (last_updated, category_name, site_name, url) VALUES ", $this->tableName);
            $date = time();
            foreach ($urls as $key => $url) {
                $end = $key+1 < count($urls) ? ', ' : ';';
                $query .= "($date, " . "'" . "$categoryName" . "'" . ", " . "'" . "$siteName" . "'" . ", " . "'" . "$url" . "'" . ")$end";
            }

            echo 'Query saveCategoryUrls succeed: ' . $categoryName . "\n";
        } catch (\Exception $e) {
            echo 'Query saveCategoryUrls failed: ' . $e->getMessage() . "\n";
            return null;
        }

        return $wpdb->query($query);
    }

    public function getUrlsFromDb(string $websiteName, int $limit = 25) {
        $this->checkIfTableExists();
        return $this->getUrls($websiteName, $limit);
    }

    private function checkIfTableExists(): void {
        global $wpdb;
        try {
            $query = $wpdb->prepare("SHOW TABLES LIKE %s", "%$this->tableName%");
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
              url varchar(255) DEFAULT '' NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }

    private function getUrls(string $websiteName, int $limit): array {
        global $wpdb;
        try {
            echo "Getting urls form Database for $websiteName. \n";

            $query = $wpdb->prepare(
                "SELECT * FROM %i
                WHERE `last_updated` = '0000-00-00 00:00:00' 
                AND `site_name` = %s 
                LIMIT %d",
                $this->tableName,
                $websiteName,
                $limit
            );
        } catch (\Exception $e) {
            echo 'Query getUrlsFromDb failed: ' . $e->getMessage() . "\n";
            return [];
        }

        return $wpdb->get_results($query, ARRAY_A);
    }

    public function updateDbUrls(string $websiteName, array $urls): \mysqli_result|bool|int|null {
        global $wpdb;
        try {
            $time =  date('Y-m-d H:i:s', time());
            $query = $wpdb->prepare(
                "UPDATE %i
                    SET last_updated =  (CASE url                      
                    ", $this->tableName
            );

            foreach ($urls as $key => $url) {
                $end = $key+1 < count($urls) ? ' ' : ' END)';
                $query .= "WHEN " . "'" . "$url" . "'" . " THEN " . "'" . "$time" . "'" . "$end";
            }

            $urlsString = $this->formatUrlsString($urls);
            $query .= " WHERE url IN($urlsString) AND site_name =" . "'" . $websiteName . "'" . ";";

            dump('URLs to update: ', $urls);
            $wpdb->query($query);
        } catch (\Exception $e) {
            echo 'Query updateDbUrls failed: ' . $e->getMessage() . "\n";
            return null;
        }

        return $wpdb->query($query);
    }

    private function formatUrlsString(array $urlsArray) : string {
        $urlsString = '';
        foreach ($urlsArray as $key => $url) {
            $end = $key+1 < count($urlsArray) ? ',' : '';
            $urlsString .= "'" . $url . "'" . $end;
        }

        return $urlsString;
    }
}