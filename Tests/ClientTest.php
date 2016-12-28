<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\Tests;

use PHPUnit_Framework_TestCase as PHPUnitTestCase;
use Sylius\Component\Core\Model\AdjustmentInterface as Adjustment;
use Sylius\Component\Core\Model\ChannelInterface as Channel;
use Sylius\Component\Core\Model\ChannelPricingInterface as ChannelPricing;
use Sylius\Component\Core\Model\OrderInterface as Order;
use Sylius\Component\Core\Model\OrderItemInterface as OrderItem;
use Sylius\Component\Core\Model\ProductInterface as Product;
use Sylius\Component\Core\Model\ProductVariant;
use Webburza\Sylius\GoogleEcommerceBundle\Client;

/**
 * Class ClientTest.
 */
class ClientTest extends PHPUnitTestCase
{
    /** @var Client */
    private $object;

    /** @var Channel */
    private $channel;

    public function setUp()
    {
        $this->channel = $this->mockChannel();

        $this->object = new Client($this->channel, 'UA-12345678-1');
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
        $variant = $this->mockVariant('123ABC', 'My Product', 9900, 'My Product (ABCD)');

        $this->object->addImpression($variant);

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
        $variant = $this->mockVariant('123ABC', 'My Product', 9900, 'My Product (ABCD)');

        $this->object->addDetailsImpression($variant);

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
        $variant = $this->mockVariant('123ABC', 'My Product', 9900, 'My Product (ABCD)');

        static::assertFixtureEquals('client-handler-click.html', $this->object->renderClickHandler($variant));
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
        $variant = $this->mockVariant('123ABC', 'My Product', 9900, 'My Product (ABCD)');

        static::assertFixtureEquals(
            'client-handler-cart-add.html',
            $this->object->renderCartHandler(
                $variant,
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
        $variant = $this->mockVariant('123ABC', 'My Product', 9900, 'My Product (ABCD)');

        static::assertFixtureEquals(
            'client-handler-cart-remove.html',
            $this->object->renderCartHandler(
                $variant,
                [
                    'event' => 'click',
                    'action' => 'remove',
                    'variant' => 'Black',
                ]
            )
        );
    }

    /**
     * @return Channel
     */
    private function mockChannel()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Channel $channel */
        /** @noinspection OneTimeUseVariablesInspection */
        $channel = $this->getMockBuilder(Channel::class)
            ->getMock();

        return $channel;
    }

    /**
     * @param float $price
     *
     * @return ChannelPricing
     */
    private function mockChannelPricing($price)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ChannelPricing $pricing */
        $pricing = $this->getMockBuilder(ChannelPricing::class)
            ->getMock();
        $pricing
            ->expects(static::once())
            ->method('getPrice')
            ->willReturn($price);

        return $pricing;
    }

    /**
     * @param string $id
     * @param string $name
     *
     * @return Product
     */
    private function mockProduct($id, $name)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Product $product */
        $product = $this->getMockBuilder(Product::class)
            ->getMock();
        $product
            ->expects(static::once())
            ->method('getId')
            ->willReturn($id);
        $product
            ->expects(static::once())
            ->method('getName')
            ->willReturn($name);

        return $product;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param string $id
     * @param string $name
     * @param float  $price
     * @param string $variantName
     *
     * @return ProductVariant
     */
    private function mockVariant($id, $name, $price, $variantName)
    {
        $product = $this->mockProduct($id, $name);
        $pricing = $this->mockChannelPricing($price);

        /** @var \PHPUnit_Framework_MockObject_MockObject|ProductVariant $variant */
        $variant = $this->getMockBuilder(ProductVariant::class)
            ->disableOriginalConstructor()
            ->getMock();
        $variant
            ->expects(static::once())
            ->method('getProduct')
            ->willReturn($product);
        $variant
            ->expects(static::once())
            ->method('getChannelPricingForChannel')
            ->with($this->channel)
            ->willReturn($pricing);
        $variant
            ->expects(static::any())
            ->method('__toString')
            ->willReturn($variantName);

        return $variant;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    /** @noinspection PhpTooManyParametersInspection */

    /**
     * @param string $id
     * @param float  $total
     * @param float  $tax
     * @param float  $shipping
     * @param string $currency
     * @param array  $items
     *
     * @return Order
     */
    private function mockOrder($id, $total, $tax, $shipping, $currency, array $items)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Order $order */
        $order = $this->getMockBuilder(Order::class)
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
                ->with(static::equalTo(Adjustment::TAX_ADJUSTMENT))
                ->willReturn($tax);
            $order
                ->expects(static::at(3))
                ->method('getAdjustmentsTotal')
                ->with(static::equalTo(Adjustment::SHIPPING_ADJUSTMENT))
                ->willReturn($shipping);
        }
        if (null !== $currency) {
            $order
                ->expects(static::once())
                ->method('getCurrencyCode')
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
     * @return OrderItem[]
     */
    private function mockOrderItems(array $items)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject[]|OrderItem[] $orderItems */
        $orderItems = [];
        foreach ($items as $item) {
            $quantity = array_pop($item); // last item in spec
            $variant = call_user_func_array([$this, 'mockVariant'], $item);

            $orderItem = $this->getMockBuilder(OrderItem::class)
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
    protected static function assertFixtureEquals($fixture, $value)
    {
        static::assertSame(static::fixture($fixture), $value);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected static function fixture($name)
    {
        return file_get_contents(__DIR__.'/Resources/fixtures/'.$name);
    }
}
