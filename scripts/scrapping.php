<?php


require "website/vendor/autoload.php";
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

echo '***********************************' . "\n";
echo 'Checking arguments...' . "\n";

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
    'euroterrasse'
];

/*
 * Arguments validity checking step
 */
$invalidArgument = [];

if ($argv && count($argv) > 1 && $argv[0] === 'scripts/scrapping.php') {
    unset($argv[0]);

    foreach ($argv as $argument) {
        if (!in_array($argument, $authorizedFileArguments)) {
            echo 'invalid argument: ' . $argument  . "\n";
            $invalidArgument[] = $argument;
        }
    }

} else {
    echo 'File arguments invalid or empty' . "\n";
}

$numnberOfvalidArguments = count($argv) - count($invalidArgument);

if ($numnberOfvalidArguments === 0) {
    echo count($invalidArgument) . ' invalid argument(s) found' . "\n";
    echo 'No valid arguments found' . "\n";
    echo '***********************************' . "\n";
    return false;
} else {
    echo count($invalidArgument) . ' invalid argument(s) found' . "\n";
    echo $numnberOfvalidArguments . ' valid argument(s) found' . "\n";
}
echo '***********************************' . "\n";

/*
 * End of arguments validity checking step
 */


/*
 * Scrapping
 */
echo "\n" . '***** Starting scrapping ******' . "\n";

// Selenium WebDriver URL
$host = 'http://localhost:4444/wd/hub';

if (in_array('pedrali', $argv) ) {
    getPedraliUrlItems($host);
}

echo "\n" . '***** Scrapping is done ******' . "\n";


function scrapPedrali($host) {
   $pedraliUrlItems = getPedraliUrlItems($host);
}

/**
 * @param string $host
 * @return array|false
 * @throws Exception
 */
function getPedraliUrlItems(string $host) {

    $driver = initWebDriver($host);

    if (!$driver) {
        return false;
    }

    // Go to the URL and retrieve it
    $url = "https://www.pedrali.com/fr-fr/produits/chaises-design";
    $driver->get($url);

    /*
     * Handling cookie banner
     */

    // Wait the page to load
    // @todo: Check if there is an element to check to confirm that the page is loaded
    sleep(3);

    // Get the cookie Banner and it exists refuse the cookies by clicking and the refuse button
    $cookieBanner = $driver->findElement(WebDriverBy::id('onetrust-banner-sdk'));

    if ($cookieBanner) {
        $driver->findElement(WebDriverBy::id('onetrust-reject-all-handler'))->click();
    }

    // Wait for the cookie Banner to close
    // @todo: check if button exist if not we continue
    sleep(1);

    // Loading all items
    scrollDown($driver);
    // End of loading all items

    // Get the categories and children
    $categoryItems = $driver->findElements(WebDriverBy::className('categoria--item'));

    echo "\n";
    echo '***********************************' . "\n";
    echo '*                                 *' . "\n";
    echo '*                                 *' . "\n";
    echo '*          '. count($categoryItems) . ' items found' . '         *' . "\n";
    echo '*                                 *' . "\n";
    echo '*                                 *' . "\n";
    echo '***********************************' . "\n";
    echo "\n";

    $categoryItemsLinks = [];
    if ($categoryItems && count($categoryItems) > 0) {
        foreach ($categoryItems as $key => $categoryItem) {
            $categoryItemsLinks[] = $categoryItem->getAttribute('href');
        }
    }

    // xxx
    $driver->quit();

    return $categoryItemsLinks;
    die();
}

function savePedraliProduct(string $host, string $itemUrl) {
    return false;
}

/**
 * @param $host
 * @return void
 * @throws Exception
 */
function getPedraliProduct($host) {
    // @todo: Add percentage for number of product done
    $pedraliUrlItemsArray = getPedraliUrlItems($host);

    foreach ($pedraliUrlItemsArray as $pedraliUrlItem) {
        savePedraliProduct($host, $pedraliUrlItem);
    }
}

/**
 * Script JS to scroll down the page until button to add more products disappear
 * @param $driver
 * @return true
 */
function scrollDown($driver): bool
{
    $driver->executeScript('window.scrollTo(0,document.body.scrollHeight);');
    sleep(15);

    if ($driver->findElement(WebDriverBy::className('categoria--load-more'))->isDisplayed()) {
        scrollDown($driver);
    }

    return true;
}

/**
 * @param $host
 * @return RemoteWebDriver
 * @throws Exception
 */
function initWebDriver ($host) {
    try {
        // @todo: Check if possible to hide the navigator
        $driver = RemoteWebDriver::create($host,  Facebook\WebDriver\Remote\DesiredCapabilities::firefox());
        $driver->manage()->window()->maximize();
        return $driver;
    } catch (\Exception $e) {
        echo 'error:' . $e->getMessage() . "\n";
        throw new \Exception($e->getMessage());
    }
}