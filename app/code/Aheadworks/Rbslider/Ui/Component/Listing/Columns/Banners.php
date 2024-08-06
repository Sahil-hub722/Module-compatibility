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

use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Slide\Collection;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Banners
 * @package Aheadworks\Rbslider\Ui\Component\Listing\Columns
 */
class Banners extends Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $slide) {
                $bannerLinks = [];
                $bannerIds = $slide[SlideInterface::BANNER_IDS] ?? [];
                foreach ($bannerIds as $key => $bannerId) {
                    $bannerLinks[] = [
                        'name' => $slide[Collection::BANNER_NAMES][$key] ?? '',
                        'url' => $this->getLink($bannerId)
                    ];
                }
                $slide['banners'] = $bannerLinks;
            }
        }
        return $dataSource;
    }

    /**
     * Retrieve link for for banner
     *
     * @param int $entityId
     * @return string
     */
    private function getLink($entityId)
    {
        return $this->context->getUrl('aw_rbslider_admin/banner/edit', ['id' => $entityId]);
    }
}
