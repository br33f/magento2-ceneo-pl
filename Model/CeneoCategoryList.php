<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model;

use Orba\Ceneopl\Api\Data\CeneocategorySearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Orba\Ceneopl\Model\ResourceModel\Ceneocategory\CollectionFactory;
use Orba\Ceneopl\Helper\SearchCriteria\CollectionProcessorInterface;
use Orba\Ceneopl\Api\CeneoCategoryListInterface;


/**
 * Class CeneoCategoryList
 * @package Orba\Ceneopl\Model
 */
class CeneoCategoryList implements CeneoCategoryListInterface
{


    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CeneocategorySearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * CeneoCategoryList constructor.
     * @param CollectionFactory $collectionFactory
     * @param CeneocategorySearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CeneocategorySearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }


    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\CategorySearchResultsInterface|\Orba\Ceneopl\Api\Data\CeneocategorySearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $collection->load();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

}