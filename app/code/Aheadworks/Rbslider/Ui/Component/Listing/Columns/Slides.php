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
namespace Aheadworks\Rbslider\Ui\Component\Listing\Columns;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Banner\Collection as BannerCollection;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Slides
 * @package Aheadworks\Rbslider\Ui\Component\Listing\Columns
 */
class Slides extends Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $banner) {
                $slideLinks = [];
                $slidesIds = $banner[BannerInterface::SLIDE_IDS] ?? [];
                foreach ($slidesIds as $key => $slideId) {
                    $slideLinks[] = [
                        'name' => $banner[BannerCollection::SLIDE_NAMES][$key] ?? '',
                        'url' => $this->getLink($slideId)
                    ];
                }
                $banner['slides'] = $slideLinks;
            }
        }
        return $dataSource;
    }

    /**
     * Retrieve link for slide
     *
     * @param int $entityId
     * @return string
     */
    private function getLink($entityId)
    {
        return $this->context->getUrl('aw_rbslider_admin/slide/edit', ['id' => $entityId]);
    }
}
