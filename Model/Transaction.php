<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\Model;

use Sylius\Component\Core\Model\Order as SyliusOrder;
use Sylius\Component\Core\Model\AdjustmentInterface;

/**
 * Class Transaction.
 */
class Transaction implements \JsonSerializable
{
    /** @var string */
    private $id;
    /** @var string */
    private $affiliation;
    /** @var float */
    private $revenue;
    /** @var float */
    private $tax;
    /** @var float */
    private $shipping;
    /** @var string */
    private $coupon;

    /**
     * @param SyliusOrder $order
     *
     * @return Transaction
     */
    public static function createFromOrder(SyliusOrder $order)
    {
        $totalAmount = $order->getTotal() / 100;
        $taxAmount = $order->getAdjustmentsTotal(AdjustmentInterface::TAX_ADJUSTMENT) / 100;
        $shippingAmount = $order->getAdjustmentsTotal(AdjustmentInterface::SHIPPING_ADJUSTMENT) / 100;

        // $coupon = $order->getPromotionCoupons()->current();
        $coupon = null;
        $instance = new self();
        $instance
            ->setId($order->getId())
            ->setRevenue($totalAmount)
            ->setTax($taxAmount)
            ->setShipping($shippingAmount)
            ->setCoupon($coupon);

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
     * @return Transaction
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getAffiliation()
    {
        return $this->affiliation;
    }

    /**
     * @param string $affiliation
     *
     * @return Transaction
     */
    public function setAffiliation($affiliation)
    {
        $this->affiliation = $affiliation;

        return $this;
    }

    /**
     * @return float
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * @param float $revenue
     *
     * @return Transaction
     */
    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     *
     * @return Transaction
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * @return float
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param float $shipping
     *
     * @return Transaction
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * @return string
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * @param string $coupon
     *
     * @return Transaction
     */
    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array_filter(
            [
                'id' => $this->getId(),
                'affiliation' => $this->getAffiliation(),
                'revenue' => $this->getRevenue(),
                'tax' => $this->getTax(),
                'shipping' => $this->getShipping(),
                'coupon' => $this->getCoupon(),
            ]
        );
    }
}
