<?php

namespace Blog\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class LolaPlugin extends AbstractPlugin
{
    public function __invoke()
    {
        echo '<pre>';
        var_dump('LOLA', 'LolaPlugin');
        echo '</pre>';
        exit;
    }
}