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
namespace Aheadworks\Rbslider\Plugin\CustomerSegmentation\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Aheadworks\Rbslider\Model\ResourceModel\Slide as SlideResourceModel;
use Aheadworks\CustomerSegmentation\Model\ResourceModel\Segment as SegmentResourceModel;
use Aheadworks\CustomerSegmentation\Model\Segment;

/**
 * Class SegmentPlugin
 * @package Aheadworks\Rbslider\Plugin\CustomerSegmentation\Model\ResourceModel
 */
class SegmentPlugin
{
    /**
     * @var SlideResourceModel
     */
    private $slideResourceModel;

    /**
     * @param SlideResourceModel $slideResourceModel
     */
    public function __construct(
        SlideResourceModel $slideResourceModel
    ) {
        $this->slideResourceModel = $slideResourceModel;
    }

    /**
     * Clear segments scopes data after store removal
     *
     * @param SegmentResourceModel $subject
     * @param SegmentResourceModel $result
     * @param Segment|AbstractModel $segment
     * @return SegmentResourceModel
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        SegmentResourceModel $subject,
        SegmentResourceModel $result,
        AbstractModel $segment
    ) {
        if ($segment && $segment->getId()) {
            $this->slideResourceModel->clearSegmentAssociations($segment->getId());
        }
        return $result;
    }
}
