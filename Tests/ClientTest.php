<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\Tests;

use PHPUnit_Framework_TestCase as PHPUnitTestCase;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\Order as SyliusOrder;
use Sylius\Component\Core\Model\OrderItem as SyliusOrderItem;
use Sylius\Component\Core\Model\Product as SyliusProduct;
use Sylius\Component\Core\Model\ProductVariant as SyliusProductVariant;
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

    /**
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::render
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::<private>
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::__construct
     *
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Product
     */
    public function testCanRenderEmptyClient()
    {
        static::assertFixtureEquals('client-action-empty.html', $this->object->render());
    }

    /**
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::render
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::addImpression
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::<private>
     *
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Client::__construct
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Product
     */
    public function testCanRenderProductImpression()
    {
        $product = static::mockProduct('123ABC', 'My Product', 9900);

        $this->object->addImpression($product);

        static::assertFixtureEquals('client-action-impression.html', $this->object->render());
    }

    /**
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::render
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::addDetailsImpression
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::<private>
     *
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Client::__construct
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Product
     */
    public function testCanRenderProductDetailsImpression()
    {
        $product = static::mockProduct('123ABC', 'My Product', 9900);

        $this->object->addDetailsImpression($product);

        static::assertFixtureEquals('client-action-details-impression.html', $this->object->render());
    }

    /**
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::render
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::addCheckoutAction
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::<private>
     *
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Client::__construct
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Product
     */
    public function testCanRenderCheckoutAction()
    {
        $order = $this->mockOrder(
            null,
            null,
            null,
            null,
            null,
            [
                ['ABC123', 'My Product #1', 4455, 'White', 2],
                ['BCD234', 'My Product #2', 4444, 'Black', 1],
            ]
        );

        $this->object->addCheckoutAction($order, ['step' => 2]);

        static::assertFixtureEquals('client-action-checkout.html', $this->object->render());
    }

    /**
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::render
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::addPurchaseAction
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::<private>
     *
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Client::__construct
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Product
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Transaction
     */
    public function testCanRenderPurchaseAction()
    {
        $order = $this->mockOrder(
            'TX123',
            8899,
            2222,
            2200,
            'EUR',
            [
                ['ABC123', 'My Product #1', 4455, 'White', 2],
                ['BCD234', 'My Product #2', 4444, 'Black', 1],
            ]
        );

        $this->object->addPurchaseAction($order);

        static::assertFixtureEquals('client-action-purchase.html', $this->object->render());
    }

    /**
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::render
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::renderClickHandler
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::<private>
     *
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Client::__construct
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Product
     */
    public function testCanRenderClickHandler()
    {
        $product = static::mockProduct('123ABC', 'My Product', 9900);

        static::assertFixtureEquals('client-handler-click.html', $this->object->renderClickHandler($product));
    }

    /**
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::render
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::renderCartHandler
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::<private>
     *
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Client::__construct
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Product
     */
    public function testCanRenderCartAddHandler()
    {
        $product = static::mockProduct('123ABC', 'My Product', 9900);

        static::assertFixtureEquals(
            'client-handler-cart-add.html',
            $this->object->renderCartHandler(
                $product,
                [
                    'callable' => 'function(p){return p}',
                ]
            )
        );
    }


    /**
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::render
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::renderCartHandler
     * @covers \Webburza\Sylius\GoogleEcommerceBundle\Client::<private>
     *
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Client::__construct
     * @uses   \Webburza\Sylius\GoogleEcommerceBundle\Model\Product
     */
    public function testCanRenderCartRemoveHandler()
    {
        $product = static::mockProduct('123ABC', 'My Product', 9900);

        static::assertFixtureEquals(
            'client-handler-cart-remove.html',
            $this->object->renderCartHandler(
                $product,
                [
                    'event' => 'click',
                    'action' => 'remove',
                    'variant' => 'Black',
                ]
            )
        );
    }

    /**
     * @param string $id
     * @param string $name
     * @param float  $price
     *
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
     * @param string $id
     * @param string $name
     * @param float  $price
     * @param string $variantName
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SyliusProductVariant
     */
    private function mockVariant($id, $name, $price, $variantName)
    {
        $product = $this->mockProduct($id, $name, $price);

        $variant = $this->getMockBuilder(SyliusProductVariant::class)
            ->disableOriginalConstructor()
            ->setMethods(['getProduct', '__toString'])
            ->getMock();
        $variant
            ->expects(static::once())
            ->method('getProduct')
            ->willReturn($product);
        $variant
            ->expects(static::once())
            ->method('__toString')
            ->willReturn($variantName);

        return $variant;
    }

    /**
     * @param string $id
     * @param float  $total
     * @param float  $tax
     * @param float  $shipping
     * @param string $currency
     * @param array  $items
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SyliusOrder
     */
    private function mockOrder($id, $total, $tax, $shipping, $currency, array $items)
    {
        $order = $this->getMockBuilder(SyliusOrder::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getTotal', 'getAdjustmentsTotal', 'getPromotionCoupons', 'getCurrency', 'getItems'])
            ->getMock();
        if (null !== $id) {
            $order
                ->expects(static::once())
                ->method('getId')
                ->willReturn($id);
        }
        if (null !== $total) {
            $order
                ->expects(static::once())
                ->method('getTotal')
                ->willReturn($total);
        }
        if (null !== $tax && null !== $shipping) {
            $order
                ->expects(static::at(2))
                ->method('getAdjustmentsTotal')
                ->with(static::equalTo(AdjustmentInterface::TAX_ADJUSTMENT))
                ->willReturn($tax);
            $order
                ->expects(static::at(3))
                ->method('getAdjustmentsTotal')
                ->with(static::equalTo(AdjustmentInterface::SHIPPING_ADJUSTMENT))
                ->willReturn($shipping);
        }
        if (null !== $currency) {
            $order
                ->expects(static::once())
                ->method('getCurrency')
                ->willReturn($currency);
        }
        $order
            ->expects(static::once())
            ->method('getItems')
            ->willReturn($this->mockOrderItems($items));

        return $order;
    }

    /**
     * @param array $items
     *
     * @return \PHPUnit_Framework_MockObject_MockObject[]|SyliusOrderItem[]
     */
    private function mockOrderItems(array $items)
    {
        $orderItems = [];
        foreach ($items as $item) {
            $quantity = array_pop($item); // last item in spec
            $variant = call_user_func_array([$this, 'mockVariant'], $item);

            $orderItem = $this->getMockBuilder(SyliusOrderItem::class)
                ->disableOriginalConstructor()
                ->setMethods(['getQuantity', 'getVariant'])
                ->getMock();
            $orderItem
                ->expects(static::once())
                ->method('getQuantity')
                ->willReturn($quantity);
            $orderItem
                ->expects(static::once())
                ->method('getVariant')
                ->willReturn($variant);
            $orderItems[] = $orderItem;
        }

        return $orderItems;
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
