<?php

namespace CustomerTools\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\CustomerQuery;

class ConfigurationController extends BaseAdminController
{
    public function deleteCustomerWithoutOrder(){
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ["CustomerTools"], AccessManager::UPDATE)) {
            return $response;
        }

        $response = null;

        $form = $this->createForm('delete_customer_without_order_form');

        try {
            $data = $this->validateForm($form)->getData();

            if ($data['start_date'] < $data['end_date']) {

                CustomerQuery::create()
                    ->leftJoinOrder()
                    ->filterByCreatedAt(array('min' => $data['start_date']))
                    ->filterByCreatedAt(array('max' => $data['end_date']))
                    ->where('order.id IS NULL')
                    ->find()
                    ->delete();

                return $this->generateSuccessRedirect($form);
            }
            $error_message = "Error : " . $data['start_date']->format('d/m/Y') . " > " . $data['end_date']->format('d/m/Y');
        } catch (FormValidationException $e){
            $error_message = $this->createStandardFormValidationErrorMessage($e);
        } catch (\Exception $e){
            $error_message = $e->getMessage();
        }

        $form->setErrorMessage($error_message);

        $this->getParserContext()
            ->addForm($form)
            ->setGeneralError($error_message);

        return $this->generateErrorRedirect($form);
    }
}