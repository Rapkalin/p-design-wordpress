<?php

namespace Scrapping\websites;

use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Scrapping\ScrappingBase;
use Scrapping\ScrappingInterface;

class Woodlab extends ScrappingBase implements ScrappingInterface
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
                    'id' => 'portfolio_link_class',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => ['https://woodlabpoland.com/portfolio-showcase/'],
                    ],
                ],
                'stools' => [
                    'id' => 'portfolio_link_class',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => ['https://woodlabpoland.com/barstool-showcase/'],
                    ],
                ],
                'sofas' => [
                    'id' => 'portfolio_link_class',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => [
                            'https://woodlabpoland.com/club-armchairs-showcase/',
                            'https://woodlabpoland.com/modern-armchairs-showcase/',
                            'https://woodlabpoland.com/bridge-armchairs/'
                        ],
                    ],
                ],
                'tables' => [
                    'id' => 'portfolio_link_class',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => [
                            'https://woodlabpoland.com/table-bases-2/'
                        ],
                    ],
                ]
            ],
            'product' => [
                'reference_prefix' => 'WOO',
                'global-infos' => [
                    // for xpath documentation: https://www.w3schools.com/xml/xpath_syntax.asp
                    'title' => "info_section_title",
                    'description' => ".//div[@class='wpb_wrapper']/ul/li",
                    'reference' => "info_section_title", // constructor reference
                    'price' => false,
                    'type' => [], // indoor / outdoor / both
                    'is_new' => false,
                ],
                'images' => [
                    'alternatives' => [ // Alternative views
                        'multiple' => true,
                        'gallery' => 'slides',
                        "xpath" => ".//li[@class='slide']",
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
        return 'woodlab';
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
            $try <= 5
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
        $itemDetails['images-cover'] = [];
        $itemDetails['image-product'] = '';

        try {
            foreach ($productWebsiteConfig as $configKey => $configArray) {
                switch ($configKey) {
                    case 'images':
                        // Get all available images for the product
                        $imageBox = $this->webDriver->findElements(WebDriverBy::xpath($configArray['alternatives']['xpath']));

                        if ($imageBox) {
                            $mainImage = $this->getImageUrl($imageBox[0]);
                            if ($mainImage) {
                                $itemDetails['image-product'] = $mainImage;
                                $itemDetails['images-cover'][] = $mainImage;

                                dd('$itemDetails $mainImage', $itemDetails);
                            }

                            // We take the images left for alternatives
                            $this->getAlternativeImages($itemDetails, $imageBox);
                        }
                        break;
                    case 'scroll-down':
                    case 'cookie-banner':
                        break;
                    case 'technical-data':
                        // No cookies banner to close
                        // $this->closeCookieBanner();
                        $datas = $this->webDriver->findElements(WebDriverBy::xpath(".//table//tbody//tr[2]"));
                        $itemDetails['technical-data'] = [
                            'weight' => false,
                            'width' => false,
                            'height' => false,
                            'depth'=> false,
                            'order-only' => false,
                            'in-stock' => 'En stock'
                        ];

                        foreach ($datas as $data) {
                            $children = $data->findElements(WebDriverBy::xpath(".//td"));
                            $itemDetails['technical-data']['height'] = $children[0]->getText();
                            $itemDetails['technical-data']['width'] = $children[1]->getText();
                            $itemDetails['technical-data']['depth'] = $children[2]->getText();
                        }
                        break;
                    case 'reference_prefix':
                        $itemDetails[$configKey] = $configArray;
                        break;
                    case 'global-infos':
                        $this->getGlobalInfos($itemDetails, $configArray);
                        break;
                    default:
                        dump('$configArray', $configArray);
                        dd('$configKey', $configKey);
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
        } catch (\Exception $e) {
            dd('Error while getting product details: ' . $itemUrl, $e->getMessage());
        }


        dd('$itemDetails', $itemDetails);
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
                $imageUrl = $this->getImageUrl($imageElement);
                if ($imageUrl) {
                    $itemDetails['images-cover'][] = $imageUrl;
                }
            }
        }

        if (isset($itemDetails['images-cover'])) {
            $itemDetails['images-cover'] = array_values(array_unique(array_filter($itemDetails['images-cover'])));
        }
    }

    private function getImageUrl($imageElement): string {
        try {
            $imgElement = $imageElement->findElement(WebDriverBy::tagName('img'));
        } catch (\Exception $e) {
            return '';
        }

        $attributesToCheck = ['data-src', 'src', 'data-srcset', 'srcset'];
        foreach ($attributesToCheck as $attribute) {
            $value = $imgElement->getAttribute($attribute);
            if ($value) {
                if (str_contains($attribute, 'srcset')) {
                    $sources = preg_split('/\s*,\s*/', $value);
                    if ($sources && isset($sources[0])) {
                        return trim(explode(' ', $sources[0])[0]);
                    }
                }
                return $value;
            }
        }

        return '';
    }

    private function getGlobalInfos(array &$itemDetails, array $configArray): void {
        foreach ($configArray as $key => $value) {
            switch ($key) {
                case 'title':
                case 'reference':
                    $textElement = $this->webDriver->findElements(WebDriverBy::className($value));
                    $itemDetails[$key] = $this->getElementText($textElement);
                    break;
                case 'description':
                    $textElements = $this->webDriver->findElements(WebDriverBy::xpath($value));
                    $description = '';

                    foreach ($textElements as $textElement) {
                        $description .= $textElement->getText() . PHP_EOL;
                    }
                    $itemDetails[$key] = $description;
                    break;
                default:
                    break;
            }
        }
    }

    private function getElementText($textElement, bool $stripProductCode = true) : string {
        if (!$textElement || !isset($textElement[0])) {
            return '';
        }

        $text = $textElement[0]->getText();
        return $stripProductCode ? str_replace("Product code: ", "", $text) : $text;
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

        $attempts = 0;
        while ($this->getCookieBanner() && $attempts < 5) {
            // We wait until the banner is gone
            sleep(1);
            $attempts++;
        }
    }

    private function getCookieBanner (): bool|RemoteWebElement {
        $cookieBanner = $this->webDriver->findElements(WebDriverBy::xpath(".//div[@id='cookie-notice']"));
        if (!$cookieBanner || !isset($cookieBanner[0])) {
            return false;
        }

        $attributes = $cookieBanner[0]->getAttribute('class');

        // if cookie banner is already hidden we do nothing
        if (str_starts_with($attributes, 'cookie-notice-hidden')) {
            return false;
        }

        $cookieButton = $this->webDriver->findElements(WebDriverBy::xpath(".//span[@id='cn-notice-buttons']/a"));
        return $cookieButton[0];
    }
}
