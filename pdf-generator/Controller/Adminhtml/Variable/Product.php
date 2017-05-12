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

namespace Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Variable;

use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Custom\Items as VariableItems;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Custom\Product as ProductHelperData;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\DefaultVariables;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplateRepository;
use Magento\Backend\App\Action\Context;
use Magento\Email\Model\Template\Config;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Email\Model\BackendTemplateFactory;
use Magento\Sales\Model\Order\Item;

class Product extends Template
{

    /**
     * @var VariableItems
     */
    public $customData;

    /**
     * Items constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Config $_emailConfig
     * @param JsonFactory $resultJsonFactory
     * @param TemplateRepository $templateRepository
     * @param DefaultVariables $_defaultVariablesHelper
     * @param SearchCriteriaBuilder $_criteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param ProductHelperData $customData
     * @param BackendTemplateFactory $backendTemplateFactory
     * @SuppressWarnings(ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Config $_emailConfig,
        JsonFactory $resultJsonFactory,
        TemplateRepository $templateRepository,
        DefaultVariables $_defaultVariablesHelper,
        SearchCriteriaBuilder $_criteriaBuilder,
        FilterBuilder $filterBuilder,
        ProductHelperData $customData,
        BackendTemplateFactory $backendTemplateFactory
    ) {
        $this->templateRepository = $templateRepository;
        parent::__construct(
            $context,
            $coreRegistry,
            $_emailConfig,
            $resultJsonFactory,
            $_defaultVariablesHelper,
            $_criteriaBuilder,
            $filterBuilder,
            $backendTemplateFactory,
            $templateRepository
        );
        $this->coreRegistry = $coreRegistry;
        $this->customData = $customData;
    }

    /**
     * @return $this|null
     */
    public function execute()
    {

        $collection = $this->addCollection();
        if (empty($collection)) {
            return null;
        }

        $orderItem = $this->dataItem($collection);

        if (!$orderItem instanceof Item) {
            $orderItem = $orderItem->getOrderItem();
        }

        $orderItemProduct = $orderItem->getProduct();
        $lastItem = $this->customData->entity($orderItemProduct)->processAndReadVariables();

        $barCodes = [];
        $barCodesData = $this->pdfTemplateModel->getData('barcode_types');
        if (!empty($barCodesData)) {
            $barCodes = explode(',', $barCodesData);
        }

        $variables = $this->defaultVariablesHelper->getOrderItemsProductDefault($lastItem, $barCodes);

        /** @var Json $resultJson */
        return $this->response($variables);
    }
}
