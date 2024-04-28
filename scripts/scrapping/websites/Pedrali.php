<?php

namespace Scrapping\websites;

use Scrapping\ScrappingBase;

class Pedrali extends ScrappingBase
{
    public function __construct()
    {
        parent::__construct('pedrali', $this->getWebsiteConfig());
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
                'chairs' => [
                    'url' => 'https://www.pedrali.com/fr-fr/produits/chaises-design',
                    'id' => 'categoria--item',
                    'item-href-element' => 'href',
                ],
            ],
            'cookie-banner' => [
                'id' => 'onetrust-banner-sdk',
                'rejectButtonId' => 'onetrust-reject-all-handler'
            ],
        ];
    }
}
