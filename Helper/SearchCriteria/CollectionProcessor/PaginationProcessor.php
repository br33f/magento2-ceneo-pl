<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Orba\Ceneopl\Helper\SearchCriteria\CollectionProcessor;

use Orba\Ceneopl\Helper\SearchCriteria\CollectionProcessorInterface;
use Orba\Ceneopl\Helper\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDb;

class PaginationProcessor implements CollectionProcessorInterface
{
    /**
     * Apply Search Criteria Pagination to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param AbstractDb $collection
     * @return void
     */
    public function process(SearchCriteriaInterface $searchCriteria, AbstractDb $collection)
    {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
