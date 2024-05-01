<?php

namespace Scrapping\websites;

use Scrapping\ScrappingBase;
use Scrapping\ScrappingInterface;

class Pedrali extends ScrappingBase implements ScrappingInterface
{
    public function __construct() {
        parent::__construct($this->getWebsiteName(), $this->getWebsiteConfig());
    }

    /**
     * Return the website configuration
     *
     * @return array
     */
    public function getWebsiteConfig(): array {
        return [
            'categories' => [
                'chairs' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.pedrali.com/fr-fr/produits/chaises-design',
                    ],
                ],
            ],
            'product' => [
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
                ],
                'product-availability' => [
                    'order-only' => false,
                    'in-stock' => false,
                ],
            ],
            'cookie-banner' => [
                'id' => 'onetrust-banner-sdk',
                'rejectButtonId' => 'onetrust-reject-all-handler'
            ],
            'scroll-down' => true,
        ];
    }

    public function getWebsiteName(): string
    {
        return 'pedrali';
    }
}
