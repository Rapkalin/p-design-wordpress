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
    public function getWebsiteName(): string {
        return 'pedrali';
    }
}
