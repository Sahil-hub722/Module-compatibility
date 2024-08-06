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

namespace Aheadworks\Rbslider\Ui\Component\Listing\Columns;

use Aheadworks\Rbslider\Api\Data\ActionLogInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Count extends Column
{
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var array
     */
    private $actionTypes = [
        'view_count' => ActionLogInterface::ACTION_TYPE_VIEW,
        'click_count' => ActionLogInterface::ACTION_TYPE_CLICK
    ];

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param AuthorizationInterface $authorization
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        AuthorizationInterface $authorization,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->authorization = $authorization;
    }

    /**
     * Add references to the statistic reports
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $aclResource = $this->getConfiguration()['aclResource'] ?? null;
        if (!$aclResource || $this->authorization->isAllowed($aclResource)) {
            $fieldName = $this->getName();
            foreach ($dataSource['data']['items'] as &$item) {
                $params = [
                    'banner_id' => $item['banner_id'] ?? '',
                    'slide_id' => $item['slide_id'] ?? '',
                    'action_type' => $this->actionTypes[$fieldName] ?? ''
                ];

                $item[$fieldName . '_url'] = $this->getContext()->getUrl(
                    'aw_rbslider_admin/statistic_report/index',
                    array_filter($params)
                );
            }
        }

        return $dataSource;
    }
}
