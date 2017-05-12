<?php
/**
 * Copyright © 2017 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Order\Massaction;

use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplatesRepositoryInterface;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Processors\Output as OutputHelper;
use Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction as SalesAbstractMassAction;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Controller\Result\Forward;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\DataObject\Factory as DataObject;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class AbstractMassAction
 * @package Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Order\Massaction
 * @SuppressWarnings(CouplingBetweenObjects)
 */
abstract class AbstractMassAction extends SalesAbstractMassAction
{
    /**
     * @var $collectionFactory
     */
    public $collectionFactory;

    /**
     * @var FileFactory
     */
    public $fileFactory;

    /**
     * @var DateTime
     */
    public $dateTime;

    /**
     * @var $outputHelper
     */
    public $outputHelper;

    /**
     * @var ForwardFactory
     */
    public $resultForwardFactory;

    /**
     * @var ExtensibleDataObjectConverter
     */
    public $extensibleDataObjectConverter;

    /**
     * @var DataObject
     */
    private $dataObject;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepositoryInterface;

    /**
     * @var TemplatesRepositoryInterface
     */
    private $templatesRepositoryInterface;

    /**
     * @var AbstractCollection
     */
    public $abstractCollection;

    /**
     * @var PdfgeneratorFactory
     */
    public $pdfgeneratorFactory;

    /**
     * AbstractMassAction constructor.
     * @param Context $context
     * @param Filter $filter
     * @param FileFactory $fileFactory
     * @param DateTime $dateTime
     * @param OutputHelper $outputHelper
     * @param ForwardFactory $resultForwardFactory
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param DataObject $dataObject
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param TemplatesRepositoryInterface $templatesRepositoryInterface
     * @param PdfgeneratorFactory $pdfgeneratorFactory
     * @SuppressWarnings(ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Filter $filter,
        FileFactory $fileFactory,
        DateTime $dateTime,
        OutputHelper $outputHelper,
        ForwardFactory $resultForwardFactory,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        DataObject $dataObject,
        CustomerRepositoryInterface $customerRepositoryInterface,
        TemplatesRepositoryInterface $templatesRepositoryInterface,
        PdfgeneratorFactory $pdfgeneratorFactory
    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->outputHelper = $outputHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->dataObject = $dataObject;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->templatesRepositoryInterface = $templatesRepositoryInterface;
        $this->pdfgeneratorFactory = $pdfgeneratorFactory;
        parent::__construct($context, $filter);
    }

    /**
     * @param $templateId
     * @param $source
     * @return object
     */
    public function processCollection($templateId, $source)
    {
        $templateModel = $this->pdfgeneratorFactory->create()->load($templateId);

        if (!$templateModel) {
            $this->noRoute();
        }

        $helper = $this->outputHelper;
        $helper->setSource($source);
        $helper->setTemplate($templateModel);

        if ($customerId = $source->getCustomerId()) {
            $pseudoCustomer = $this->customer($customerId);
            $helper->setCustomer($pseudoCustomer);
        }

        $helper->template2Pdf();

        return $templateModel;
    }

    /**
     * @param $customerId
     * @return \Magento\Framework\DataObject;
     */
    public function customer($customerId)
    {
        /** @var Customer $customer */
        $customer = $this->customerRepositoryInterface
            ->getById($customerId);

        $customerData = $this->extensibleDataObjectConverter->toFlatArray(
            $customer,
            [],
            CustomerInterface::class
        );

        $pseudoCustomer = $this->dataObject->create($customerData);

        return $pseudoCustomer;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function generateFile()
    {
        $collection = $this->abstractCollection;

        $templateId = $this->getRequest()->getParam('template_id');

        foreach ($collection as $item) {
            $templateModel = $this->processCollection($templateId, $item);
        }

        $output = $this->outputHelper->PDFmerger($templateModel);

        $dateTime = $this->dateTime->date('Y-m-d_H-i-s');

        $fileName = 'mass_print_pdf_order' . $dateTime . '.pdf';

        $file = $this->fileFactory->create(
            $fileName,
            $output,
            DirectoryList::VAR_DIR,
            'application/pdf'
        );

        return $file;
    }

    /**
     * @return bool
     */
    //@codingStandardsIgnoreLine
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Sales::sales_order');
    }

    /**
     * @return Forward
     */
    public function noRoute()
    {
        return $this->resultForwardFactory
            ->create()
            ->forward('noroute');
    }
}
