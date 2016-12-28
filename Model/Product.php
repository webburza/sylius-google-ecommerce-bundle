<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\Model;

use Sylius\Component\Core\Model\ChannelInterface as Channel;
use Sylius\Component\Core\Model\ProductVariantInterface as ProductVariant;

/**
 * Class Impression.
 */
class Product implements \JsonSerializable
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $category;
    /** @var string */
    private $brand;
    /** @var string */
    private $variant;
    /** @var string */
    private $list;
    /** @var int */
    private $position;
    /** @var float */
    private $price;
    /** @var int */
    private $quantity;

    /**
     * @param Channel        $channel
     * @param ProductVariant $variant
     * @param null|string[]  $options
     *
     * @return Product
     */
    public static function createFromProductVariant(Channel $channel, ProductVariant $variant, array $options = null)
    {
        $options = array_merge(
            [
                'list' => null,
                'position' => null,
                'quantity' => null,
                'variant' => null,
            ],
            (array) $options
        );

        $channelPricing = $variant->getChannelPricingForChannel($channel);
        $price = ($channelPricing->getPrice() / 100);
        $product = $variant->getProduct();

        $instance = new self();
        $instance
            ->setId($product->getId())
            ->setName($product->getName())
            ->setPrice($price)
            ->setQuantity($options['quantity'])
            ->setList($options['list'])
            ->setPosition($options['position'])
            ->setVariant($options['variant']);

        return $instance;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     *
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     *
     * @return Product
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return string
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @param string $variant
     *
     * @return Product
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * @return string
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param string $list
     *
     * @return Product
     */
    public function setList($list)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return Product
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array_filter(
            [
                'id' => $this->getId(),
                'name' => $this->getName(),
                'category' => $this->getCategory(),
                'brand' => $this->getBrand(),
                'variant' => $this->getVariant(),
                'list' => $this->getList(),
                'position' => $this->getPosition(),
                'price' => $this->getPrice(),
                'quantity' => $this->getQuantity(),
            ]
        );
    }
}
