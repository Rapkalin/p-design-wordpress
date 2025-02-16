<?php

namespace Scrapping;

use Exception;
use Scrapping\websites\Fameg;
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
        $productUrls = $scrappingUtils->getUrlsFromDb($website->getName());
        if ($productUrls && is_array($productUrls)) {
            $website->scrapProductUrls($productUrls);
            $urlsToUpdate = array_map(function ($url) use (&$urlsString, &$urlsToUpdate) {
                return $url['url'];
            }, $productUrls);
            $scrappingUtils->updateDbUrls($website->getName(), $urlsToUpdate);
            $website->closeBrowser();
        } elseif ($productUrls && is_numeric($productUrls)) {
            echo "Scrapping for {$website->getName()} already done \n";
        } else {
            $scrappingUtils->getUrlsFromScrapping($website);
            echo "get Urls From Scrapping Done \n";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
    }
}

// Todo : add for each on all argv
if (in_array('fameg', $argv) ) {
    try {
        $website = new Fameg();
        $productUrls = $scrappingUtils->getUrlsFromDb($website->getName());
        if ($productUrls && is_array($productUrls)) {
            $website->scrapProductUrls($productUrls);
            $urlsToUpdate = array_map(function ($url) use (&$urlsString, &$urlsToUpdate) {
                return $url['url'];
            }, $productUrls);
            $scrappingUtils->updateDbUrls($website->getName(), $urlsToUpdate);
            $website->closeBrowser();
        } elseif ($productUrls && is_numeric($productUrls)) {
            echo "Scrapping for {$website->getName()} already done \n";
        } else {
            $scrappingUtils->getUrlsFromScrapping($website);
            echo "get Urls From Scrapping Done \n";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
    }
}

echo "\n ****** END OF SCRAPPING ****** \n";
die();


