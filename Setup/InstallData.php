<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;


/**
 * Class InstallData
 * @package Orba\Ceneopl\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeCode = 'ceneo_category_id';
        $properties = [
            'type' => 'int',
            'label' => 'Ceneo Category',
            'global'   => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'required' => false,
            'input' => 'select',
            'visible_on_front' => false,
            'source' => 'Orba\Ceneopl\Model\Source\Category',
            'used_in_product_listing' => false,
            'sort_order' => 200
        ];

        $eavSetup->addAttribute($entityTypeId, $attributeCode, $properties);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'ceneo_category_map', [
            'type'     => 'varchar',
            'label'    => 'Ceneo category',
            'input'    => 'select',
            'required' => false,
            'sort_order' => 50,
            'global'   => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group'    => 'General Information',
        ]);

        $setup->endSetup();
    }

}