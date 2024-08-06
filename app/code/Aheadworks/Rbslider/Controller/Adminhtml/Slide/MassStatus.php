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
 * Class MassStatus
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Slide
 */
class MassStatus extends AbstractMassAction implements HttpPostActionInterface
{
    /**
     * {@inheritdoc}
     */
    protected function massAction($collection)
    {
        $status = (int) $this->getRequest()->getParam('status');
        $count = 0;
        foreach ($collection->getItems() as $item) {
            $slideDataObject = $this->slideRepository->get($item->getId());
            $slideDataObject->setStatus($status);
            $this->slideRepository->save($slideDataObject);
            $count++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated', $count));
    }
}
