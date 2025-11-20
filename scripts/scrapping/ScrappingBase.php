<?php

namespace Scrapping;

use Exception;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/*
 * Load the WordPress environment
 * So we have access to WP and ACF functions
 */
if (!defined('WPPATH')) {
    define( 'WPPATH', __DIR__ . '/../../website/wordpress-core/wp-load.php' );
    if (file_exists(WPPATH)) {
        require WPPATH;
        echo 'Wordpress successfully loaded for: ' . get_bloginfo() . "\n\n";
    } else {
        die('Failed to load Wordpress.');
    }
}

class ScrappingBase
{
    /**
     * The webDriver
     *
     * @var RemoteWebDriver
     */
    protected RemoteWebDriver $webDriver;

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
    protected scrappingUtils $scrappingUtils;

    /**
     * Array of field keys
     *
     * @var array
     */
    private array $acfFieldKeys = [
        'product_banner',
        'product_featured_image',
        'product_description',
        'product_colors',
        'product_images',
        'product_more',
        'product_details',
        'product_reference',
        'product_url_reference',
        'product_on_order',
    ];

    protected array $categories;

    /**
     * Mapping between category name in code and contributed category name
     *
     * @var array|string[] 
     */
    protected array $pDesingCategories = [
        'accessories' => 'Accessoires',
        'chairs' => 'Chaises & fauteuils',
        'chairs-lounge' => 'Banquettes & canapés',
        'stools' => 'Tabourets & poufs',
        'sofas' => 'Banquettes & canapés',
        'tables' => 'Tables',
        'tables-small' => 'Tables',
        'tables-bottom' => 'Tables',
        'table-legs' => 'Piétements de tables',
        'tops' => 'Plateaux de table',
    ];

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
        $this->scrappingUtils = new scrappingUtils(loadWordpressMedia:true);
        $this->categories = get_terms([
            'taxonomy' => 'product_categories',
            'hide_empty' => false,
        ]);
    }

    /**
     * Return the website configuration
     * This array is an example and must be overriden in each website class
     *
     * @return array
     */
    public function getConfig(): array {
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
                     * Ex for all:
                     * 'type' => [ // indoor / outdoor / both
                     * 'all' => 'https://www.pedrali.com/fr-fr/produits/chaises-design',
                     * ],
                     *
                     */
                ],
            ],
            'product' => [
                'reference_prefix' => 'XXX',
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
    private function initWebDriver(string $host): RemoteWebDriver {
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
    protected function getBrowserTab(string $url): void {
        // Go to the URL and retrieve it
        $this->webDriver->switchTo()->newWindow();
        $this->webDriver->get($url);
        $this->webDriver->manage()->window()->maximize(); // @todo: when ready, change it to minimize

        // Wait the page to load
        // @todo: Check if there is an element to check to confirm that the page is loaded
        // @todo: Update DB with cron every hour
        sleep(3);
    }

    /**
     * @throws Exception
     */
    public function ScrapProductUrls(array $productUrls): void {
        foreach ($productUrls as $productUrl) {
            $productDetails = $this->getProductDetails($productUrl['url'], $productUrl['category_name']);
            $this->saveProduct($productDetails);
        }
    }

    /**
     * Scrap & save all the products urls from a category
     *
     * @return true
     * @throws Exception
     */
    public function scrapCategoryUrls(): bool {
        $categories = $this->websiteConfig['categories'];

        foreach ($categories as $categoryName => $category) {
            $categoryUrls = [];
            foreach ($category['type'] as $categoryUrl) {
                $urls = is_array($categoryUrl) ? $categoryUrl : [$categoryUrl];
                foreach ($urls as $singleUrl) {
                    echo "Getting category urls for $categoryName\n";
                    $itemUrls = $this->getCategoryUrls($category, $singleUrl, $categoryName);
                    $categoryUrls = array_merge($categoryUrls, $itemUrls);
                }
            }

            if (!$categoryUrls) {
                echo "No urls found for $categoryName\n";
                continue;
            }

            $categoryUrls = array_values(array_unique($categoryUrls));
            echo 'Saving category urls...' . "\n";
            $this->scrappingUtils->saveCategoryUrls($categoryUrls, $categoryName, $this->websiteName);
        }

        try {
            echo "Trying to quit browser... \n";
            // Close the browser
            $this->webDriver->quit();
            echo "Browser quit successfully... \n";
            return true;

        } catch (Exception $e) {
            echo "Error while trying to quit webdriver \n";
            throw new Exception("Quitting browser error: " . $e->getMessage());
        }
    }

    /**
     * Save the product and the corresponding acf fields in the database
     *
     * @param array $productDetails
     * @return bool
     */
    private function saveProduct(array $productDetails): bool {
        echo "Saving product: " . $productDetails['title'] . PHP_EOL;
        $postId = $this->savePost($productDetails);

        if ($postId) {
            $this->saveAcfFields($productDetails, $postId);
            echo 'Product successfully saved in database at id: ' . $postId . PHP_EOL . PHP_EOL;
            return true;
        } else {
            echo "Error while saving ACF fields for product: {$productDetails['title']} at id $postId \n";
            return false;
        }

    }

    /**
     * @return array
     */
    private function getFieldKeys(): array {
        $keys = [];
        foreach ($this->acfFieldKeys as $field) {
            $fieldDetails = acf_get_field($field);

            if (!$fieldDetails) {
                echo 'Field ' . $field . ' does not exist in database. Skipping...' . "\n";
                continue;
            }
            $keys[$fieldDetails['name']] = ['key' => $fieldDetails['key']];

            if (isset($fieldDetails['sub_fields'])) {

                foreach ($fieldDetails['sub_fields'] as $subField) {
                    /*
                     * Here we add the subfield's keys of the current field
                     * It will look like that:
                     *     "product_details" => array:2 [
                              "key" => "field_62b9bf2bdee16"
                              "subkeys" => array:2 [
                                "key" => "field_62b9bf3cdee17"
                                "value" => "field_62b9bf4cdee18"
                              ]
                            ]
                     */
                    $keys[$fieldDetails['name']]['subkeys'][$subField['name']] = $subField['key'];
                }
            }
        }

        return $keys;
    }

    /**
     * @param array $itemDetails
     * @param int $postId
     * @return void
     */
    private function saveAcfFields (
        array $itemDetails,
        int $postId
    ): void {
        $acfFieldKeys = $this->getFieldKeys();

        // dump('$acfFieldKeys', $acfFieldKeys);
        // dump('$itemDetails', $itemDetails);

        foreach ($acfFieldKeys as $keyType => $key) {
            switch ($keyType) {
                case 'product_banner':
                    if (isset($itemDetails['images-cover']) && $itemDetails['images-cover']) {
                        // We always take the first image by default for the cover
                        $imageId = $this->scrappingUtils->downloadImage(
                            $itemDetails['images-cover'][0],
                            $postId,
                            acf_slugify($itemDetails['title']) . '-p-design-image'
                        );
                        update_field($key['key'], $imageId, $postId);
                    }
                    break;
                case 'product_featured_image':
                    if (isset($itemDetails['image-product']) && $itemDetails['image-product']) {
                        $imageId = $this->scrappingUtils->downloadImage(
                            $itemDetails['image-product'],
                            $postId,
                            acf_slugify($itemDetails['title']) . '-p-design-image'
                        );
                        update_field($key['key'], $imageId, $postId);
                    }
                    break;
                    break;
                case 'product_description':
                    if (isset($itemDetails['description']) && $itemDetails['description']) {
                        update_field($key['key'], $itemDetails['description'], $postId);
                    }
                    break;
                case 'product_colors':
                    if (isset($itemDetails['colors']) && $itemDetails['colors']) {
                        update_field($key['key'], $itemDetails['colors'], $postId);
                    }
                    break;
                case 'product_images':
                    if (isset($itemDetails['images-cover'])) {
                        $imageIds = [];
                        foreach ($itemDetails['images-cover'] as $imageCoverUrl) {
                            $imageIds[] = $this->scrappingUtils->downloadImage(
                                $imageCoverUrl,
                                $postId,
                                acf_slugify($itemDetails['title']) . '-p-design-image'
                            );
                        }
                        echo 'imageIds update product images: ' . implode(',', $imageIds) . "\n";
                        update_field($key['key'], $imageIds, $postId);
                    }
                    break;
                case 'product_details':
                    $productDetails = [
                        'weight' => 'Poids',
                        'width' => 'Largeur',
                        'height' => 'Hauteur',
                        'depth'=> 'Profondeur',
                        'order-only' => 'Sur commande',
                        'in-stock' => 'En stock'
                    ];

                    if (isset($itemDetails['technical-data'])) {
                        foreach ($itemDetails['technical-data'] as $label => $value) {
                         $this->addAcfRepeaterRow($value, $key, $postId, $label);
                        }
                    } else {
                        $i = 0;
                        foreach ($productDetails as $keyDetail => $trad) {
                            if (isset($itemDetails[$keyDetail]) && $itemDetails[$keyDetail]) {
                                $this->addAcfRepeaterRow($itemDetails[$keyDetail], $key, $postId, $trad);
                            } else {
                                $this->addAcfRepeaterRow($itemDetails[$i], $key, $postId, $itemDetails[$i]);
                                $i++;
                            }
                        }
                    }
                    break;
                case 'product_url_reference':
                    update_field($key['key'], $itemDetails['product-url'], $postId);
                    break;
                case 'product_reference':
                    $productReference = $itemDetails['reference_prefix'] . $itemDetails['title'] . $itemDetails['reference'];
                    update_field($key['key'], strtoupper($productReference), $postId);
                    break;
                case 'product_on_order':
                    update_field($key['key'], true, $postId);
                    break;
                case 'product_price':
                    if(isset($itemDetails['price']) && $itemDetails['price']) {
                        update_field($key['key'], $itemDetails['price'], $postId);
                    }
                    break;
                default:
                    break;
                // no case 'product_more'.
                // no case 'product_stock'. They have to be completed manually
            }
        }
    }

    /**
     * @param $rowDetails
     * @param array $key
     * @param int $postId
     * @param string $trad
     * @return void
     */
    private function addAcfRepeaterRow (
        $value,
        array $key,
        int $postId,
        string $trad
    ): void {
        // For update_sub_field $postId needs to be in an array
        $row = [
            $key['subkeys']['key'] => $trad,
            $key['subkeys']['value'] => $value
        ];
        add_row($key['key'], $row, $postId);
    }

    /**
     * @param array $itemDetails
     * @return false|int|\WP_Error
     */
    private function savePost (array $itemDetails): \WP_Error|bool|int {
        $postData = [
            'post_title' => $itemDetails['title'],
            'post_name' => acf_slugify($itemDetails['title']),
            'post_type' => 'produits',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'post_author' => 1,
        ];
        // dump('$post_data', $postData);
        $postId = wp_insert_post($postData);
        if (!is_wp_error($postId)) {
            //the post is valid
            $this->saveCategories($itemDetails['categories'], $postId);
            echo 'Item: ' . $itemDetails['title'] .  ' => successfully saved at id: ' . $postId . "\n";
            return $postId;
        } else {
            //there was an error in the post insertion,
            echo 'Item: ' . $itemDetails['title'] .  ' => failed to be saved at id: ' . $postId . "\n";
            echo $postId->get_error_message();
            return false;
        }
    }

    private function saveCategories(array $categoryIds, int $postId): void {
        wp_set_object_terms($postId, $categoryIds, 'product_categories');
        echo "Categories saved for post ID $postId: " . implode(',', $categoryIds) . "\n";
    }

    public function closeBrowser()
    {
        try {
            echo "Trying to quit browser... \n";
            // Close the browser
            $this->webDriver->quit();
            echo "Browser quit successfully... \n";
            return true;
        } catch (Exception $e) {
            echo "Error while trying to quit webdriver \n";
            throw new Exception("Quitting browser error: " . $e->getMessage());
        }
    }
}



