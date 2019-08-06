<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Ceneocategory
 * @package Orba\Ceneopl\Model\ResourceModel
 */
class Ceneocategory extends AbstractDb
{


    /**
     *
     */
    protected function _construct()
    {
        $this->_init('orba_ceneo_category', 'id');
    }

    /**
     * @param $data
     */
    public function addRelations($data)
    {
        $this->getConnection()
            ->insertMultiple('orba_ceneo_category', $data);
    }
}