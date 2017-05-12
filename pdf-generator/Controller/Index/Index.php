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

namespace Eadesigndev\Opicmsppdfgenerator\Controller\Index;

use Eadesigndev\Pdfgenerator\Api\TemplatesRepositoryInterface;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Processors\Output;
use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\DataObject\Factory as DataObject;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\Order;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Class Index
 *
 * @package Eadesigndev\Opicmsppdfgenerator\Controller\Index
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Index extends Action
{

    /**
     * @var SearchCriteriaBuilder
     */
    private $criteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var Output
     */
    private $helper;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @var
     */
    private $resultForwardFactory;

    /**
     * @var Session
     */
    private $customerSession;

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
     * Index constructor.
     * @param Context $context
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param Output $helper
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param Session $customerSession
     * @param ForwardFactory $resultForwardFactory
     * @param DataObject $dataObject
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param TemplatesRepositoryInterface $templatesRepositoryInterface
     * @SuppressWarnings(ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        SearchCriteriaBuilder $criteriaBuilder,
        FilterBuilder $filterBuilder,
        Output $helper,
        DateTime $dateTime,
        FileFactory $fileFactory,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        Session $customerSession,
        ForwardFactory $resultForwardFactory,
        DataObject $dataObject,
        CustomerRepositoryInterface $customerRepositoryInterface,
        TemplatesRepositoryInterface $templatesRepositoryInterface
    ) {
        parent::__construct($context);
        $this->criteriaBuilder = $criteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->helper = $helper;
        $this->dateTime = $dateTime;
        $this->fileFactory = $fileFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->customerSession = $customerSession;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->dataObject = $dataObject;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->templatesRepositoryInterface = $templatesRepositoryInterface;
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        $templateId = $this->getRequest()->getParam('template_id');

        if (!$templateId) {
            $this->noRoute();
        }

        $templateModel = $this->templatesRepositoryInterface
            ->getById($templateId);

        if (!$templateModel) {
            $this->noRoute();
        }

        $templateType = $templateModel->getData('template_type');

        $templateTypeName = TemplateType::TYPES[$templateType];

        $collection = $this->collection($templateTypeName);

        if (empty($collection)) {
            $this->noRoute();
        }

        $helper = $this->helper;

        $pdfFileData = ['filename'];

        foreach ($collection as $source) {
            if ($source instanceof Order) {
                $customerId = $source->getCustomerId();
            } else {
                $customerId = $source->getOrder()->getCustomerId();
            }

            if ($customerId) {
                $pseudoCustomer = $this->customer($customerId);
                $helper->setCustomer($pseudoCustomer);
            }

            if ($this->customerSession->getCustomer()->getId() != $customerId) {
                $this->noRoute();
            }

            $helper->setSource($source);
            $helper->setTemplate($templateModel);

            $pdfFileData = $helper->template2Pdf();
        }

        $output = $helper->PDFmerger();

        $fileName = $pdfFileData['filename'] . '.pdf';

        $file = $this->fileFactory->create(
            $fileName,
            $output,
            DirectoryList::VAR_DIR,
            'application/pdf'
        );

        return $file;
    }

    /**
     * @param $customerId
     * @return \Magento\Framework\DataObject
     */
    private function customer($customerId)
    {
        $customer = $this->customerRepositoryInterface
            ->getById($customerId);

        $customerData = $this->extensibleDataObjectConverter->toFlatArray(
            $customer,
            [],
            '\Magento\Customer\Api\Data\CustomerInterface'
        );

        $pseudoCustomer = $this->dataObject->create($customerData);
        return $pseudoCustomer;
    }

    /**
     * @param $templateTypeName
     * @return mixed
     */
    private function collection($templateTypeName)
    {
        $this->criteriaBuilder->addFilters(
            [$this->filterBuilder
                ->setField('entity_id')
                ->setValue($this->getRequest()->getParam('source_id'))
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
     * @return Forward
     */
    private function noRoute()
    {
        return $this->resultForwardFactory
            ->create()
            ->forward('noroute');
    }
}
