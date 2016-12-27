<?php

namespace Webburza\Sylius\GoogleEcommerceBundle;

use Sylius\Component\Core\Model\Order as SyliusOrder;
use Sylius\Component\Core\Model\Product as SyliusProduct;
use Webburza\Sylius\GoogleEcommerceBundle\Model\Product;
use Webburza\Sylius\GoogleEcommerceBundle\Model\Transaction;

/**
 * Class Client.
 */
class Client
{
    /** @var string */
    private $key;

    /** @var Product[] */
    private $impressions = [];

    /** @var Product[] */
    private $products = [];

    /** @var string */
    private $action;

    /** @var string[] */
    private $actionOptions = [];

    /** @var string */
    private $currency;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param SyliusOrder   $order
     * @param null|string[] $options
     *
     * @return $this
     */
    public function addCheckoutAction(SyliusOrder $order, array $options = null)
    {
        $this->addProductsFromOrder($order);
        $this->action = 'checkout';
        $this->actionOptions = $options;

        return $this;
    }

    /**
     * @param SyliusOrder $order
     *
     * @return $this
     */
    public function addPurchaseAction(SyliusOrder $order)
    {
        $this->addProductsFromOrder($order);
        $this->action = 'purchase';
        $this->actionOptions = Transaction::createFromOrder($order);
        $this->currency = $order->getCurrencyCode();

        return $this;
    }

    /**
     * @param SyliusProduct $product
     *
     * @return Client
     */
    public function addDetailsImpression(SyliusProduct $product)
    {
        $this->addProduct($product);
        $this->action = 'detail';

        return $this;
    }

    /**
     * @param SyliusProduct $product
     * @param null|string[] $options
     *
     * @return $this
     */
    public function addImpression(SyliusProduct $product, array $options = null)
    {
        $this->impressions[] = Product::createFromProduct($product, $options);

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        /** @noinspection CommaExpressionJS */
        $render = sprintf(
            '
        <script>
            (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                    })(window,document,"script","//www.google-analytics.com/analytics.js", "ga");
            ga("create", "%1$s", "auto");
            ga("require", "ec");',
            $this->key
        );

        $blocks = (array) array_filter(
            array_merge(
                $this->renderBlock($this->impressions, 'Impression'),
                $this->renderBlock($this->products, 'Product'),
                $this->renderAction($this->action, $this->actionOptions),
                $this->renderVariable($this->currency, '&cu')
            )
        );
        foreach ($blocks as $block) {
            $render .= sprintf(
                '
            %1$s',
                $block
            );
        }

        $render .= '
            ga("send", "pageview");

            function wbGoogleEcommerceClick(a){
                var p = JSON.parse(a.dataset.geec);
                ga("ec:addProduct", p);
                ga("ec:setAction", "click", {list: p.list});
                ga("send", "event", "UX", "click", "Results", {hitCallback: function() {document.location = a.href}});
                return false;
            }

            function wbGoogleEcommerceCart(a,el,f){
                if (!(el&&window.ga&&ga.loaded)) return true;
                var c, p = JSON.parse(el.dataset.geec);
                if (f && typeof f === "function") p = f(p);
                ga("ec:addProduct", p);
                ga("ec:setAction", a);
                switch (el.nodeName.toLowerCase()) {
                    case "a": c = function() {document.location = el.href}; break;
                    case "form": c = function() {el.submit()}; break;
                    default: throw Error("Unsupported GEEC invocation");
                }
                ga("send", "event", "UX", "click", a, {hitCallback: c});
                return false;
            }
        </script>
';

        return $render;
    }

    /**
     * @param SyliusProduct $product
     * @param null|string[] $options
     *
     * @return string
     */
    public function renderClickHandler(SyliusProduct $product, array $options = null)
    {
        $payload = htmlentities(json_encode(Product::createFromProduct($product, $options)));

        return sprintf(' data-geec="%1$s" onclick="wbGoogleEcommerceClick(this); return !ga.loaded;"', $payload);
    }

    /**
     * @param SyliusProduct $product
     * @param null|string[] $options
     *
     * @return string
     */
    public function renderCartHandler(SyliusProduct $product, array $options = null)
    {
        $options = array_merge(
            [
                'action' => 'add',
                'event' => 'submit',
                'callable' => 'null',
            ],
            (array) $options
        );

        $payload = htmlentities(json_encode(Product::createFromProduct($product, $options)));

        return sprintf(
            ' data-geec="%1$s" on%2$s="return wbGoogleEcommerceCart(\'%3$s\', this, %4$s);"',
            $payload,
            $options['event'],
            $options['action'],
            $options['callable']
        );
    }

    /**
     * @param Product[] $collection
     * @param string    $type
     *
     * @return array
     */
    private function renderBlock(array $collection, $type)
    {
        if (0 === count($collection)) {
            return [];
        }

        $blocks = [];
        foreach ($collection as $item) {
            $blocks[] = sprintf('ga("ec:add%1$s", %2$s);', $type, json_encode($item));
        }

        return $blocks;
    }

    /**
     * @param string        $action
     * @param null|string[] $options
     *
     * @return array
     */
    private function renderAction($action, $options = null)
    {
        if (null === $action) {
            return [];
        }

        return [sprintf('ga("ec:setAction", %1$s, %2$s);', json_encode($action), json_encode($options))];
    }

    /**
     * @param mixed  $value
     * @param string $name
     *
     * @return array
     */
    private function renderVariable($value, $name)
    {
        if (null === $value) {
            return [];
        }

        return [sprintf('ga("set", %1$s, %2$s);', json_encode($name), json_encode($value))];
    }

    /**
     * @param SyliusProduct $product
     * @param null|string[] $options
     *
     * @return $this
     */
    private function addProduct(SyliusProduct $product, array $options = null)
    {
        $this->products[] = Product::createFromProduct($product, $options);

        return $this;
    }

    /**
     * @param SyliusOrder $order
     */
    private function addProductsFromOrder(SyliusOrder $order)
    {
        /** @var \Sylius\Component\Core\Model\OrderItem $item */
        foreach ($order->getItems() as $item) {
            /** @var \Sylius\Component\Core\Model\ProductVariant $variant */
            $variant = $item->getVariant();
            /** @var \Sylius\Component\Core\Model\Product $product */
            $product = $variant->getProduct();
            $this->addProduct(
                $product,
                [
                    'variant' => $variant->__toString(),
                    'quantity' => $item->getQuantity(),
                ]
            );
        }
    }
}
