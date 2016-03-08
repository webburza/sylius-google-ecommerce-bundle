<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\Tests;

use PHPUnit_Framework_TestCase as PHPUnitTestCase;
use Sylius\Component\Core\Model\Product as SyliusProduct;
use Webburza\Sylius\GoogleEcommerceBundle\Client;

/**
 * Class ClientTest.
 */
class ClientTest extends PHPUnitTestCase
{
    /** @var Client */
    private $object;

    public function setUp()
    {
        $this->object = new Client('UA-12345678-1');
    }

    public function testCanRender()
    {
        static::assertEquals($this->fixture('client-render-empty.html'), $this->object->render());
    }

    private function mockProduct()
    {
        $product = $this->getMockBuilder(SyliusProduct::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $product
            ->expects(static::once())
            ->method('getId')
            ->with('a')
            ->willReturn('b');

        return $product;
    }

    private function fixture($name)
    {
        return file_get_contents(__DIR__ .'/Resources/fixtures/'. $name);
    }
}
