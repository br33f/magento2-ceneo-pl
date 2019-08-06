<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\Context;
use \Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Config
 * @package Orba\Ceneopl\Helper
 */
class Config extends AbstractHelper
{

    const XML_PATH_CENEO_HASH = 'ceneopl/config/hash';

    /**
     * @var ScopeConfigInterface
     */
    protected $_config;

    /**
     * @var WriterInterface
     */
    protected $_configWriter;


    /**
     * Config constructor.
     * @param ScopeConfigInterface $_config
     * @param WriterInterface $configWriter
     * @param Context $context
     */
    public function __construct(
        ScopeConfigInterface $_config,
        WriterInterface $configWriter,
        Context $context
    )
    {
        parent::__construct($context);
        $this->_config = $_config;
        $this->_configWriter = $configWriter;
    }

    /**
     * save hash
     */
    public function saveHash()
    {
        $hash = md5(microtime());
        $this->_configWriter->save(self::XML_PATH_CENEO_HASH, $hash);

    }

    /**
     * @return mixed
     */
    public function getHash(){

        return $this->_config->getValue(
            self::XML_PATH_CENEO_HASH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}