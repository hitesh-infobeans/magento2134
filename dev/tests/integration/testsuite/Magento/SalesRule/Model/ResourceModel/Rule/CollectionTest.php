<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SalesRule\Model\ResourceModel\Rule;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Magento/SalesRule/_files/rules.php
     * @magentoDataFixture Magento/SalesRule/_files/coupons.php
     * @dataProvider setValidationFilterDataProvider()
     * @param string $couponCode
     * @param array $expectedItems
     */
    public function testSetValidationFilter($couponCode, $expectedItems)
    {
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\SalesRule\Model\ResourceModel\Rule\Collection'
        );
        $items = array_values($collection->setValidationFilter(1, 0, $couponCode)->getItems());

        $ids = [];
        foreach ($items as $key => $item) {
            $this->assertEquals($expectedItems[$key], $item->getName());
            if (in_array($item->getId(), $ids)) {
                $this->fail('Item should be unique in result collection');
            }
            $ids[] = $item->getId();
        }
    }

    /**
     * data provider for testSetValidationFilter
     * @return array
     */
    public function setValidationFilterDataProvider()
    {
        return [
            'Check type COUPON' => ['coupon_code', ['#1', '#2', '#5']],
            'Check type NO_COUPON' => ['', ['#2', '#5']],
            'Check type COUPON_AUTO' => ['coupon_code_auto', ['#2', '#4', '#5']],
            'Check result with auto generated coupon' => ['autogenerated_3_1', ['#2', '#3', '#5']],
            'Check result with non actual previously generated coupon' => [
                'autogenerated_2_1',
                ['#2', '#5'],
            ],
            'Check result with wrong code' => ['wrong_code', ['#2', '#5']]
        ];
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Checkout/_files/quote_with_shipping_method_and_items_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_any_categories.php
     */
    public function testSetValidationFilterWithGroup()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule = $objectManager->get('Magento\Framework\Registry')
            ->registry('_fixture/Magento_SalesRule_Group_Multiple_Categories');

        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $objectManager->create('Magento\Quote\Model\Quote');
        $quote->load('test_order_item_with_items', 'reserved_order_id');

        //gather only the existing rules that obey the validation filter
        /** @var  \Magento\SalesRule\Model\ResourceModel\Rule\Collection $ruleCollection */
        $ruleCollection = $objectManager->create(
            'Magento\SalesRule\Model\ResourceModel\Rule\Collection'
        );

        $appliedRulesArray = array_keys(
            $ruleCollection->setValidationFilter(
                $quote->getStore()->getWebsiteId(),
                0,
                '',
                null,
                $quote->getShippingAddress()
            )->getItems()
        );

        $this->assertEquals([$rule->getRuleId()], $appliedRulesArray);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Checkout/_files/quote_with_shipping_method_and_items_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_any_categories.php
     */
    public function testSetValidationFilterAnyCategory()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule = $objectManager->get('Magento\Framework\Registry')
            ->registry('_fixture/Magento_SalesRule_Group_Multiple_Categories');

        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $objectManager->create('Magento\Quote\Model\Quote');
        $quote->load('test_order_item_with_items', 'reserved_order_id');

        //gather only the existing rules that obey the validation filter
        /** @var  \Magento\SalesRule\Model\ResourceModel\Rule\Collection $ruleCollection */
        $ruleCollection = $objectManager->create(
            'Magento\SalesRule\Model\ResourceModel\Rule\Collection'
        );

        $appliedRulesArray = array_keys(
            $ruleCollection->setValidationFilter(
                $quote->getStore()->getWebsiteId(),
                0,
                '',
                null,
                $quote->getShippingAddress()
            )->getItems()
        );
        $this->assertEquals([$rule->getRuleId()], $appliedRulesArray);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Checkout/_files/quote_with_shipping_method_and_items_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_not_categories_sku_attr.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_any_categories.php
     * @magentoDataFixture Magento/SalesRule/_files/rules_group_any_categories_price_attr_set_any.php
     */
    public function testSetValidationFilterOther()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\Quote\Model\Quote  $quote */
        $quote = $objectManager->create('Magento\Quote\Model\Quote');
        $quote->load('test_order_item_with_items', 'reserved_order_id');

        //gather only the existing rules that obey the validation filter
        /** @var  \Magento\SalesRule\Model\ResourceModel\Rule\Collection $ruleCollection */
        $ruleCollection = $objectManager->create(
            'Magento\SalesRule\Model\ResourceModel\Rule\Collection'
        );

        $appliedRulesArray = array_keys(
            $ruleCollection->setValidationFilter(
                $quote->getStore()->getWebsiteId(),
                0,
                '',
                null,
                $quote->getShippingAddress()
            )->getItems()
        );
        $this->assertEquals(3, count($appliedRulesArray));
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/SalesRule/_files/rules.php
     * @magentoDataFixture Magento/SalesRule/_files/coupons.php
     * @magentoDataFixture Magento/SalesRule/_files/rule_specific_date.php
     * @magentoConfigFixture general/locale/timezone Europe/Kiev
     */
    public function testMultiRulesWithTimezone()
    {
        $this->setSpecificTimezone('Europe/Kiev');
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\SalesRule\Model\ResourceModel\Rule\Collection'
        );
        $collection->addWebsiteGroupDateFilter(1, 0);
        $items = array_values($collection->getItems());
        $this->assertNotEmpty($items);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/SalesRule/_files/rules.php
     * @magentoDataFixture Magento/SalesRule/_files/coupons.php
     * @magentoDataFixture Magento/SalesRule/_files/rule_specific_date.php
     * @magentoConfigFixture general/locale/timezone Australia/Sydney
     */
    public function testMultiRulesWithDifferentTimezone()
    {
        $this->setSpecificTimezone('Australia/Sydney');
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\SalesRule\Model\ResourceModel\Rule\Collection'
        );
        $collection->addWebsiteGroupDateFilter(1, 0);
        $items = array_values($collection->getItems());
        $this->assertNotEmpty($items);
    }

    protected function setSpecificTimezone($timezone)
    {
        $localeData = [
            'section' => 'general',
            'website' => null,
            'store' => null,
            'groups' => [
                'locale' => [
                    'fields' => [
                        'timezone' => [
                            'value' => $timezone
                        ]
                    ]
                ]
            ]
        ];
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Config\Model\Config\Factory')
            ->create()
            ->addData($localeData)
            ->save();
    }

    public function tearDown()
    {
        // restore default timezone
        $this->setSpecificTimezone('America/Los_Angeles');
    }
}
