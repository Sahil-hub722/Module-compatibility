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

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class MassDelete
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Banner
 */
class MassDelete extends AbstractMassAction implements HttpPostActionInterface
{
    /**
     * {@inheritdoc}
     */
    protected function massAction($collection)
    {
        $count = 0;
        foreach ($collection->getItems() as $item) {
            $this->bannerRepository->deleteById($item->getId());
            $count++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) were deleted', $count));
    }
}
