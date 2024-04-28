<?php

namespace Scrapping;

interface ScrappingInterface
{
    function getWebsiteConfig();
    function getWebsiteName();
    function scrapWebsite();
    function getCategoryItems(array $category);
    function getProduct(string $itemUrl);
    function saveProducts(array $categoryItems);

}