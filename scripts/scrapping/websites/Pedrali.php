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
                    'url' => 'https://www.pedrali.com/fr-fr/produits/chaises-design',
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                ],
            ],
            'product' => [
                'title' => 'titles--label',
                'id' => 'titles--sub',
                'description' => 'intro--text__descr',
                'price' => '',
                'image' => [
                    'mutliple' => true,
                    'cover' => 'intro--image__box',
                    'gallery' => 'intro--image__box',
                ],
                'finitions' => '',
                'dimensions' => [
                    'image' => true,
                ],
            ],
            'cookie-banner' => [
                'id' => 'onetrust-banner-sdk',
                'rejectButtonId' => 'onetrust-reject-all-handler'
            ],
        ];
    }

    public function getWebsiteName(): string
    {
        return 'pedrali';
    }
}
