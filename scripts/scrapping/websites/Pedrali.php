<?php

namespace Scrapping\websites;

use Exception;
use Facebook\WebDriver\WebDriverBy;
use Scrapping\ScrappingBase;
use Scrapping\ScrappingInterface;

class Pedrali extends ScrappingBase implements ScrappingInterface
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
                'accessories' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/accessoires-design',
                    ],
                ],
                'chairs' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/chaises-design',
                    ],
                ],
                'chairs-lounge' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/chaises-lounge',
                    ],
                ],
                'stools' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/tabourets-design-pouf',
                    ],
                ],
                'sofas' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/canape-design-banquettes',
                    ],
                ],
                'tables' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/tables-design',
                    ],
                ],
                'tables-small' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/tables-base-centrale',
                    ],
                ],
                'tables-bottom' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/tables-basses-design',
                    ],
                ],
                'table-legs' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/tables-base-centrale',
                    ],
                ],
                'tops' => [ // Plateaux de table
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/plateaux',
                    ],
                ]
            ],
            'product' => [
                'reference_prefix' => 'PED',
                'global-infos' => [
                    'title' => 'titles--label',
                    'description' => 'intro--text__descr',
                    'reference' => 'titles--sub', // constructor reference
                    'price' => false,
                    'type' => 'infobox--ambiti-list', // indoor / outdoor / both
                    'is_new' => false,
                ],
                'images' => [
                    'product' => 'intro--image__box', // Product image
                    'cover' => [ // Banner cover
                        'multiple' => true,
                        'gallery' => 'tns-carousel',
                        "xpath" => ".//div[@id='tns1']/div",
                        'single-img' => 'image--box',
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
            'scroll-down' => 'categoria--load-more',
        ];
    }

    /**
     * @return string
     */
    public function getName(): string {
        return 'pedrali';
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

        if (isset($this->getConfig()['scroll-down']) && $this->getConfig()['scroll-down']) {
            // Loading all items
            $this->scrappingUtils->scrollDown($this->webDriver, $this->getConfig()['scroll-down']);
            // End of loading all items
        }

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

        if(isset($this->getConfig()['turn-pages']) && $this->getConfig()['turn-pages']) {
            $this->scrappingUtils->turnPage(
                $this->webDriver,
                $this->getConfig()['turn-pages'],
                $category['id'],
                $categoryName,
                $itemUrls,
                $category['item-href-element']
            );
        }

        dump('$itemUrls', $itemUrls);
        die();

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
                    // Get the image for the product
                    if ($productWebsiteConfig['images']['product']) {
                        $imageBox = $this->webDriver->findElements(WebDriverBy::className($productWebsiteConfig['images']['product']));
                        $itemDetails['image-product'] = $imageBox[0]->findElement(WebDriverBy::tagName('span'))->getAttribute('data-img');
                    }

                    // Get the images for the cover
                    if ($productWebsiteConfig['images']['cover']) {
                        $itemDetails['images-cover'] = [];
                        if (
                            isset($productWebsiteConfig['images']['cover']['single'])
                            && $productWebsiteConfig['images']['cover']['single']
                        ) {
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
                case 'technical-data':
                    foreach ($configArray as $key => $value) {
                        $itemDetails[$key] = $value;
                    }
                    break;
                case 'reference_prefix':
                    $itemDetails[$configKey] = $configArray;
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
        $itemDetails['categories'] = $this->getItemCategories($categoryName, explode("\n", $itemDetails['type']));
        return $itemDetails;
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
