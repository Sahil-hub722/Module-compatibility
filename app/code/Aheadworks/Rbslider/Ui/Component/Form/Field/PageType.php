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

namespace Aheadworks\Rbslider\Ui\Component\Form\Field;

use Aheadworks\Rbslider\Model\Source\PageType as PageTypeSource;
use Magento\Ui\Component\Form\Element\Select;

/**
 * Class PageTypeField
 * @package Aheadworks\Rbslider\Ui\Component\Form
 */
class PageType extends Select
{
    /**
     * @inheritdoc
     */
    public function prepare()
    {
        $config = $this->getData('config');
        $config['rbsliderSwitcher'] = [
            [
                'values' => [PageTypeSource::HOME_PAGE],
                'actions' => [
                    [
                        'selector' => '#rule_conditions_fieldset',
                        'action' => 'hide'
                    ],
                    [
                        'selector' => '#rule_category_fieldset',
                        'action' => 'hide'
                    ],
                    [
                        'selector' => 'div[data-index="position"]',
                        'action' => 'show'
                    ],
                ]
            ],
            [
                'values' => [PageTypeSource::PRODUCT_PAGE],
                'actions' => [
                    [
                        'selector' => '#rule_conditions_fieldset',
                        'action' => 'show'
                    ],
                    [
                        'selector' => '#rule_category_fieldset',
                        'action' => 'hide'
                    ],
                    [
                        'selector' => 'div[data-index="position"]',
                        'action' => 'show'
                    ],
                ]
            ],
            [
                'values' => [PageTypeSource::CATEGORY_PAGE],
                'actions' => [
                    [
                        'selector' => '#rule_conditions_fieldset',
                        'action' => 'hide'
                    ],
                    [
                        'selector' => '#rule_category_fieldset',
                        'action' => 'show'
                    ],
                    [
                        'selector' => 'div[data-index="position"]',
                        'action' => 'show'
                    ],
                ]
            ],
            [
                'values' => [PageTypeSource::CUSTOM_WIDGET],
                'actions' => [
                    [
                        'selector' => '#rule_conditions_fieldset',
                        'action' => 'hide'
                    ],
                    [
                        'selector' => '#rule_category_fieldset',
                        'action' => 'hide'
                    ],
                    [
                        'selector' => 'div[data-index="position"]',
                        'action' => 'hide'
                    ]
                ]
            ],
            [
                'values' => [PageTypeSource::AW_BLOG_HOME_PAGE],
                'actions' => [
                    [
                        'selector' => '#rule_conditions_fieldset',
                        'action' => 'hide'
                    ],
                    [
                        'selector' => '#rule_category_fieldset',
                        'action' => 'hide'
                    ],
                    [
                        'selector' => 'div[data-index="position"]',
                        'action' => 'show'
                    ],
                ]
            ]
        ];
        $this->setData('config', $config);

        parent::prepare();
    }
}
