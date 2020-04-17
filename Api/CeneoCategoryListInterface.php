<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */
namespace Orba\Ceneopl\Api;

use Orba\Ceneopl\Helper\SearchCriteriaInterface;

/**
 * @api
 */
interface CeneoCategoryListInterface
{
    /**
     * Get category list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\CategorySearchResultsInterface
     * @since 101.1.0
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
