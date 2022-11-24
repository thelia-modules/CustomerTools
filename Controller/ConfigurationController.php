<?php

namespace CustomerTools\Controller;

use CustomerTools\Service\CustomerToolsService;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;

class ConfigurationController extends BaseAdminController
{
    public function deleteCustomerWithoutOrder()
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ["CustomerTools"], AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm('delete_customer_without_order_form');

        try {
            $data = $this->validateForm($form)->getData();

            if (null === $data['start_date']) {
                $data['start_date'] = new \DateTime("01/01/1971");
            }

            if (null === $data['end_date']) {
                $data['end_date'] = new \DateTime("now");
            }

            if ($data['start_date'] > $data['end_date']) {
                throw new \Exception("Error : " . $data['start_date']->format('d/m/Y') . " > " . $data['end_date']->format('d/m/Y'));
            }

            /** @var CustomerToolsService $customerToolsService */
            $customerToolsService = $this->getContainer()->get('action.customer.tool.service');

            $customers = $customerToolsService->getCustomertoDelete($data['start_date']->format('d-m-Y'), $data['end_date']->format('d-m-Y'));

            $customerDeleteCount = $customerToolsService->deleteCustomer($customers);

            $url = $this->retrieveSuccessUrl($form);
            $url .= '?customer_count=' . $customerDeleteCount;

            return $this->generateRedirect($url);

        } catch (FormValidationException $e) {
            $error_message = $this->createStandardFormValidationErrorMessage($e);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
        }

        $form->setErrorMessage($error_message);

        $this->getParserContext()
            ->addForm($form)
            ->setGeneralError($error_message);

        return $this->generateErrorRedirect($form);
    }
}