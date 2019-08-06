<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model;

use Magento\Framework\Model\AbstractModel;
use Orba\Ceneopl\Api\Data\CeneocategoryInterface;


/**
 * Class Ceneocategory
 * @package Orba\Ceneopl\Model
 */
class Ceneocategory extends AbstractModel implements CeneocategoryInterface
{

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('Orba\Ceneopl\Model\ResourceModel\Ceneocategory');
    }
    
}