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
        $this->getBrowserTab($category['url']);

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
    function getProduct(string $itemUrl): array
    {
        $item = [];

        // intro--image__box -> get the span element with the data-img attribute to get the image href
        // intro--text__descr -> the description of the product
        // titles--label -> the title of the product
        // titles--sub -> the reference of the product

        $imageBox = $this->webDriver->findElements(WebDriverBy::className($this->websiteConfig['product']['image']));
        $productDescription = $this->webDriver->findElements(WebDriverBy::className($this->websiteConfig['product']['description']));
        $productTitle = $this->webDriver->findElements(WebDriverBy::className($this->websiteConfig['product']['title']));
        $productId = $this->webDriver->findElements(WebDriverBy::className($this->websiteConfig['product']['id']));
        $productPrice = $this->webDriver->findElements(WebDriverBy::className($this->websiteConfig['product']['price']));

        $item = [
            'image' => $imageBox[0]->findElement(WebDriverBy::tagName('span'))->getAttribute('data-img'),
            'title' => $productTitle[0]->getText(),
            'id' => $productId[0]->getText(),
            'price' => $productPrice[0]->getText(),
            'description' => $productDescription[0]->getText(),
        ];

        $this->getBrowserTab($itemUrl);
        die('stop');
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

    /**
     * Get & open a browser tab with the url
     *
     * @param string $url
     * @return void
     */
    private function getBrowserTab(string $url) {
        // Go to the URL and retrieve it
        $this->webDriver->get($url);
        $this->webDriver->manage()->window()->maximize();

        // Wait the page to load
        // @todo: Check if there is an element to check to confirm that the page is loaded
        // @todo: Update DB with cron every hour
        sleep(3);
    }


}