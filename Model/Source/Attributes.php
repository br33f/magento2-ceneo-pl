<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model\Source;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Orba\Ceneopl\Helper\SearchCriteriaBuilder;
use Magento\Framework\Option\ArrayInterface;


/**
 * Class Attributes
 * @package Orba\Ceneopl\Model\Source
 */
class Attributes implements ArrayInterface
{

    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;


    /**
     * Attributes constructor.
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProductAttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductAttributeRepositoryInterface $attributeRepository

    )
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $criteria = $this->searchCriteriaBuilder->create();
        $attributes = $this->attributeRepository->getList($criteria)->getItems();

        $options = [];
        foreach ($attributes as $key => $value) {
            $options[] = ['label' => $value->getAttributeCode(), 'value' => $value->getAttributeCode()];
        }

        $emptyLabel = ' ';
        if ($emptyLabel !== false && count($options) > 0) {
            array_unshift($options, ['value' => '', 'label' => $emptyLabel]);
        }
        return $options;
    }

}