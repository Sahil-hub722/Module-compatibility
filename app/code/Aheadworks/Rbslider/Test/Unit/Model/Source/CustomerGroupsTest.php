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
namespace Aheadworks\Rbslider\Test\Unit\Model\Source;

use Aheadworks\Rbslider\Model\Source\CustomerGroups;
use Magento\Customer\Api\Data\GroupSearchResultsInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Model\Source\CustomerGroups
 */
class CustomerGroupsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CustomerGroups
     */
    private $model;

    /**
     * @var GroupRepositoryInterface|MockObject
     */
    private $groupRepositoryMock;

    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    private $searchCriteriaBuilderMock;

    /**
     * @var DataObject|MockObject
     */
    private $objectConverterMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setup(): void
    {
        $objectManager = new ObjectManager($this);
        $this->groupRepositoryMock = $this->getMockForAbstractClass(GroupRepositoryInterface::class);
        $this->searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $this->objectConverterMock = $this->getMockBuilder(DataObject::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $customerGroups = ['group_1', 'group_2'];
        $options = [
            ['label' => 'group_1', 'value' => '1'],
            ['label' => 'group_2', 'value' => '2']
        ];

        $searchCriteriaMock = $this->getMockForAbstractClass(SearchCriteriaInterface::class);
        $searchResultMock = $this->getMockForAbstractClass(GroupSearchResultsInterface::class);
        $this->searchCriteriaBuilderMock->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteriaMock);

        $this->groupRepositoryMock->expects($this->once())
            ->method('getList')
            ->with($searchCriteriaMock)
            ->willReturn($searchResultMock);

        $searchResultMock->expects($this->once())
            ->method('getItems')
            ->willReturn($customerGroups);
        $this->objectConverterMock->expects($this->once())
            ->method('toOptionArray')
            ->with($customerGroups, 'id', 'code')
            ->willReturn($options);

        $this->model = $objectManager->getObject(
            CustomerGroups::class,
            [
                'groupRepository' => $this->groupRepositoryMock,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilderMock,
                'objectConverter' => $this->objectConverterMock
            ]
        );
    }

    /**
     * Testing of toOptionArray method
     */
    public function testToOptionArray()
    {
        $this->assertTrue(is_array($this->model->toOptionArray()));
    }

    /**
     * Testing of getOptionArray method
     */
    public function testGetOptionArray()
    {
        $this->assertTrue(is_array($this->model->getOptionArray()));
    }
}
