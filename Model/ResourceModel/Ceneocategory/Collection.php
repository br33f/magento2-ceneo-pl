<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model\ResourceModel\Ceneocategory;
use Magento\Framework\Api\Search\SearchResultInterface;


/**
 * Class Collection
 * @package Orba\Ceneopl\Model\ResourceModel\Ceneocategory
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    implements SearchResultInterface
{


    protected function _construct()
    {
        $this->_init(
            'Orba\Ceneopl\Model\Ceneocategory',
            'Orba\Ceneopl\Model\ResourceModel\Ceneocategory'
        );
    }


    /**
     * @return mixed
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }


    /**
     * @param \Magento\Framework\Api\Search\AggregationInterface $aggregations
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }


    /**
     * @return mixed
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }


    /**
     * @param \Orba\Ceneopl\Helper\SearchCriteriaInterface|null $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(\Orba\Ceneopl\Helper\SearchCriteriaInterface $searchCriteria = null)
    {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }


    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }


    /**
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        $this->setSize($totalCount);
        return $this;
    }


    /**
     * @param array|null $items
     * @return $this
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

}