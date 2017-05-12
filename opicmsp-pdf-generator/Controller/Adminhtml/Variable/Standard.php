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
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Email\Model\Source\Variables;
use Magento\Email\Model\Template\Config;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplateRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;
use Magento\Framework\Json\Helper\Data as JsonData;
use Magento\Variable\Model\Variable;
use Magento\Variable\Model\VariableFactory as VariableModelFactory;
use Magento\Email\Model\Source\VariablesFactory as VariablesModelSourceFactory;
use Magento\Email\Model\BackendTemplateFactory;

/**
 * Class Standard
 *
 * @package Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Variable
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Standard extends Template
{

    /**
     * @var JsonData
     */
    private $jsonData;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var Variable
     */
    private $variableModelFactory;

    /**
     * @var Variables
     */
    private $variablesModelSourceFactory;

    /**
     * Currency constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Config $_emailConfig
     * @param JsonFactory $resultJsonFactory
     * @param TemplateRepository $templateRepository
     * @param DefaultVariables $_defaultVariablesHelper
     * @param SearchCriteriaBuilder $_criteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param JsonData $jsonData
     * @param VariableModelFactory $variableModelFactory
     * @param VariablesModelSourceFactory $variablesModelSourceFactory
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
        JsonData $jsonData,
        VariableModelFactory $variableModelFactory,
        VariablesModelSourceFactory $variablesModelSourceFactory,
        BackendTemplateFactory $backendTemplateFactory
    ) {
        $this->context = $context;
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
        $this->jsonData = $jsonData;
        $this->variableModelFactory = $variableModelFactory;
        $this->variablesModelSourceFactory = $variablesModelSourceFactory;
    }

    /**
     * @return $this|null
     */
    public function execute()
    {

        $template = $this->_initTemplate();

        $id = $this->getRequest()->getParam('template_id');

        if (!$id) {
            return null;
        }

        $templateModel = $this->templateRepository->getById($id);
        $templateType = $templateModel->getData('template_type');
        $type = TemplateType::TYPES[$templateType];

        /**if json error see https://github.com/magento/magento2/commit/02bc3fc42bf041919af6200f5dbba071ae3f2020 */

        try {
            $parts = $this->emailConfig->parseTemplateIdParts('sales_email_' . $type . '_template');
            $templateId = $parts['templateId'];
            $theme = $parts['theme'];

            if ($theme) {
                $template->setForcedTheme($templateId, $theme);
            }
            $template->setForcedArea($templateId);

            $template->loadDefault($templateId);
            $template->setData('orig_template_code', $templateId);
            $template->setData('template_variables', \Zend_Json::encode($template->getVariablesOptionArray(true)));

            $templateBlock = $this->_view->getLayout()->createBlock('Magento\Email\Block\Adminhtml\Template\Edit');
            $template->setData('orig_template_currently_used_for', $templateBlock->getCurrentlyUsedForPaths(false));

            $this->getResponse()->representJson(
                $this->jsonData->jsonEncode($template->getData())
            );
        } catch (Exception $e) {
            $this->context->getMessageManager()->addExceptionMessage($e, $e->getMessage());
        }

        $customVariables = $this->variableModelFactory->create()
            ->getVariablesOptionArray(true);

        $storeContactVariables = $this->variablesModelSourceFactory->create()
            ->toOptionArray(true);

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $result = $resultJson->setData(
            [
                $storeContactVariables,
                $template->getVariablesOptionArray(true),
                $customVariables
            ]
        );

        return $this->addResponse($result);
    }
}
