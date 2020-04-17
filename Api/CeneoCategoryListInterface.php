<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */
namespace Orba\Ceneopl\Api;

/**
 * @api
 */
interface CeneoCategoryListInterface
{
    /**
     * Get category list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\CategorySearchResultsInterface
     * @since 101.1.0
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
