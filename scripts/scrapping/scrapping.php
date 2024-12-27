<?php

namespace Scrapping;

use Exception;
use Scrapping\websites\Fermob;
use Scrapping\websites\Pedrali;

require __DIR__ . "/../../website/vendor/autoload.php";

echo '***********************************' . "\n";
echo 'Checking arguments...' . "\n";
/*
 * The $argv comes from the command line arguments
 */

$scrappingUtils = new ScrappingUtils(loadWordpress:true);

try {
    $scrappingUtils->checkArguments($argv);
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
/*
 * End of arguments validity checking step
 */

/*
 * Scrapping
 */
echo "\n" . '****** Starting scrapping ******' . "\n";

// Todo : add for each on all argv
if (in_array('pedrali', $argv) ) {
    try {
        $website = new Pedrali();
        $productUrls = $scrappingUtils->getUrlsFromDb($website->getWebsiteName());
        if ($productUrls) {
            $website->scrapProductUrls($productUrls);
            $urlsToUpdate = array_map(function ($url) use (&$urlsString, &$urlsToUpdate) {
                return $url['url'];
            }, $productUrls);
            $scrappingUtils->updateDbUrls($website->getWebsiteName(), $urlsToUpdate);
            $website->closeBrowser();
        } else {
            $scrappingUtils->getUrlsFromScrapping($website);
            echo "get Urls From Scrapping Done \n";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
    }
}

// Todo : add for each on all argv
if (in_array('fermob', $argv) ) {
    try {
        $website = new Fermob();
        $productUrls = $scrappingUtils->getUrlsFromDb($website->getWebsiteName());
        if ($productUrls) {
            $website->scrapProductUrls($productUrls);
            $urlsToUpdate = array_map(function ($url) use (&$urlsString, &$urlsToUpdate) {
                return $url['url'];
            }, $productUrls);
            $scrappingUtils->updateDbUrls($website->getWebsiteName(), $urlsToUpdate);
            $website->closeBrowser();
        } else {
            $scrappingUtils->getUrlsFromScrapping($website);
            echo "get Urls From Scrapping Done \n";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
    }
}

echo "\n" . '****** Scrapping is done ******' . "\n";


