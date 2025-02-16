<?php

namespace Scrapping\websites;

use Exception;
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
                    'title' => ".//div[@class='prod-name']/a", // the /a get the a child element
                    'description' => 'desc',
                    'reference' => ".//div[@class='prod-name']/div[2]", // constructor reference
                    'price' => false,
                    'type' => false, // indoor / outdoor / both
                    'is_new' => false,
                ],
                'images' => [
                    'product' => 'swiper-wrapper-new', // Product image
                    'alternatives' => [ // Alternative views
                        'multiple' => true,
                        'gallery' => 'carousel-thumbs-product',
                        "xpath" => ".//div[@class='swiper-slide']/a",
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
        return 'fermob';
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

        dump('$itemDetails before', $itemDetails);

        foreach ($productWebsiteConfig as $configKey => $configArray) {
            switch ($configKey) {
                case 'images':
                    // Get the image for the product
                    if ($productWebsiteConfig['images']['product']) {
                        $imageBox = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig['images']['product']));
                        dump('$imageBox', $imageBox);
                        die();
                        $itemDetails['image-product'] = $imageBox[0]->findElement(WebDriverBy::tagName('span'))->getAttribute('data-img');
                    }
                    break;
                case 'scroll-down':
                case 'cookie-banner':
                    break;
                case 'technical-data':
                    $descriptionTab = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig['images']['product']));
                    dump('$descriptionTab', $descriptionTab);
                    die();
                    $descriptionTab->click();
                    $datas = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig['images']['product']));

                    foreach ($configArray as $key => $value) {
                        $itemDetails[$key] = $value;
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

                    dump('$itemDetails default', $itemDetails);
                    die();
                    break;
            }
        }

        $itemDetails['title'] = $this->getItemTitle($itemDetails);
        $itemDetails['categories'] = $this->getItemCategories($categoryName, explode("\n", $itemDetails['type']));

        dump('$itemDetails', $itemDetails);
        return $itemDetails;
    }

    private function getGlobalInfos(array &$itemDetails, array $configArray)
    {
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
            }
        }
    }

    private function getElementText($textElement) : string
    {
        $text = $textElement[0]->getText();
        return str_replace("Product code: ", "", $text);
    }

    private function getItemTitle(array $itemDetails): string
    {
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
    ): array
    {
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
     * @return array|false|int|\WP_Error|\WP_Term|null
     *
     * It will return an integer
     * if no parent category => 0
     * if parent category => the id
     */
    private function getParentCategory (string $categoryName, array $type): \WP_Term|\WP_Error|bool|array|int|null
    {
        if ($categoryName === 'accessories') {
            // Accessories is the only other parent category with outdoor and indoor
            return get_term_by('slug', 'accessoires', 'product_categories');
        } elseif (in_array('outdoor', $type)) {
            return get_term_by('slug', 'mobilier-exterieur', 'product_categories');
        } else {
            return get_term_by('slug', 'mobilier-interieur', 'product_categories');
        }
    }

}
