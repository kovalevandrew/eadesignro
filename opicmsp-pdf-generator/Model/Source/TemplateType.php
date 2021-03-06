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

namespace Eadesigndev\Opicmsppdfgenerator\Model\Source;

use Eadesigndev\Pdfgenerator\Model\Source\AbstractSource;
use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;

/**
 * Class PageLayout
 */
class TemplateType extends AbstractSource
{
    /**
     * Types
     */
    const TYPE_INVOICE = 1;
    const TYPE_ORDER = 2;
    const TYPE_SHIPMENT = 3;
    const TYPE_CREDIT_MEMO = 4;

    const TYPES = [
        self::TYPE_INVOICE => 'invoice',
        self::TYPE_ORDER => 'order',
        self::TYPE_SHIPMENT => 'shipment',
        self::TYPE_CREDIT_MEMO => 'creditmemo',
    ];

    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    private $pageLayoutBuilder;

    /**
     * Constructor
     *
     * @param BuilderInterface $pageLayoutBuilder
     */
    public function __construct(BuilderInterface $pageLayoutBuilder)
    {
        $this->pageLayoutBuilder = $pageLayoutBuilder;
    }

    /**
     * Prepare post's statuses.
     *
     * @return array
     */
    public function getAvailable()
    {
        return [
            self::TYPE_INVOICE => __('Invoice'),
            self::TYPE_ORDER => __('Order'),
            self::TYPE_SHIPMENT => __('Shipment'),
            self::TYPE_CREDIT_MEMO => __('Credit memo'),
        ];
    }
}
