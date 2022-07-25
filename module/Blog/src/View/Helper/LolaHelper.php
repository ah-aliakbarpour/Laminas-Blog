<?php

namespace Blog\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class LolaHelper extends AbstractHelper
{
    public function __invoke()
    {

        echo '<pre>';
        var_dump('LOLA', 'LolaHelper');
        echo '</pre>';
        exit;

    }
}