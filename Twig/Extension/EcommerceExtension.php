<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\Twig\Extension;

use Sylius\Component\Core\Model\OrderInterface as Order;
use Sylius\Component\Core\Model\ProductVariantInterface as ProductVariant;
use Webburza\Sylius\GoogleEcommerceBundle\Client;

/**
 * Class EcommerceExtension.
 */
class EcommerceExtension extends \Twig_Extension
{
    /** @var Client */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('google_ecommerce_render', [$this->client, 'render'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction(
                'google_ecommerce_click',
                [$this->client, 'renderClickHandler'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'google_ecommerce_cart',
                [$this->client, 'renderCartHandler'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction('google_ecommerce_impression', [$this, 'addImpression']),
            new \Twig_SimpleFunction('google_ecommerce_details', [$this, 'addDetailsImpression']),
            new \Twig_SimpleFunction('google_ecommerce_checkout', [$this, 'addCheckoutAction']),
            new \Twig_SimpleFunction('google_ecommerce_purchase', [$this, 'addPurchaseAction']),
        ];
    }

    /**
     * @param ProductVariant $variant
     * @param null|string    $list
     * @param null|int       $position
     */
    public function addImpression(ProductVariant $variant, $list = null, $position = null)
    {
        $this->client->addImpression($variant, compact('list', 'position'));
    }

    /**
     * @param ProductVariant $variant
     */
    public function addDetailsImpression(ProductVariant $variant)
    {
        $this->client->addDetailsImpression($variant);
    }

    /**
     * @param Order         $order
     * @param null|string[] $options
     */
    public function addCheckoutAction(Order $order, array $options = null)
    {
        $this->client->addCheckoutAction($order, $options);
    }

    /**
     * @param Order $order
     */
    public function addPurchaseAction(Order $order)
    {
        $this->client->addPurchaseAction($order);
    }
}
