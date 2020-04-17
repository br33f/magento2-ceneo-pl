<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model\Source;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Orba\Ceneopl\Api\CeneoCategoryListInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\DB\Helper as DbHelper;
use Magento\Catalog\Model\Category as CategoryModel;
use Orba\Ceneopl\Helper\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Stdlib\ArrayManager;

/**
 * Class Category
 * @package Orba\Ceneopl\Model\Source
 */
class Category extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * Category tree cache id
     */
    const CATEGORY_TREE_ID = 'CATALOG_CENEO_CATEGORY_TREE';


    /**
     *
     */
    const TREE_ROOT_ID = '0';

    /**
     * @var \Magento\Framework\App\Cache\Type\Config
     */
    protected $_configCacheType;


    /**
     * @var CeneoCategoryListInterface
     */
    private $ceneoCategoryList;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SerializerInterface
     */
    private $serializer;


    /**
     * Category constructor.
     * @param CeneoCategoryListInterface $ceneoCategoryList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param DbHelper $dbHelper
     * @param UrlInterface $urlBuilder
     * @param ArrayManager $arrayManager
     * @param SerializerInterface $serializer
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     */
    public function __construct(
        CeneoCategoryListInterface $ceneoCategoryList,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CategoryCollectionFactory $categoryCollectionFactory,
        DbHelper $dbHelper,
        UrlInterface $urlBuilder,
        ArrayManager $arrayManager,
        SerializerInterface $serializer,
        \Magento\Framework\App\Cache\Type\Config $configCacheType
    )
    {
        $this->ceneoCategoryList = $ceneoCategoryList;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->dbHelper = $dbHelper;
        $this->urlBuilder = $urlBuilder;
        $this->arrayManager = $arrayManager;
        $this->serializer = $serializer;
        $this->_configCacheType = $configCacheType;
    }


    /**
     * @return array|bool|float|int|null|string
     */
    public function getAllOptions()
    {

        $cacheKey = self::CATEGORY_TREE_ID;
        if ($cache = $this->_configCacheType->load($cacheKey)) {
            $this->_options = $this->serializer->unserialize($cache);
        } else {
            $options = $this->getCategoriesFlat();
            asort($options);
            $emptyLabel = ' ';
            if ($emptyLabel !== false && count($options) > 0) {
                array_unshift($options, ['value' => '', 'label' => $emptyLabel]);
            }
            $this->_options = $options;
            $this->_configCacheType->save($this->serializer->serialize($this->_options), $cacheKey);
        }
        return $this->_options;
    }


    /**
     * @return array
     */
    public function getCategoriesFlat()
    {
        $categoryById = [];
        foreach ($this->getCategoryItems() as $category) {
            $categoryById[$category->getId()]['label'] = $category->getName();
            foreach (explode('/', $category->getPath()) as $parentId) {
                $categoryById[$category->getId()]['parents'][$parentId] = &$categoryById[$parentId]['label'];
            }
        }

        $options = [];
        foreach ($categoryById as $key => $value) {
            if (isset($value['parents'])) {
                $options[$key] = ['label' => implode(" / ", $value['parents']), 'value' => $key];
            }
        }
        return $options;
    }

    /**
     * @return \Magento\Framework\Api\ExtensibleDataInterface[]
     */
    public function getCategoryItems()
    {
        $criteria = $this->searchCriteriaBuilder->create();
        $categories = $this->ceneoCategoryList->getList($criteria);
        return $categories->getItems();
    }


    /**
     * @param null $filter
     * @return mixed
     */
    protected function getCategoriesTree($filter = null)
    {

        $criteria = $this->searchCriteriaBuilder->create();
        $collection = $this->ceneoCategoryList->getList($criteria);

        $categoryById = [
            self::TREE_ROOT_ID => [
                'value' => self::TREE_ROOT_ID,
                'optgroup' => null,
            ],
        ];

        foreach ($collection->getItems() as $category) {
            foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                if (!isset($categoryById[$categoryId])) {
                    $categoryById[$categoryId] = ['value' => $categoryId];
                }
            }

            $categoryById[$category->getId()]['label'] = $category->getName();
            $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
        }

        $this->getCacheManager()->save(
            $this->serializer->serialize($categoryById[CategoryModel::TREE_ROOT_ID]['optgroup']),
            self::CATEGORY_TREE_ID . '_' . $filter,
            [
                \Magento\Catalog\Model\Category::CACHE_TAG,
                \Magento\Framework\App\Cache\Type\Block::CACHE_TAG
            ]
        );

        return $categoryById[self::TREE_ROOT_ID]['optgroup'];
    }
}