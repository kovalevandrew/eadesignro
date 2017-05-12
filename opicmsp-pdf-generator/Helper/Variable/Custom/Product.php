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

namespace Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Custom;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product as ProductObject;

class Product implements CustomInterface
{

    /**
     * @var ProductObject
     */
    private $source;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    public function __construct(ImageHelper $imageHelper)
    {
        $this->imageHelper = $imageHelper;
    }

    /**
     * @param $source
     * @return $this
     */
    public function entity($source)
    {
        $this->source = $source;
        $this->imageProcessor();

        return $this;
    }

    /**
     * @return ProductObject
     */
    public function processAndReadVariables()
    {
        return $this->source;
    }

    /**
     * Add the images, pare the image gallery to get all
     */
    private function imageProcessor()
    {
        $this->mediaFiles();
        $this->image();
        $this->smallImage();
        $this->thumbnail();
    }

    /**
     * @return $this
     */
    private function mediaFiles()
    {
        $media = $this->source->getData('media_gallery');
        $mediaImages = $media['images'];

        if (empty($mediaImages)) {
            return $this;
        }

        $i = 0;
        foreach ($mediaImages as $mediaImage) {
            $t = $i++;
            $key = "ea_image_{$t}";
            $this->source->setData($key, $mediaImage['file']);
        }

        return $this;
    }

    /**
     * @return ProductObject
     */
    private function image()
    {
        $html = $this->imageHtml($this->imageUrl('image', 200));
        $this->source->setImageHtml($html);
        return $this->source;
    }

    /**
     * @return ProductObject
     */
    private function smallImage()
    {
        $html = $this->imageHtml($this->imageUrl('small_image', 100));
        $this->source->setSmallImageHtml($html);
        return $this->source;
    }

    /**
     * @return ProductObject
     */
    private function thumbnail()
    {
        $html = $this->imageHtml($this->imageUrl('thumbnail', 50));
        $this->source->setThumbnailImageHtml($html);
        return $this->source;
    }

    /**
     * @param $type
     * @param $size
     * @return string
     */
    private function imageUrl($type, $size)
    {
        $imageUrl = $this->imageHelper
            ->init($this->source, $type)
            ->setImageFile($this->source->getImage())
            ->resize($size)
            ->getUrl();

        return $imageUrl;
    }

    /**
     * @param $imageUrl
     * @return string
     */
    public function imageHtml($imageUrl)
    {
        $html = '<img src="' . $imageUrl . '"/>';
        return $html;
    }
}
