<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model;

use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\CategoryListInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

/**
 * Class Data
 * @package Orba\Ceneopl\Model
 */
class Data
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * Data constructor.
     * @param CollectionFactory $productCollectionFactory
     * @param SortOrderBuilder $sortOrderBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CategoryListInterface $categoryList
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        SortOrderBuilder $sortOrderBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CategoryListInterface $categoryList

    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->categoryList = $categoryList;
    }

    /**
     * @return mixed
     */
    public function getProductsForCeneoFeed()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id = entity_id',
            null,
            'left');
        $collection->addAttributeToFilter('visibility',
            array('neq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE));
        $collection->addAttributeToFilter('status',
            array('eq' => \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED));

        $categoryIds = $this->getCategoriesForCeneo();

        // Add OR condition:
        $collection->addAttributeToFilter(array(
            array(
                'attribute' => 'ceneo_category_id',
                'notnull' => true,
            ),
            array(
                'attribute' => 'category_id',
                'in' => $categoryIds,
            ),
        ), '', 'left');

        $collection->getSelect()->group('e.entity_id');
        return $collection->addMediaGalleryData()->getItems();
    }

    /**
     * @return array
     */
    public function getCategoriesForCeneo()
    {

        $sortOrder = $this->sortOrderBuilder->setField('entity_id')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $this->searchCriteriaBuilder
            ->addFilter('ceneo_category_map', true, 'notnull')
            ->setSortOrders([$sortOrder]);

        $criteria = $this->searchCriteriaBuilder->create();
        $categoryList = $this->categoryList->getList($criteria)->getItems();

        $arrItems = [];
        foreach ($categoryList as $item) {
            $arrItems[] = $item->getEntityId();
        }
        return $arrItems;
    }

    /**
     * @param ProductInterface $product
     * @return bool|array
     */
    public function getCeneoCatNameFromProduct(ProductInterface $product)
    {

        $filter = $product->getCategoryIds();

        $sortOrder = $this->sortOrderBuilder->setField('entity_id')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $this->searchCriteriaBuilder
            ->addFilter('entity_id', $filter, 'in')
            ->setSortOrders([$sortOrder]);

        $criteria = $this->searchCriteriaBuilder->create();
        $categoryItems = $this->categoryList->getList($criteria)->getItems();

        $result = [];
        foreach ($categoryItems as $key => $value) {
            if ($value->getCeneoCategoryMap()) {
                $result[$key]['entity_id'] = $value->getEntityId();
                $result[$key]['ceneo_category_map'] = $value->getCeneoCategoryMap();
            }
        }
        arsort($result);

        foreach ($result as $key => $value) {
            if (isset ($value['ceneo_category_map'])) {
                return $value['ceneo_category_map'];
            }
        }
        return false;
    }
}