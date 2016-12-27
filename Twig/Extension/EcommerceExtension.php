<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\Twig\Extension;

use Sylius\Component\Core\Model\Order as SyliusOrder;
use Sylius\Component\Core\Model\Product as SyliusProduct;
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
     * @param SyliusProduct $product
     * @param null|string   $list
     * @param null|int      $position
     */
    public function addImpression(SyliusProduct $product, $list = null, $position = null)
    {
        $this->client->addImpression($product, compact('list', 'position'));
    }

    /**
     * @param SyliusProduct $product
     */
    public function addDetailsImpression(SyliusProduct $product)
    {
        $this->client->addDetailsImpression($product);
    }

    /**
     * @param SyliusOrder   $order
     * @param null|string[] $options
     */
    public function addCheckoutAction(SyliusOrder $order, array $options = null)
    {
        $this->client->addCheckoutAction($order, $options);
    }

    /**
     * @param SyliusOrder $order
     */
    public function addPurchaseAction(SyliusOrder $order)
    {
        $this->client->addPurchaseAction($order);
    }
}
