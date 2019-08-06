<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model;

/**
 * Class Feed
 * @package Orba\Ceneopl\Model
 */
class Feed extends \Magento\Framework\Model\AbstractExtensibleModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Orba\Ceneopl\Model\ResourceModel\Feed');
    }

    /**
     * @return array
     */
    public function getCustomAttributesCodes()
    {
        return array('store_id','name','feed');
    }
}