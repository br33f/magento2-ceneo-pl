<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Orba\Ceneopl\Helper\SearchCriteria;

use Magento\Framework\Data\Collection\AbstractDb;
use Orba\Ceneopl\Helper\SearchCriteriaInterface;

/**
 * @api
 * @since 100.2.0
 */
interface CollectionProcessorInterface
{
    /**
     * Apply Search Criteria to Collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param AbstractDb $collection
     * @throws \InvalidArgumentException
     * @return void
     * @since 100.2.0
     */
    public function process(SearchCriteriaInterface $searchCriteria, AbstractDb $collection);
}
