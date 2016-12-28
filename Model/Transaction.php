<?php

namespace Webburza\Sylius\GoogleEcommerceBundle\Model;

use Sylius\Component\Core\Model\OrderInterface as Order;
use Sylius\Component\Core\Model\AdjustmentInterface as Adjustment;

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
     * @param Order $order
     *
     * @return Transaction
     */
    public static function createFromOrder(Order $order)
    {
        // TODO: should extract this from payment, not guess like this
        $totalAmount = $order->getTotal() / 100;
        $taxAmount = $order->getAdjustmentsTotal(Adjustment::TAX_ADJUSTMENT) / 100;
        $shippingAmount = $order->getAdjustmentsTotal(Adjustment::SHIPPING_ADJUSTMENT) / 100;

        // TODO: complete coupon handling
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
     * {@inheritdoc}
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
