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

use Eadesigndev\Pdfgenerator\Controller\Adminhtml\Templates;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplateRepository;
use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\DefaultVariables;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Email\Model\BackendTemplateFactory;
use Magento\Email\Model\Template\Config;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;

/**
 * Class Template
 *
 * @package Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Variable
 * @SuppressWarnings(CouplingBetweenObjects)
 */
abstract class Template extends Action
{

    const INVOICE_TMEPLTE_ID = 'sales_email_invoice_template';
    const ORDER_TMEPLTE_ID = 'sales_email_order_template';
    const SHIPMENT_TMEPLTE_ID = 'sales_email_shipment_template';
    const CREDITMEMO_TMEPLTE_ID = 'sales_email_creditmemo_template';

    const GUEST_ORDER_TMEPLTE_ID = 'sales_email_order_guest_template';
    const GUEST_INVOICE_TMEPLTE_ID = 'sales_email_invoice_guest_template';
    const GUEST_SHIPMENT_TMEPLTE_ID = 'sales_email_shipment_guest_template';
    const GUEST_CREDITMEMO_TMEPLTE_ID = 'sales_email_creditmemo_guest_template';

    const ADMIN_RESOURCE_VIEW = 'Eadesigndev_Pdfgenerator::templates';

    /**
     * @var TemplateRepository
     */
    public $templateRepository;

    /**
     * @var DefaultVariables
     */
    public $defaultVariablesHelper;

    /**
     * @var Registry
     */
    public $coreRegistry;

    /**
     * @var Config
     */
    public $emailConfig;

    /**
     * @var SearchCriteriaBuilder
     */
    public $criteriaBuilder;

    /**
     * @var FilterBuilder
     */
    public $filterBuilder;

    /**
     * @var JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var BackendTemplateFactory
     */
    public $backendTemplateFactory;

    /**
     * @var mixed
     */
    public $pdfTemplateModel;

    /**
     * Template constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Config $_emailConfig
     * @param JsonFactory $resultJsonFactory
     * @param DefaultVariables $_defaultVariablesHelper
     * @param SearchCriteriaBuilder $_criteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param BackendTemplateFactory $backendTemplateFactory
     * @param TemplateRepository $templateRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Config $_emailConfig,
        JsonFactory $resultJsonFactory,
        DefaultVariables $_defaultVariablesHelper,
        SearchCriteriaBuilder $_criteriaBuilder,
        FilterBuilder $filterBuilder,
        BackendTemplateFactory $backendTemplateFactory,
        TemplateRepository $templateRepository
    ) {
        $this->criteriaBuilder = $_criteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->emailConfig = $_emailConfig;
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->defaultVariablesHelper = $_defaultVariablesHelper;
        $this->backendTemplateFactory = $backendTemplateFactory;
        $this->templateRepository = $templateRepository;
    }

    /**
     * Load email template from request
     *
     * @return BackendTemplate $model
     */
    //@codingStandardsIgnoreLine
    protected function _initTemplate()
    {

        $model = $this->backendTemplateFactory->create();

        if (!$this->coreRegistry->registry('email_template')) {
            $this->coreRegistry->register('email_template', $model);
        }

        if (!$this->coreRegistry->registry('current_email_template')) {
            $this->coreRegistry->register('current_email_template', $model);
        }

        return $model;
    }

    /**
     * @param $templateTypeName
     * @return mixed
     */
    public function collection($templateTypeName)
    {
        $this->criteriaBuilder->addFilters(
            [$this->filterBuilder
                ->setField('entity_id')
                ->setValue($this->getRequest()->getParam('variables_entity_id'))
                ->setConditionType('eq')
                ->create()]
        );
        $searchCriteria = $this->criteriaBuilder->create();
        //@codingStandardsIgnoreLine
        $collection = $this->_objectManager->create(
            'Magento\Sales\Api\\' .
            ucfirst($templateTypeName) .
            'RepositoryInterface'
        )->getList($searchCriteria);

        return $collection;
    }

    /**
     * @return mixed
     */
    public function addCollection()
    {
        $this->_initTemplate();

        $sourceType = $this->prepareType();
        $collection = $this->collection($sourceType);

        return $collection;
    }

    /**
     * @param $collection
     * @return Item|mixed
     */
    public function dataItem($collection)
    {
        $source = $collection->getLastItem();
        $items = $source->getItems();

        $dataItem = end($items);
        
        return $dataItem;
    }

    /**
     * @param $result
     * @return $result
     */
    public function addResponse($result)
    {
        if (!empty($result)) {
            return $result;
        }

        $resultJson = $this->resultJsonFactory->create();

        $optionArray[] = ['value' => '{{' . '' . '}}', 'label' => __('%1', '')];

        $optionArray = [
            'label' => __('There are no variable available, please check the source value.'),
            'value' => $optionArray
        ];

        $result = $resultJson->setData(
            [
                $optionArray
            ]
        );

        return $result;
    }

    /**
     * @param $variables
     * @return $this
     */
    public function response($variables)
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = $resultJson->setData($variables);

        return $this->addResponse($result);
    }

    /**
     * @return mixed
     */
    public function prepareType()
    {
        $id = $this->getRequest()->getParam('template_id');

        if (!$id) {
            return null;
        }

        $pdfTemplateModel = $this->templateRepository->getById($id);
        $this->pdfTemplateModel = $pdfTemplateModel;
        $templateType = $pdfTemplateModel->getData('template_type');

        $templateTypeName = TemplateType::TYPES[$templateType];
        return $templateTypeName;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    //@codingStandardsIgnoreLine
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(Templates::ADMIN_RESOURCE_VIEW);
    }
}
