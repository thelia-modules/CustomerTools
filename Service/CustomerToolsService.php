<?php

namespace CustomerTools\Service;

use CustomerTools\Event\CustomerToolsEventDelete;
use CustomerTools\Event\CustomerToolsEvents;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Log\Tlog;
use Thelia\Model\Base\Customer;
use Thelia\Model\Base\CustomerQuery;

class CustomerToolsService
{
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param $customer
     * @return int
     */
    public function deleteCustomer(ObjectCollection $customers)
    {
        /** @var Customer $customer */
        foreach ($customers as $key => $customer) {
            try {
                $event = new CustomerToolsEventDelete($customer);
                $this->dispatcher->dispatch(CustomerToolsEvents::DELETE, $event);

                if ($event->isProtected()) {
                    unset($customers[$key]);
                    continue;
                }

                $customer->delete();

            } catch (\Exception $ex) {
                // LOG
                Tlog::getInstance()->log(Tlog::ERROR, 'Error on delete customer : ' . $ex->getMessage());
                continue;
            }
        }

        return count($customers);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return \Propel\Runtime\Collection\ObjectCollection|\Thelia\Model\Customer[]
     */
    public function getCustomertoDelete(string $startDate, string $endDate): ObjectCollection
    {
        $customers = CustomerQuery::create()
            ->filterByCreatedAt(['min' => $startDate])
            ->filterByCreatedAt(['max' => $endDate])
            ->useOrderQuery('', Criteria::LEFT_JOIN)
            ->filterById(null, Criteria::ISNULL)
            ->endUse()
            ->find();

        return $customers;
    }
}