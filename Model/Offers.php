<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\CatalogInventory\Api\StockStatusRepositoryInterface;
use Magento\CatalogInventory\Api\StockStatusCriteriaInterface;
use Orba\Ceneopl\Model\Source\Category;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Api\CategoryListInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Class Offers
 * @package Orba\Ceneopl\Model
 */
class Offers
{

    /**
     *
     */
    const CENEO_ATTR_PATH = 'ceneopl/attr_';

    /**
     * @var array
     */
    protected $offers = [];
    /**
     * @var
     */
    protected $categories;
    /**
     * @var ScopeConfigInterface
     */
    protected $_config;
    /**
     * @var
     */
    protected $products;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;
    /**
     * @var StockStatusCriteriaInterface
     */
    private $statusCriteria;
    /**
     * @var StockStatusRepositoryInterface
     */
    private $stockStatusRepository;
    /**
     * @var Category
     */
    private $ceneocategory;
    /**
     * @var Mapping
     */
    private $mapping;
    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    private $stockRegistry;
    /**
     * @var CategoryListInterface
     */
    private $categoryList;
    /**
     * @var FilterGroup
     */
    private $filterGroup;
    /**
     * @var FilterGroupBuilder
     */
    private $filterGroupBuilder;
    /**
     * @var SearchCriteriaInterface
     */
    private $searchCriteriaInterface;
    /**
     * @var Data
     */
    private $dataProducts;

    /**
     * Offers constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param StockStatusRepositoryInterface $stockStatusRepository
     * @param StockStatusCriteriaInterface $statusCriteria
     * @param Category $ceneocategory
     * @param ScopeConfigInterface $_config
     * @param Mapping $mapping
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param CategoryListInterface $categoryList
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param FilterGroup $filterGroup
     * @param SearchCriteriaInterface $searchCriteriaInterface
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param Data $dataProducts
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        SortOrderBuilder $sortOrderBuilder,
        StockStatusRepositoryInterface $stockStatusRepository,
        StockStatusCriteriaInterface $statusCriteria,
        Category $ceneocategory,
        ScopeConfigInterface $_config,
        Mapping $mapping,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        CategoryListInterface $categoryList,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        FilterGroup $filterGroup,
        SearchCriteriaInterface $searchCriteriaInterface,
        FilterGroupBuilder $filterGroupBuilder,
        Data $dataProducts,
        \Magento\Store\Model\StoreManagerInterface $storeManager

    )
    {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->stockStatusRepository = $stockStatusRepository;
        $this->statusCriteria = $statusCriteria;
        $this->ceneocategory = $ceneocategory;
        $this->_config = $_config;
        $this->mapping = $mapping;
        $this->stockRegistry = $stockRegistry;
        $this->categoryList = $categoryList;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->filterGroup = $filterGroup;
        $this->searchCriteriaInterface = $searchCriteriaInterface;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->dataProducts = $dataProducts;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function getOffersArray()
    {
        $offers = [];

        // array of Ceneo categories Names
        $this->categories = $this->ceneocategory->getCategoriesFlat();

        // get products for ceneo feed
        $products = $this->dataProducts->getProductsForCeneoFeed();

        foreach ($products as $product) {
            $stock = $this->stockRegistry->getStockItem($product->getId());

            $product->setStockForCeneo($stock);
            if (!$this->checkStockForCeneo($product)) continue;

            $offers[$this->getGroup($product)][] = [
                'id' => $product->getSku(),
                'url' => $product->getProductUrl(),
                'price' => $product->getFinalPrice($product),
                'name' => $product->getName(),
                'desc' => $product->getDescription() ? $product->getDescription() : $product->getShortDescription(),
                'weight' => $product->getWeight(),
                'imgs' => $this->getImgs($product),
                'cat' => $this->getCeneoCategoryName($product),
                'group_attrs' => $this->getGroupAttrs($product),
                'core_attrs' => $this->getCoreAttrs($product)
            ];
            $this->offers = $offers;
        }
        return $this->offers;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     *
     */
    protected function checkStockForCeneo(ProductInterface $product)
    {
        $stock = $product->getStockForCeneo();

        if (!$stock->getIsInStock() && $stock->getManageStock()) {
            return false;
        }
        return true;
    }

    /**
     * @param ProductInterface $product
     * @return string
     */
    protected function getGroup(ProductInterface $product)
    {
        $group = 'other';
        return $group;
    }

    /**
     * @param ProductInterface $product
     * @return array
     */
    public function getImgs(ProductInterface $product)
    {
        $array = [];
        $images = $product->getMediaGalleryImages();

        if ($images) {
            foreach ($images->getItems() as $image) {

                if ($image->getUrl()) {
                    if (!isset ($array['main'])) {
                        $array['main'] = $image->getUrl();
                        continue;
                    }
                    $array['i'] = $image->getUrl();
                    break;
                }
            }
        }
        return $array;
    }


    /**
     * @param ProductInterface $product
     * @return string
     */
    protected function getCeneoCategoryName(ProductInterface $product)
    {
        if (isset($this->categories[$product->getCeneoCategoryId()])) {
            return $this->categories[$product->getCeneoCategoryId()];
        }

        $arrayCategory = $this->dataProducts->getCeneoCatNameFromProduct($product);
        if ($arrayCategory) {
            $result = $this->categories[$arrayCategory]?? '';
            return $result;
        }
    }

    /**
     * @param ProductInterface $product
     * @return mixed|null
     */
    protected function getGroupAttrs(ProductInterface $product)
    {
        return $this->mapping->getMapping('other', $product);

    }


    //get ceneo cat

    /**
     * @param ProductInterface $product
     * @return mixed|null
     */
    protected function getCoreAttrs(ProductInterface $product)
    {
        return $this->mapping->getMapping('core', $product);
    }

}