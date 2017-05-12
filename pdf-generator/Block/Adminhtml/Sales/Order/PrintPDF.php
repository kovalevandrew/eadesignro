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

namespace Eadesigndev\Opicmsppdfgenerator\Block\Adminhtml\Sales\Order;

use Eadesigndev\Opicmsppdfgenerator\Helper\Data;
use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class PrintPDF extends Container
{
    private $lastItem = [];

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * Core registry
     *
     * @var Registry
     */
    private $coreRegistry = null;

    /**
     * PrintPDF constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $dataHelper,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    public function _construct()
    {

        if (!$this->dataHelper->isEnable(Data::ENABLE_ORDER)) {
            return $this;
        }

        $lastItem = $this->dataHelper->getTemplateStatus(
            $this->coreRegistry->registry('sales_order'),
            TemplateType::TYPE_ORDER
        );

        if (empty($lastItem->getId())) {
            return null;
        }
        $this->lastItem = $lastItem;

        $this->addButton(
            'eadesign_print',
            [
                'label' => 'Print',
                'class' => 'print',
                'onclick' => 'setLocation(\'' . $this->getPdfPrintUrl() . '\')'
            ]
        );

        parent::_construct();
    }

    /**
     * @return string
     */
    public function getPdfPrintUrl()
    {
        return $this->getUrl(
            'opicmsppdfgenerator/*/printpdf',
            [
                'template_id' => $this->lastItem->getId(),
                'order_id' => $this->getOrderId(),
            ]
        );
    }

    /**
     * @return integer
     */
    public function getOrderId()
    {
        return $this->coreRegistry->registry('sales_order')->getId();
    }
}
