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
namespace Aheadworks\Rbslider\Model\Source;

/**
 * Class ImageType
 * @package Aheadworks\Rbslider\Model\Source
 */
class ImageType implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Image type values
     */
    const TYPE_FILE = 1;
    const TYPE_URL = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TYPE_FILE,  'label' => __('File')],
            ['value' => self::TYPE_URL,  'label' => __('URL')],
        ];
    }
}
