<?php

namespace Scrapping;

use Exception;
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
        $urls = $scrappingUtils->getUrlsFromDb($website->getWebsiteName());
        if ($urls) {
            $website->scrapWebsite();
            $scrappingUtils->updateDbUrls($website->getWebsiteName(), $urls);
        } else {
            $scrappingUtils->getUrlsFromScrapping($website);
            echo "get Urls From Scrapping Done \n";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
    }
}

echo "\n" . '****** Scrapping is done ******' . "\n";


