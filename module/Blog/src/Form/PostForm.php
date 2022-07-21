<?php

namespace Blog\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;

class PostForm extends Form
{
    public function __construct()
    {
        parent::__construct('post');

        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        // Title
        $this->add([
            'type'  => Text::class,
            'name' => 'title',
            'attributes' => [
                'id' => 'title'
            ],
            'options' => [
                'label' => 'Title',
            ],
        ]);

        // Context
        $this->add([
            'type'  => Textarea::class,
            'name' => 'context',
            'attributes' => [
                'id' => 'context'
            ],
            'options' => [
                'label' => 'Context',
            ],
        ]);

        // Submit
        $this->add([
            'type'  => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create',
                'id' => 'submitbutton',
            ],
        ]);
    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Title
        $inputFilter->add([
            'name'     => 'title',
            'required' => true,
            'filters'  => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'min' => 3,
                        'max' => 128,
                    ],
                ],
            ],
        ]);

        // Context
        $inputFilter->add([
            'name'     => 'context',
            'required' => true,
            'filters'  => [
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ],
                ],
            ],
        ]);
    }
}