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
namespace Aheadworks\Rbslider\Controller\Adminhtml\Slide;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class MassAddToBanner
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Slide
 */
class MassAddToBanner extends AbstractMassAction implements HttpPostActionInterface
{
    /**
     * {@inheritdoc}
     */
    protected function massAction($collection)
    {
        $bannerId = (int) $this->getRequest()->getParam('banner_id');
        $count = 0;
        if ($bannerId) {
            foreach ($collection->getItems() as $item) {
                $slideDataObject = $this->slideRepository->get($item->getId());
                $bannerIds = $slideDataObject->getBannerIds();
                if (false === array_search($bannerId, $bannerIds)) {
                    $bannerIds[] = $bannerId;
                    $slideDataObject->setBannerIds($bannerIds);
                    $this->slideRepository->save($slideDataObject);
                    $count++;
                }
            }
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated', $count));
    }
}
