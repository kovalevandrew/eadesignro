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

namespace Eadesigndev\Opicmsppdfgenerator\Model\Email;

use Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Variable\Template as AdminHtmlTemplate;
use Eadesigndev\Opicmsppdfgenerator\Helper\Data as OpicmsData;
use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
use Magento\Sales\Model\Order\Email\Container\Template;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Processors\Output;
use Eadesigndev\Opicmsppdfgenerator\Helper\Data;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Eadesigndev\Pdfgenerator\Model\Email\TransportBuilder;

class SenderBuilder extends \Magento\Sales\Model\Order\Email\SenderBuilder
{
    private $pdfTemplate;

    /**
     * @var Pdf
     */
    private $helper;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * SenderBuilder constructor.
     * @param Template $templateContainer
     * @param IdentityInterface $identityContainer
     * @param TransportBuilder $transportBuilder
     * @param Output $helper
     * @param Data $dataHelper
     * @param DateTime $dateTime
     */
    public function __construct(
        Template $templateContainer,
        IdentityInterface $identityContainer,
        TransportBuilder $transportBuilder,
        Output $helper,
        Data $dataHelper,
        DateTime $dateTime
    ) {
        $this->helper = $helper;
        $this->dataHelper = $dataHelper;
        $this->dateTime = $dateTime;
        parent::__construct($templateContainer, $identityContainer, $transportBuilder);
    }

    /**
     * Add attachment to the main mail
     */
    public function send()
    {
        $this->addEadesignPDFAttachment();
        parent::send();
    }

    /**
     * Add attachment to the css/bcc mail
     */
    public function sendCopyTo()
    {
        $this->addEadesignPDFAttachment();
        parent::sendCopyTo();
    }

    /**
     * Add the attachment
     *
     * @return $this
     */
    private function addEadesignPDFAttachment()
    {
        $templateSpecs = $this->isEadesignPDFAttachment();
        $templateEmail = $templateSpecs['email'];

        if ($this->isForEmail($templateEmail)) {
            $templateValue = $templateSpecs['type'];
            $templateType = TemplateType::TYPES[$templateValue];
            $variables = $this->templateContainer->getTemplateVars();
            $source = $variables[$templateType];
            $this->helper->setSource($source);
            $this->pdfTemplate = $this->dataHelper->getTemplateStatus(
                $source,
                $templateValue
            );
            $this->attachment();
        }

        return $this;
    }

    /**
     * @param null $opicmsData
     * @return bool
     */
    private function isForEmail($opicmsData = null)
    {
        if ($this->dataHelper->isEmail($opicmsData)) {
            return true;
        }

        return false;
    }

    /**
     * Create the actual pdf file attachment
     *
     * @return $this
     */
    private function attachment()
    {
        if (!$this->pdfTemplate) {
            return $this;
        }

        $helper = $this->helper;
        $helper->setTemplate($this->pdfTemplate);

        $pdfFileData = $helper->template2Pdf();
        $output = $helper->PDFmerger();

        $this->transportBuilder->addAttachment(
            $output,
            \Zend_Mime::TYPE_OCTETSTREAM,
            \Zend_Mime::DISPOSITION_ATTACHMENT,
            \Zend_Mime::ENCODING_BASE64,
            $pdfFileData['filename']. '.pdf'
        );

        return $this;
    }

    /**
     * Check if the email template id matches our
     *
     * @return string
     */
    private function isEadesignPDFAttachment()
    {
        $templateId = $this->templateContainer->getTemplateId();

        $sourceIds = [
            OpicmsData::EMAIL_ORDER => [
                AdminHtmlTemplate::ORDER_TMEPLTE_ID,
                AdminHtmlTemplate::GUEST_ORDER_TMEPLTE_ID,
                'type' => TemplateType::TYPE_ORDER
            ],
            OpicmsData::EMAIL => [
                AdminHtmlTemplate::INVOICE_TMEPLTE_ID,
                AdminHtmlTemplate::GUEST_INVOICE_TMEPLTE_ID,
                'type' => TemplateType::TYPE_INVOICE
            ],
            OpicmsData::EMAIL_SHIPMENT => [
                AdminHtmlTemplate::SHIPMENT_TMEPLTE_ID,
                AdminHtmlTemplate::GUEST_SHIPMENT_TMEPLTE_ID,
                'type' => TemplateType::TYPE_SHIPMENT
            ],
            OpicmsData::EMAIL_CREDITMEMO => [
                AdminHtmlTemplate::CREDITMEMO_TMEPLTE_ID,
                AdminHtmlTemplate::GUEST_CREDITMEMO_TMEPLTE_ID,
                'type' => TemplateType::TYPE_CREDIT_MEMO
            ],
        ];

        $result = [];
        foreach ($sourceIds as $key => $sourceId) {
            if (in_array($templateId, $sourceId)) {
                $result = ['email' => $key, 'type' => $sourceId ['type']];
            }
        }

        return $result;
    }
}
