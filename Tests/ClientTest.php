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

    public function testCanRenderEmptyClient()
    {
        static::assertFixtureEquals('client-render-empty.html', $this->object->render());
    }

    public function testCanRenderProductImpression()
    {
        $product = static::mockProduct('123ABC', 'My Product', 9900);

        $this->object->addImpression($product);

        static::assertFixtureEquals('client-render-impression.html', $this->object->render());
    }

    public function testCanRenderProductDetailsImpression()
    {
        $product = static::mockProduct('123ABC', 'My Product', 9900);

        $this->object->addDetailsImpression($product);

        static::assertFixtureEquals('client-render-details-impression.html', $this->object->render());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SyliusProduct
     */
    private function mockProduct($id, $name, $price)
    {
        $product = $this->getMockBuilder(SyliusProduct::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getName', 'getPrice'])
            ->getMock();

        $product
            ->expects(static::once())
            ->method('getId')
            ->willReturn($id);

        $product
            ->expects(static::once())
            ->method('getName')
            ->willReturn($name);

        $product
            ->expects(static::once())
            ->method('getPrice')
            ->willReturn($price);

        return $product;
    }

    /**
     * @param string $fixture
     * @param string $value
     */
    private static function assertFixtureEquals($fixture, $value)
    {
        static::assertEquals(static::fixture($fixture), $value);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private static function fixture($name)
    {
        return file_get_contents(__DIR__.'/Resources/fixtures/'.$name);
    }
}
