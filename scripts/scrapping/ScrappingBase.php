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
    )
    {
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
        return [
            'categories' => [
                'categoryName' => [
                    'url' => '', // ex: https://www.pedrali.com/fr-fr/produits/chaises-design
                    'id' => 'className', // ex: categoria--item
                    'item-href-element' => 'dom-element', // ex: href
                    'type' => [ // indoor && outdoor || all
                        'indoor' => 'url',
                        'outdoor' => 'url',
                    ],

                    /*
                     * Ex:
                     * 'type' => [ // indoor / outdoor / both
                     * 'all' => 'https://www.pedrali.com/fr-fr/produits/chaises-design',
                     * ],
                     *
                     */
                ],
            ],
            'product' => [
                'title' => 'className', // ex: titles--label
                'description' => 'className', // ex: intro--text__descr
                'reference' => 'className', // ex: titles--sub -> constructor reference
                'price' => 'className', // className or false
                'type' => 'className', // ex: infobox--ambiti-list
                'is_new' => false, // true or false
                'images' => [
                    'product' => 'className', // ex: intro--image__box -> Product image
                    'cover' => [ // Banner cover
                        'multiple' => true,
                        'img' => 'className', // ex: intro--image__box
                        'gallery' => 'className', // ex: intro--image__box
                    ],
                ],
                'technical-data' => [
                    'weight' => 'className', // ex: technical-data--weight
                    'width' => 'className', // ex: technical-data-width
                    'height' => 'className', // ex: technical-data-height
                    'depth' => 'className', // ex: technical-data-depth
                    'colors' => [ // ex: technical-data-color-1
                        'className',
                        'className',
                    ],
                ],
                'product-availability' => [
                    'order-only' => false, // true or false
                    'in-stock' => false, // true or false
                ],
            ],
            'cookie-banner' => [
                'id' => 'className', // ex: onetrust-banner-sdk
                'rejectButtonId' => 'className', // ex: onetrust-reject-all-handler
            ],
            'scroll-down' => false, // true or false
        ];
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
     * Get & open a browser tab with the url
     *
     * @param string $url
     * @return void
     */
    private function getBrowserTab(string $url)
    {
        // Go to the URL and retrieve it
        $this->webDriver->get($url);
        $this->webDriver->manage()->window()->maximize();

        // Wait the page to load
        // @todo: Check if there is an element to check to confirm that the page is loaded
        // @todo: Update DB with cron every hour
        sleep(3);
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
            foreach ($category['type'] as $categoryUrl) {
                $this->getCategoryItems($category, $categoryUrl);
            }
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
    function getCategoryItems(array $category, $categoryUrl): array
    {
        $this->getBrowserTab($categoryUrl);

        /*
         * Handling cookie banner
         */
        if (isset($this->websiteConfig['cookie-banner'])) {
            $this->handleCookieBanner($this->websiteConfig['cookie-banner'], $this->webDriver);
        }

        if ($this->websiteConfig['scroll-down']) {
            // Loading all items
            $this->scrappingUtils->scrollDown($this->webDriver);
            // End of loading all items
        }

        // Get the category's children
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
            foreach ($categoryItems as $categoryItem) {
                $itemUrl = $categoryItem->getAttribute($category['item-href-element']);
                dump('$itemUrl', $itemUrl);
                $item = $this->getProductDetails($itemUrl);
                $categoryProducts[] = $item;
            }
        }

        // Close the browser
        $this->webDriver->quit();

        return $categoryProducts;
    }

    /**
     * Return all the infos of a product from its url
     *
     * @param string $itemUrl
     * @return array
     */
    function getProductDetails(string $itemUrl): array
    {
        $this->getBrowserTab($itemUrl);
        $productWebsiteConfig = $this->websiteConfig['product'];
        $itemDetails = [];
        $itemDetails['product-url'] = $itemUrl;

        foreach ($productWebsiteConfig as $configKey => $configArray) {
            switch ($configKey) {
                case 'images':
                    // Get the image for the product
                    if ($productWebsiteConfig['images']['product']) {
                        $imageBox = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig['images']['product']));
                        $itemDetails['image-product'] = $imageBox[0]->findElement(WebDriverBy::tagName('span'))->getAttribute('data-img');
                    }

                    // Get the images for the cover
                    if ($productWebsiteConfig['images']['cover']) {
                        $itemDetails['images-cover'] = [];
                        if ($productWebsiteConfig['images']['cover']['single']) {
                            $imageBox = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig['images']['cover']));
                            $itemDetails['images-cover'][] = $imageBox[0]->findElement(WebDriverBy::tagName('span'))->getAttribute('data-img');
                        } elseif ($productWebsiteConfig['images']['cover']['multiple']) {

                            if ($productWebsiteConfig['images']['cover']['xpath']) {
                                $imageBox = $this->webDriver->findElements(WebDriverBy::xpath($productWebsiteConfig['images']['cover']['xpath']));
                            } else {
                                $imageBox = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig['images']['cover']['gallery']));
                            }

                            foreach ($imageBox as $image) {
                                $itemDetails['images-cover'][] = $image->findElement(WebDriverBy::tagName('span'))->getAttribute('data-img');
                            }
                        }
                    }
                    break;
                case 'scroll-down':
                case 'cookie-banner':
                    break;
                default:
                    foreach ($configArray as $key => $value) {
                        if ($value) {
                            $productInfo = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig[$configKey][$key]));
                            $itemDetails[$key] = $productInfo[0]->getText();
                        } else {
                            $itemDetails[$key] = $value;
                        }
                    }
                    break;
            }
        }

        dump('$itemDetails', $itemDetails);
        die('stop');
        return $itemDetails;
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