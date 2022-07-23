<?php

namespace Blog\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;

class PostSearchForm extends Form
{
    public function __construct()
    {
        parent::__construct('post-search');

        $this->setAttribute('method', 'get');

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        // Search
        $this->add([
            'type'  => Text::class,
            'name' => 'search',
            'attributes' => [
                'id' => 'search'
            ],
            'options' => [
                'label' => 'Search',
            ],
        ]);

        // Submit
        $this->add([
            'type'  => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Search',
                'id' => 'submitbutton',
            ],
        ]);
    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Search
        $inputFilter->add([
            'name'     => 'search',
            'required' => true,
            'filters'  => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
                ['name' => StripNewlines::class],
            ],
        ]);
    }
}