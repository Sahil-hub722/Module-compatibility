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

use Aheadworks\Rbslider\Model\Rule\Product as ProductRule;
use Magento\CatalogRule\Controller\Adminhtml\Promo\Catalog as CatalogPromoController;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Rule\Model\Condition\AbstractCondition;

/**
 * Class NewConditionHtml
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Banner
 */
class NewConditionHtml extends CatalogPromoController implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Rbslider::banners';

    /**
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $formName = $this->getRequest()->getParam('form_namespace');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $prefix = 'conditions';
        if ($this->getRequest()->getParam('prefix')) {
            $prefix = $this->getRequest()->getParam('prefix');
        }

        $rule = ProductRule::class;
        if ($this->getRequest()->getParam('rule')) {
            $rule = base64_decode($this->getRequest()->getParam('rule'));
        }
        $model = $this->_objectManager->create(
            $type
        )->setId(
            $id
        )->setType(
            $type
        )->setRule(
            $this->_objectManager->create($rule)
        )->setPrefix(
            $prefix
        );

        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof AbstractCondition) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $model->setFormName($formName);
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }
}
