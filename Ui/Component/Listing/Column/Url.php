<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;
use Orba\Ceneopl\Helper\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Url extends Column
{
    /** Url path */
    const URL_PATH = 'ceneopl/feed/product/hash';

    /** @var UrlBuilder */
    protected $actionUrlBuilder;

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var Config
     */
    protected $configHelper;

    /**
     * @var ScopeConfigInterface
     */
    protected $_config;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * Url constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlBuilder $actionUrlBuilder
     * @param UrlInterface $urlBuilder
     * @param Config $configHelper
     * @param ScopeConfigInterface $_config
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        Config $configHelper,
        ScopeConfigInterface $_config,
        array $components = [],
        array $data = [],
        $editUrl = self::URL_PATH
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
        $this->editUrl = $editUrl;
        $this->configHelper = $configHelper;
        $this->_config = $_config;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['store_id'])) {
                    $hash = $this->_config->getValue(
                        \Orba\Ceneopl\Helper\Config::XML_PATH_CENEO_HASH,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                        $item['store_id']
                    );
                    $item[$name]['edit'] = [
                        'href' => $this->actionUrlBuilder->getUrl("$this->editUrl/$hash/", '', $item['store_id']),
                        'label' => $this->actionUrlBuilder->getUrl("$this->editUrl/$hash/", '', $item['store_id'])
                    ];
                }
            }
        }

        return $dataSource;
    }
}
