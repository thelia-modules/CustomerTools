<?php

namespace CustomerTools\Event;

use Thelia\Core\Event\ActionEvent;
use Thelia\Model\Base\Customer;

class CustomerToolsEventDelete extends ActionEvent
{
    /** @var Customer $customer */
    protected $customer;

    /** @var bool $isProtected */
    protected $isProtected;

    /**
     * @param Customer $customer
     * @param bool $isProtected
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        $this->isProtected = true;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return bool
     */
    public function isProtected(): bool
    {
        return $this->isProtected;
    }

    /**
     * @param bool $isProtected
     */
    public function setIsProtected(bool $isProtected): void
    {
        $this->isProtected = $isProtected;
    }
}