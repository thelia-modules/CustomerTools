<?php

namespace CustomerTools\Form;

use CustomerTools\CustomerTools;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class DeleteCustomerWithoutOrderForm extends BaseForm
{
    // The date format for the start and end date
    const MOMENT_JS_DATE_FORMAT = "DD-MM-YYYY";

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
        'start_date',
        DateType::class,
        [
            'required'    => false,
            'label'       => Translator::getInstance()->trans('start_date', [], CustomerTools::DOMAIN_NAME),
            'label_attr'  => [
                'for'         => 'start_date',
                'help'        => Translator::getInstance()->trans("The early created date from which customer are delete. Please use %fmt format.", [ '%fmt' => self::MOMENT_JS_DATE_FORMAT], CustomerTools::DOMAIN_NAME),
            ],
            'attr' => [
                'data-date-format' => self::MOMENT_JS_DATE_FORMAT
            ],
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
        ]
            )
        ->add(
            'end_date',
            DateType::class,
            [
                'required'    => false,
                'label'       => Translator::getInstance()->trans('end_date', [], CustomerTools::DOMAIN_NAME),
                'label_attr'  => [
                    'for'         => 'end_date',
                    'help'        => Translator::getInstance()->trans("The last created date from which customer are delete. Please use %fmt format.", [ '%fmt' => self::MOMENT_JS_DATE_FORMAT], CustomerTools::DOMAIN_NAME),
                ],
                'attr' => [
                    'data-date-format' => self::MOMENT_JS_DATE_FORMAT
                ],
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ]
        );
    }

    public function getName(){
        return 'delete_customer_without_order_form';
    }
}