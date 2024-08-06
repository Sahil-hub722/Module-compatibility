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
namespace Aheadworks\Rbslider\Model\Cache;

use Aheadworks\Rbslider\Model\Slide as SlideModel;

/**
 * Class SlideIdentities
 * @package Aheadworks\Rbslider\Model\Cache
 */
class SlideIdentities
{

    /**
     * Retrieve list cache tags
     *
     * @param array $slideList
     * @return array
     */
    public function getIdentities($slideList)
    {
        $identities = [];

        foreach ($slideList as $slide) {
            $identities[] = SlideModel::CACHE_TAG . '_' . $slide->getId();
        }

        return array_unique($identities);
    }
}
