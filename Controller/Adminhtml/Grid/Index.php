<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;

/**
 * Class Index
 * @package Orba\Ceneopl\Controller\Adminhtml\Grid
 */
class Index extends Action
{
    /**
     * Verify permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Orba_Ceneopl::feed_list');
    }


    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        return $this->resultFactory->create('page');
    }

}