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
namespace Aheadworks\Rbslider\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;

/**
 * Class CategoriesJson
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Banner
 */
class CategoriesJson extends Action
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Rbslider::banners';

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->_forward('categoriesJson', 'promo_widget', 'catalog_rule');
    }
}
