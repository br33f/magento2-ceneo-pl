<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Controller\Feed;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Orba\Ceneopl\Model\Offers;
use Orba\Ceneopl\Model\GenerateXML;
use Orba\Ceneopl\Helper\Config;

/**
 * Class Product
 * @package Orba\Ceneopl\Controller\Repository
 */
class Product extends Action
{
    /**
     * @var GenerateXML
     */
    protected $generateXML;

    /**
     * @var Config
     */
    protected $configHelper;

    /**
     * @var Offers
     */
    private $offers;

    /**
     * Product constructor.
     * @param Context $context
     * @param Offers $offers
     * @param GenerateXML $generatexml
     * @param Config $configHelper
     */
    public function __construct(
        Context $context,
        Offers $offers,
        GenerateXML $generatexml,
        Config $configHelper
    )
    {
        parent::__construct($context);
        $this->offers = $offers;
        $this->generateXML = $generatexml;
        $this->configHelper = $configHelper;
    }

    public function execute()
    {
        $hash = $this->getRequest()->getParam('hash');
        if ($hash == $this->configHelper->getHash()) {
            $array = $this->offers->getOffersArray();
            $xml = $this->generateXML->generate($array);
            $this->outputXML($xml);
        } else {
            $this->_redirect('/');
        }
    }

    /**
     * @param $xml
     */
    private function outputXML($xml)
    {
        $this->getResponse()
            ->setHeader('Content-Type', 'text/xml')
            ->setBody($xml->saveXML());
        return;
    }

}