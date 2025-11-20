<?php

namespace Scrapping\websites;

use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Scrapping\ScrappingBase;
use Scrapping\ScrappingInterface;

class Fameg extends ScrappingBase implements ScrappingInterface
{
    public function __construct() {
        parent::__construct($this->getName(), $this->getConfig());
    }

    /**
     * Return the website configuration
     *
     * @return array
     */
    public function getConfig(): array {
        return [
            'categories' => [
                'chairs' => [
                    'id' => 'image-wrapper',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://fameg.pl/en/category/products_categories/chairs',
                    ],
                ],
                'stools' => [
                    'id' => 'image-wrapper',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://fameg.pl/en/category/products_categories/bar-stools-stools/',
                    ],
                ],
                'sofas' => [
                    'id' => 'image-wrapper',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://fameg.pl/en/category/products_categories/fotele_en/',
                    ],
                ],
                'tables' => [
                    'id' => 'image-wrapper',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://fameg.pl/en/category/products_categories/tables-coffee-tables/',
                    ],
                ]
            ],
            'product' => [
                'reference_prefix' => 'FAM',
                'global-infos' => [
                    // for xpath documentation: https://www.w3schools.com/xml/xpath_syntax.asp
                    'title' => ".//div[@class='prod-name']/a", // the /a get the child element
                    'description' => 'desc',
                    'reference' => ".//div[@class='prod-name']/div[2]", // constructor reference
                    'price' => false,
                    'type' => [], // indoor / outdoor / both
                    'is_new' => false,
                ],
                'images' => [
                    'alternatives' => [ // Alternative views
                        'multiple' => true,
                        'gallery' => 'carousel-thumbs-product',
                        "xpath" => ".//div[@class='swiper-wrapper']/a",
                        'single-img' => 'swiper-slide',
                    ],
                ],
                'technical-data' => [
                    'weight' => false,
                    'width' => false,
                    'height' => false,
                    'depth' => false,
                    'colors' => false,
                    'order-only' => false,
                    'in-stock' => 'En stock',
                ],
            ],
            'cookie-banner' => [
                'id' => 'onetrust-banner-sdk',
                'rejectButtonId' => 'onetrust-reject-all-handler'
            ],
            'turn-pages' => 'next',
            'scroll-down' => false
        ];
    }

    /**
     * @return string
     */
    public function getName(): string {
        return 'fameg';
    }

    /**
     * Scrap all the products urls from a category
     *
     * @Return array
     * @throws Exception
     */
    protected function getCategoryUrls(
        array $category,
        string $categoryUrl,
        string $categoryName,
        int $try = 0
    ): array {
        $this->getBrowserTab($categoryUrl);

        // Get the category's children
        $categoryItems = $this->webDriver->findElements(WebDriverBy::className($category['id']));

        if (
            !count($categoryItems) &&
            $try > 5
        ) {
            $try++;
            echo "Retrying to get category urls for $categoryName. Try N° $try" . PHP_EOL;
            $this->getCategoryUrls($category, $categoryUrl, $categoryName, $try);
        }

        $itemUrls = [];
        $this->scrappingUtils->getItemURls($categoryName,
            $categoryItems,
            $itemUrls,
            $category['item-href-element']
        );

        return $itemUrls;
    }


    /**
     * Return all the infos of a product from its url
     *
     * @param string $itemUrl
     * @param string $categoryName
     * @return array
     */
    protected function getProductDetails(string $itemUrl, string $categoryName): array {
        $this->getBrowserTab($itemUrl);
        $productWebsiteConfig = $this->getConfig()['product'];
        $itemDetails = [];
        $itemDetails['product-url'] = $itemUrl;

        foreach ($productWebsiteConfig as $configKey => $configArray) {
            switch ($configKey) {
                case 'images':
                    // Get all available images for the product
                    $imageBox = $this->webDriver->findElements(WebDriverBy::xpath($configArray['alternatives']['xpath']));
                    $itemDetails['image-product'] = [];

                    if ($imageBox) {
                        // We take the first image from imageBox as main image and cover image
                        $itemDetails['image-product'] = $this->getImageUrl($imageBox[0]);

                        // We take the images left for alternatives
                        $this->getAlternativeImages($itemDetails, $imageBox);
                    }
                    break;
                case 'scroll-down':
                case 'cookie-banner':
                    break;
                case 'technical-data':
                    // We need to get rid of the cookie banner before clicking elsewhere
                    $this->closeCookieBanner();

                    // Click on the button with the data-tab-link attribute: wymiary .ie the last li
                    // The dimensions table will have the open class
                    $descriptionTab = $this->webDriver->findElements(WebDriverBy::xpath(".//section[@class='sec-product-desc']//li[last()]/button"));
                    $descriptionTab[0]->click();

                    $datas = $this->webDriver->findElements(WebDriverBy::xpath(".//div[@data-tab-name='wymiary']//tbody//tr"));
                    foreach ($datas as $data) {
                        $children = $data->findElements(WebDriverBy::xpath(".//td"));
                        $itemDetails['technical-data'][$children[0]->getText()] = $children[1]->getText();
                    }
                    break;
                case 'reference_prefix':
                    $itemDetails[$configKey] = $configArray;
                    break;
                case 'global-infos':
                    $this->getGlobalInfos($itemDetails, $configArray);
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

        $itemDetails['title'] = $this->getItemTitle($itemDetails);
        $type = $this->getType($itemDetails['type']);
        $itemDetails['categories'] = $this->getItemCategories($categoryName, $type);
        return $itemDetails;
    }

    private function getType(array|string $type) : array
    {
        $formattedType = $type;
        if (is_string($type)) {
            $formattedType = explode("\n", $type);
        }

        return $formattedType;
    }

    private function getAlternativeImages(array &$itemDetails, array $imageBox): void {
        // Get alternative images
        foreach ($imageBox as $key => $imageElement) {
            if ($key) { // We ignore the first image of the array that is use as cover & main image
                $itemDetails['images-cover'][] = $this->getImageUrl($imageElement);
            }
        }
    }

    private function getImageUrl($imageElement): string {
        return $imageElement->findElement(WebDriverBy::tagName('img'))->getAttribute('data-src');
    }

    private function getGlobalInfos(array &$itemDetails, array $configArray): void {
        foreach ($configArray as $key => $value) {
            switch ($key) {
                case 'title':
                case 'reference':
                    $textElement = $this->webDriver->findElements(WebDriverBy::xpath($value));
                    $itemDetails[$key] = $this->getElementText($textElement);
                    break;
                case 'description':
                    $textElement = $this->webDriver->findElements(WebDriverBy::className($value));
                    $itemDetails[$key] = $this->getElementText($textElement);
                    break;
                default:
                    if ($value) {
                        $productInfo = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig[$configKey][$key]));
                        $itemDetails[$key] = $productInfo[0]->getText();
                    } else {
                        $itemDetails[$key] = $value;
                    }
                    break;
            }
        }
    }

    private function getElementText($textElement) : string {
        $text = $textElement[0]->getText();
        return str_replace("Product code: ", "", $text);
    }

    private function getItemTitle(array $itemDetails): string {
        // return preg_match('/([^\/]+)$/', $itemUrl, $matches) ? $matches[1] : $defaultTitle;
        return "{$itemDetails['title']} {$itemDetails['reference']}";
    }

    /**
     * Get all categories ids for a given item
     *
     * @param string $categoryName
     * @param array $type // indoor / outdoor / both / Accessories
     * @return array
     */
    private function getItemCategories (
        string $categoryName,
        array $type
    ): array {
        $categories = [];

        $parentCat = $this->getParentCategory($categoryName, $type);
        $categories[] = $parentCat ? $parentCat->term_id : (get_term_by('slug', 'a-trier', 'product_categories'))->term_id;

        foreach ($this->categories as $category) {
            if (
                $parentCat &&
                $parentCat->term_id === $category->parent &&
                $this->pDesingCategories[$categoryName] === htmlspecialchars_decode($category->name)
            ) {
                $categories[] = $category->term_id;
            }
        }

        return $categories;
    }

    /**
     * @param string $categoryName
     * @param array $type
     * It will return an integer
     *
     * It will return an integer
     * if no parent category => 0
     * if parent category => the id
     */
    private function getParentCategory (string $categoryName, array $type) {
        try {
            if ($categoryName === 'accessories') {
                // Accessories is the only other parent category with outdoor and indoor
                return get_term_by('slug', 'accessoires', 'product_categories');
            } elseif (in_array('outdoor', $type)) {
                return get_term_by('slug', 'mobilier-exterieur', 'product_categories');
            } else {
                return get_term_by('slug', 'mobilier-interieur', 'product_categories');
            }
        } catch (\Exception $e) {
            echo "Error while getting Parent Category: " . $e . PHP_EOL;
            return false;
        }
    }

    private function closeCookieBanner (): void {
        $cookieBanner = $this->getCookieBanner();
        if ($cookieBanner) {
            $cookieBanner->click();
        }

        if ($this->getCookieBanner()) {
            // We wait until the banner is gone
            sleep(2);
        }
    }

    private function getCookieBanner (): bool|RemoteWebElement {
        $cookieBanner = $this->webDriver->findElements(WebDriverBy::xpath(".//div[@id='cookie-notice']"));
        $attributes = $cookieBanner[0]->getAttribute('class');

        // if cookie banner is already hidden we do nothing
        if (str_starts_with($attributes, 'cookie-notice-hidden')) {
            return false;
        }

        $cookieButton = $this->webDriver->findElements(WebDriverBy::xpath(".//span[@id='cn-notice-buttons']/a"));
        return $cookieButton[0];
    }
}
