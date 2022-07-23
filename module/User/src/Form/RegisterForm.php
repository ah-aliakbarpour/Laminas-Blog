<?php

namespace User\Form;

use Laminas\Db\Adapter\Adapter;
use Laminas\Filter\StringToLower;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Db\NoRecordExists;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Identical;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use User\Model\Repository\UserRepository;

class RegisterForm extends Form
{
    public function __construct()
    {
        // Define form name
        parent::__construct('register');
        // Set method
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements(): void
    {
        // Name
        $this->add([
            'type' => Text::class,
            'name' => 'name',
            'options' => [
                'label' => 'Name',
            ],
            'attributes' => [
                'required' => true,
                'size' => 40,
                'maxlength' => 25,
                'pattern' => '^[a-zA-Z0-9 ]+$',
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'placeholder' => 'Enter Your Name',
            ]
        ]);

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

        // Confirm Password
        $this->add([
            'type' => Password::class,
            'name' => 'confirm_password',
            'options' => [
                'label' => 'Confirm Password',
            ],
            'attributes' => [
                'required' => true,
                'size' => 40,
                'maxlength' => 25,
                'autocomplete' => false,
                'data-toggle' => 'tooltip',
                'class' => 'form-control',
                'placeholder' => 'Repeat Your Password',
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

    private function addInputFilter(): void
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // name
        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 3,
                        'max' => 25,
                    ],
                ],
            ],
        ]);

        //email
        $inputFilter->add([
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
        ]);

        // Password
        $inputFilter->add([
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
        ]);

        // Confirm Password
        $inputFilter->add([
            'name' => 'confirm_password',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                ['name' => NotEmpty::class],
                [
                    'name' => Identical::class,
                    'options' => [
                        'token' => 'password',
                        'messages' => [
                            Identical::NOT_SAME => 'Passwords do not match!',
                        ],
                    ],
                ],
            ],
        ]);
    }
}