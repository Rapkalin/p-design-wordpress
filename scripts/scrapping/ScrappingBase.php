<?php

namespace Scrapping;

use Exception;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class ScrappingBase
{

    /**
     * The webDriver
     *
     * @var RemoteWebDriver
     */
    private RemoteWebDriver $webDriver;

    /**
     * The webDriver host (Selenium server host)
     *
     * @var string
     */
    private string $host = 'http://localhost:4444/wd/hub';

    /**
     * The website configuration passed in the constructor
     *
     * @var array
     */
    private array $websiteConfig;

    /**
     * The website name passed in the constructor
     *
     * @var string
     */
    private string $websiteName;

    /**
     * The scrapping function utils
     *
     * @var scrappingUtils
     */
    private scrappingUtils $scrappingUtils;

    /**
     * websiteName: The name of the website that needs to be included in $authorizedFileArguments
     * websiteConfig: The configuration website for the scrapping
     *
     * @throws Exception
     */
    public function __construct(
        string $websiteName,
        array $websiteConfig
    ) {
        $this->webDriver = $this->initWebDriver($this->host);
        $this->websiteConfig = $websiteConfig;
        $this->websiteName = $websiteName;
        $this->scrappingUtils = new scrappingUtils();
    }

    /**
     * Return the website configuration
     *
     * @return array
     */
    public function getWebsiteConfig(): array
    {
        return $this->websiteConfig;
    }

    /**
     * Init a new webDriver
     *
     * @param string $host
     * @return RemoteWebDriver
     * @throws Exception
     */
    function initWebDriver (string $host): RemoteWebDriver
    {
        try {
            // @todo: Check if possible to hide the navigator
            return RemoteWebDriver::create($host, DesiredCapabilities::firefox());
        } catch (\Exception $e) {
            echo 'error:' . $e->getMessage() . "\n";
            throw new \Exception('initWebDriver Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle the website scrapping
     *
     * @return true
     * @throws Exception
     */
    function scrapWebsite(): bool
    {
        $categories = $this->websiteConfig['categories'];
        $categoryProducts = [];

        foreach ($categories as $category) {
            $categoryProducts[] = $this->getCategoryItems($category);
        }

        try {
            echo 'Saving products...' . "\n";
            $this->saveProducts($categoryProducts);
            echo 'Products successfully saved in database' . "\n";
        } catch (\Exception $e) {
            echo 'error:' . $e->getMessage() . "\n";
            throw new \Exception('saveProducts Error: ' . $e->getMessage());
        }

        return true;
    }

    /**
     * Return all category items
     *
     * @Return array
     * @throws Exception
     */
    function getCategoryItems(array $category): array
    {
        // Go to the URL and retrieve it
        $this->webDriver->manage()->window()->maximize();
        $this->webDriver->get($category['url']);

        // Wait the page to load
        // @todo: Check if there is an element to check to confirm that the page is loaded
        // @todo: Update DB with cron every hour
        sleep(3);

        /*
         * Handling cookie banner
         */
        if (isset($this->websiteConfig['cookie-banner'])) {
            $this->handleCookieBanner($this->websiteConfig['cookie-banner'], $this->webDriver);
        }

        // Loading all items
        $this->scrappingUtils->scrollDown($this->webDriver);
        // End of loading all items

        // Get the categories and children
        $categoryItems = $this->webDriver->findElements(WebDriverBy::className($category['id']));

        echo "\n";
        echo '***********************************' . "\n";
        echo '*                                 *' . "\n";
        echo '*                                 *' . "\n";
        echo '*          '. count($categoryItems) . ' items found' . '         *' . "\n";
        echo '*                                 *' . "\n";
        echo '*                                 *' . "\n";
        echo '***********************************' . "\n";
        echo "\n";

        $categoryProducts = [];
        // @todo: Add percentage for number of product done
        if ($categoryItems && count($categoryItems) > 0) {
            foreach ($categoryItems as $key => $categoryItem) {
                $itemUrl = $categoryItem->getAttribute($category['item-href-element']);
                $item = $this->getProduct($itemUrl);
                $categoryProducts[] = $item;
            }
        }

        // Close the browser
        $this->webDriver->quit();

        return $categoryProducts;
    }

    /**
     * Handle the cookie banner
     *
     * @param array $cookieBannerDetails
     * @param $driver
     * @return void
     */
    function handleCookieBanner(array $cookieBannerDetails, $driver): void
    {
        // Get the cookie Banner and if it exists refuses the cookies by clicking on the refuse button
        $cookieBanner = $driver->findElement(WebDriverBy::id($cookieBannerDetails['id']));

        if ($cookieBanner) {
            $driver->findElement(WebDriverBy::id($cookieBannerDetails['rejectButtonId']))->click();
        }

        // Wait for the cookie Banner to close
        // @todo: check if button exist if not we continue
        sleep(2);
    }

    /**
     * Return all the infos of a product from its url
     *
     * @param string $itemUrl
     * @return array
     */
    function getProduct(string $itemUrl) {
        $item = [];

        return $item;
    }

    /**
     * Save the products in the database
     *
     * @param array $categoryItems
     * @return void
     */
    function saveProducts(array $categoryItems) {
        // Save the products
    }

}