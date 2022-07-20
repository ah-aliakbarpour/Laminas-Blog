<?php

namespace User\Form;

use Laminas\Filter\StringToLower;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class LoginForm extends Form
{
    public function __construct()
    {
        // Define form name
        parent::__construct('login');
        // Set method
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements(): void
    {
        // Email
        $this->add([
            'type' => Email::class,
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'required' => true,
                'size' => 40,
                'maxlength' => 128,
                'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'placeholder' => 'Enter Your Email',
            ]
        ]);

        // Password
        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'required' => true,
                'size' => 40,
                'maxlength' => 25,
                'autocomplete' => false,
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'placeholder' => 'Enter Your Password',
            ]
        ]);

        // Remember Me
        $this->add([
            'type' => Checkbox::class,
            'name' => 'remember_me',
            'options' => [
                'label' => 'Remember Me?',
                'label_attributes' => [
                    'class' => 'custom-control-label'
                ],
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
            'attributes' => [
                'value' => 0,
                'id' => 'remember_me',
                'class' => 'custom-control-input'
            ]
        ]);

        // Submit Button
        $this->add([
            'type' => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    public function addInputFilter(): void
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        //email
        $inputFilter->add(
            [
                'name' => 'email',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => StringToLower::class],
                ],
                'validators' => [
                    ['name' => NotEmpty::class],
                    ['name' => EmailAddress::class],
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 6,
                            'max' => 128,
                            'messages' => [
                                StringLength::TOO_SHORT => 'Email address must have at least 6 characters',
                                StringLength::TOO_LONG => 'Email address must have at most 128 characters',
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Password
        $inputFilter->add(
            [
                'name' => 'password',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    ['name' => NotEmpty::class],
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 8,
                            'max' => 25,
                            'messages' => [
                                StringLength::TOO_SHORT => 'Password must have at least 8 characters',
                                StringLength::TOO_LONG => 'Password must have at most 25 characters',
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}