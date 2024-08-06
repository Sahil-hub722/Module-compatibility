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
declare(strict_types=1);

namespace Aheadworks\Rbslider\Ui\Component\Listing;

use Magento\Ui\Component\Bookmark as UiBookmark;

/**
 * @method string[]|null getFilterUrlParams()
 */
class Bookmark extends UiBookmark
{
    /**
     * Use filters from request
     *
     * @return void
     */
    public function prepare()
    {
        parent::prepare();

        $config = $this->getConfiguration();

        $filter = [];
        foreach ($this->getFilterUrlParams() ?? [] as $param) {
            $value = $this->getContext()->getRequestParam($param);
            if ($value) {
                $filter[$param] = $value;
            }
        }

        if ($filter) {
            $filter['placeholder'] = true;
            $config['current']['filters']['applied'] = $filter;
            $this->setData('config', $config);
        }
    }
}
