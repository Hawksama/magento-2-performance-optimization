<?php
namespace Hawksama\PerformanceOptimization\Test\Unit\Observer\Frontend\Controller;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @covers \Hawksama\PerformanceOptimization\Observer\Frontend\Controller\FrontSendResponseBefore
 */
class FrontSendResponseBeforeTest extends TestCase
{
    /**
     * Mock helper
     *
     * @var \Hawksama\PerformanceOptimization\Helper\Data|PHPUnit_Framework_MockObject_MockObject
     */
    private $helper;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Hawksama\PerformanceOptimization\Observer\Frontend\Controller\FrontSendResponseBefore
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->helper = $this->createMock(\Hawksama\PerformanceOptimization\Helper\Data::class);
        $this->testObject = $this->objectManager->getObject(
        \Hawksama\PerformanceOptimization\Observer\Frontend\Controller\FrontSendResponseBefore::class,
            [
                'helper' => $this->helper,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestExecute
     */
    public function testExecute(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
