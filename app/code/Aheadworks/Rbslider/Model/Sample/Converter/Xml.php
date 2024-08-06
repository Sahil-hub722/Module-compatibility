<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Rbslider
 * @version    1.4.5
 * @copyright  Copyright (c) 2022 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Rbslider\Model\Sample\Converter;

/**
 * Class Xml
 * Converts banner's parameters from XML files
 */
class Xml implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * Converting data to array type
     *
     * @param mixed $source
     * @return array
     * @throws \InvalidArgumentException
     */
    public function convert($source)
    {
        $output = [];
        if (!$source instanceof \DOMDocument) {
            return $output;
        }

        $banners = $source->getElementsByTagName('banner');
        foreach ($banners as $banner) {
            $bannerData = [];
            /** @var $banner \DOMElement */
            foreach ($banner->childNodes as $child) {
                if (!$child instanceof \DOMElement) {
                    continue;
                }
                /** @var $banner \DOMElement */
                $bannerData[$child->nodeName] = $child->nodeValue;
            }
            $output[] = $bannerData;
        }
        return $output;
    }
}
