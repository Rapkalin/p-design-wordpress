<?php

namespace Scrapping\websites;

use Scrapping\ScrappingBase;
use Scrapping\ScrappingInterface;

class Fermob extends ScrappingBase implements ScrappingInterface
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
                    'id' => 'product-item-link',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'outdoor' => 'https://www.fermob.com/fr/mobilier/chaises-repas/chaises.html',
                        'indoor' => 'https://www.fermob.com/fr/mobilier/mobilier-d-interieur/chaises-repas.html',
                    ],
                ],
                'stools' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'outdoor' => 'https://www.fermob.com/fr/mobilier/chaises-repas/tabourets.html',
                        'indoor' => 'https://www.fermob.com/fr/mobilier/chaises-repas/tabourets-de-bar.html',
                    ],
                ],
                'sofas' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.fermob.com/fr/mobilier/fauteuils-canapes.html',
                    ],
                ],
                'tables' => [
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                    'type' => [ // indoor && outdoor || all
                        'all' => 'https://www.fermob.com/fr/mobilier/tables.html',
                    ],
                ]
            ],
            'product' => [
                'reference_prefix' => 'FER',
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
            'turn-pages' => 'next',
            'scroll-down' => false
        ];
    }

    /**
     * @return string
     */
    public function getWebsiteName(): string {
        return 'fermob';
    }
}
