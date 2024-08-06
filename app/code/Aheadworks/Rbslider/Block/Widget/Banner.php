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
namespace Aheadworks\Rbslider\Block\Widget;

use Aheadworks\Rbslider\Api\Data\BlockInterface;
use Magento\Customer\Model\Context as CustomerContext;
use Aheadworks\Rbslider\Block\Banner as BlockBanner;
use Magento\Widget\Block\BlockInterface as WidgetBlockInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Banner
 * @package Aheadworks\Rbslider\Block\Widget
 */
class Banner extends BlockBanner implements WidgetBlockInterface
{
    /**
     * @var string
     */
    const WIDGET_NAME_PREFIX = 'aw_rbslider_widget_';

    /**
     * Retrieve banner for widget
     *
     * @return BlockInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlocks()
    {
        $currentStoreId = $this->_storeManager->getStore()->getId();
        $currentCustomerGroup = $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP);
        $bannerId = $this->getData('banner_id');

        $blocks = [];
        try {
            $block = $this->blocksRepository->getByBannerIdForWidget($bannerId, $currentStoreId, $currentCustomerGroup);
            $blocks[] = $block;
        } catch (NoSuchEntityException $e) {
        }

        return $blocks;
    }

    /**
     * {@inheritdoc}
     */
    public function getNameInLayout()
    {
        return self::WIDGET_NAME_PREFIX . $this->getData('banner_id');
    }
}
