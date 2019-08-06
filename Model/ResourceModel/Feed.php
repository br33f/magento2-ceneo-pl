<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model\ResourceModel;


/**
 * Class Feed
 * @package Orba\Ceneopl\Model\ResourceModel
 */
class Feed extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

     protected function _construct()
    {
        $this->_init('store', 'store_id');
    }
}
