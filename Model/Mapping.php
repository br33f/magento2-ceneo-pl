<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;


/**
 * Class Mapping
 * @package Orba\Ceneopl\Model
 */
class Mapping
{
    /**
     *
     */
    const AVAIL_DEFAULT_VALUE = 99;
    /**
     *
     */
    const OFF_DEFAULT = 0;
    /**
     *
     */
    const ON_DEFAULT = 1;


    /**
     * @var array
     */
    public $groups = array(
        'core' => array('stock', 'avail', 'set', 'basket'),
        'other' => array('Producent', 'Kod_producenta', 'EAN')
    );

    /**
     * @var array
     */
    public $avail = array(1, 3, 7, 14);


    /**
     * @var
     */
    protected $product;

    /**
     * @var
     */
    protected $groupName;

    /**
     * @var ScopeConfigInterface
     */
    protected $_config;

    /**
     * Mapping constructor.
     * @param ScopeConfigInterface $_config
     */
    public function __construct(
        ScopeConfigInterface $_config
    )
    {
        $this->_config = $_config;
    }


    /**
     * @param string $groupName
     * @param ProductInterface $product
     * @param null $default
     * @return mixed|null
     */
    public function getMapping(string $groupName, ProductInterface $product, $default = null)
    {
        $this->product = $product;
        $this->groupName = $groupName;

        $arguments = $this->groups[$groupName] ?? $default;
        if (!is_array($arguments))
            return $default;
        $callback = $groupName . 'Callback';
        $array = method_exists($this, $callback) ? call_user_func([$this, $callback], $arguments) : $default;
        return $array;

    }

    /**
     * @param $arguments
     * @return array
     */
    protected function otherCallback($arguments)
    {
        $array = [];
        foreach ($arguments as $argument) {
            $value = $this->getConf("ceneopl/attr_$this->groupName/$argument");
            $data = $this->product->getData($value);
            if (!isset($data)) continue;
            $array[$argument] = $this->product->getData($value);
        }
        return $array;
    }

    /**
     * @param $conf
     * @return mixed
     */
    protected function getConf($conf)
    {
        return $this->_config->getValue(
            $conf,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $arguments
     * @return array
     */
    protected function coreCallback($arguments)
    {
        $array = [];
        foreach ($arguments as $argument) {
            if ($argument == 'stock') {
                $array[$argument] = $this->getStockData($argument);
                continue;
            }
            if ($argument == 'avail') {
                $array[$argument] = $this->getAvail($argument);
                continue;
            }

            $name = $this->getConf("ceneopl/attr_$this->groupName/$argument" . '_name');
            $value = $this->getConf("ceneopl/attr_$this->groupName/$argument" . '_value');

            if (!isset($name) || !isset($value)) {
                $array[$argument] = self::OFF_DEFAULT;
                continue;
            }
            ($value == $this->product->getData($name)) ? $array[$argument] = self::ON_DEFAULT : $array[$argument] = self::OFF_DEFAULT;
        }

        return $array;
    }

    /**
     * @param $argument
     * @return int
     */
    protected function getStockData($argument)
    {
        $stock = $this->product->getStockForCeneo();

        if (!$stock->getManageStock()) {
            return self::OFF_DEFAULT;
        }
        return $stock->getQty();
    }

    /**
     * @param $argument
     * @return int|mixed
     */
    protected function getAvail($argument)
    {
        foreach ($this->avail as $num) {
            $name = $this->getConf("ceneopl/attr_$this->groupName/$argument" . '_' . $num . '_name');
            $value = $this->getConf("ceneopl/attr_$this->groupName/$argument" . '_' . $num . '_value');

            if (!isset($name) || !isset($value)) {
                return self::AVAIL_DEFAULT_VALUE;
            }
            if ($value == $this->product->getData($name)) {
                return $num;
            }
        }
        return self::AVAIL_DEFAULT_VALUE;
    }

}
