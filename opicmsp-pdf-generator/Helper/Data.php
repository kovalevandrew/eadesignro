<?php
/**
 * EaDesgin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    eadesigndev_pdfgenerator
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Opicmsppdfgenerator\Helper;

use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\Collection;
use Eadesigndev\Pdfgenerator\Model\ResourceModel\Pdfgenerator\CollectionFactory as templateCollectionFactory;
use Eadesigndev\Pdfgenerator\Model\Source\AbstractSource;
use Eadesigndev\Pdfgenerator\Model\Source\TemplateActive;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order;
use Magento\Store\Model\ScopeInterface;

/**
 * Handles the config and other settings
 *
 * Class Data
 * @package Eadesigndev\Pdfgenerator\Helper
 */
class Data extends AbstractHelper
{

    const ENABLE = 'eadesign_pdfgenerator/general/enabled';
    const EMAIL = 'eadesign_pdfgenerator/general/email';

    const ENABLE_ORDER = 'eadesign_pdfgenerator/order/enabled';
    const EMAIL_ORDER = 'eadesign_pdfgenerator/order/email';

    const ENABLE_SHIPMENT = 'eadesign_pdfgenerator/shipment/enabled';
    const EMAIL_SHIPMENT = 'eadesign_pdfgenerator/shipment/email';

    const ENABLE_CREDITMEMO = 'eadesign_pdfgenerator/creditmemo/enabled';
    const EMAIL_CREDITMEMO = 'eadesign_pdfgenerator/creditmemo/email';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $config;

    /**
     * @var Collection
     */
    private $templateCollection;

    /**
     * Data constructor.
     * @param Context $context
     * @param templateCollectionFactory $_templateCollection
     */
    public function __construct(
        Context $context,
        templateCollectionFactory $_templateCollection
    ) {
        $this->templateCollection = $_templateCollection;
        $this->config = $context->getScopeConfig();
        parent::__construct($context);
    }

    /**
     * @param string $node
     * @return bool|string
     */
    public function isEmail($node = self::EMAIL)
    {
        $enableNode = str_replace('email', 'enabled', $node);

        if ($this->isEnable($enableNode)) {
            return $this->getConfig($node);
        }

        return false;
    }

    /**
     * @param string $node
     * @return bool|string
     */
    public function isEnable($node = self::ENABLE)
    {
        if (!class_exists('mPDF')) {
            return false;
        }

        if (empty($this->collection())) {
            return false;
        }

        return $this->getConfig($node);
    }

    /**
     * Get config value
     *
     * @param string $configPath
     * @return string
     */
    public function getConfig($configPath)
    {
        return $this->config->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $source
     * @param int $type
     * @return \Magento\Framework\DataObject
     */
    public function getTemplateStatus($source, $type = TemplateType::TYPE_ORDER)
    {

        if ($source instanceof Order) {
            $store = $source->getStoreId();
        } else {
            $store = $source->getOrder()->getStoreId();
        }

        $collection = $this->collection();
        $collection->addStoreFilter($store);
        $collection->addFieldToFilter(
            'is_active',
            TemplateActive::STATUS_ENABLED
        );
        $collection->addFieldToFilter(
            'template_default',
            AbstractSource::IS_DEFAULT
        );
        $collection->addFieldToFilter(
            'template_type',
            $type
        );

        $lastItem = $collection->getLastItem();

        return $lastItem;
    }

    /**
     * @return Collection
     */
    public function collection()
    {

        $collection = $this->templateCollection->create();

        return $collection;
    }
}
