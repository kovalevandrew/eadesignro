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

use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\DefaultVariables;
use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplateRepository;
use Magento\Backend\App\Action\Context;
use Magento\Email\Model\Template\Config;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\DataObject\Factory as DataObject;
use Magento\Framework\Registry;
use Magento\Sales\Model\OrderModel;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Email\Model\BackendTemplateFactory;

/**
 * Class Customer
 *
 * @package Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Variable
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Customer extends Template
{

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @var DataObject
     */
    private $dataObject;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepositoryInterface;

    /**
     * Customer constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Config $_emailConfig
     * @param JsonFactory $resultJsonFactory
     * @param TemplateRepository $templateRepository
     * @param DefaultVariables $_defaultVariablesHelper
     * @param SearchCriteriaBuilder $_criteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param DataObject $dataObject
     * @param CustomerRepositoryInterface $customerRepositoryInterface
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
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        DataObject $dataObject,
        CustomerRepositoryInterface $customerRepositoryInterface,
        BackendTemplateFactory $backendTemplateFactory
    ) {
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
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
        $this->dataObject = $dataObject;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }

    /**
     * @return $this|null
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('template_id');

        if (!$id) {
            return null;
        }

        $templateModel = $this->templateRepository->getById($id);
        $templateType = $templateModel->getData('template_type');

        $templateTypeName = TemplateType::TYPES[$templateType];

        /** @var Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $collection = $this->collection($templateTypeName);

        if (empty($collection)) {
            return null;
        }

        $id = $this->getRequest()->getParam('template_id');

        if (!$id) {
            return null;
        }

        $source = $collection->getLastItem();

        if ($source instanceof Order) {
            $order = $source;
        } else {
            $order = $source->getOrder();
        }

        if ($customerId = $order->getCustomerId()) {
            $customer = $this->customerRepositoryInterface
                ->getById($customerId);

            $customerData = $this->extensibleDataObjectConverter->toFlatArray(
                $customer,
                [],
                '\Magento\Customer\Api\Data\CustomerInterface'
            );
        }

        $model = $this->templateRepository->getById($id);
        $barCodes = [];
        if (!empty($model->getData('barcode_types'))) {
            $barCodes = explode(',', $model->getData('barcode_types'));
        }

        $pseudoCustomer = $this->dataObject->create($customerData);

        $invoiceVariables = $this->defaultVariablesHelper->getCustomerDefault($pseudoCustomer, $barCodes);

        $result = $resultJson->setData($invoiceVariables);

        return $this->addResponse($result);
    }
}
