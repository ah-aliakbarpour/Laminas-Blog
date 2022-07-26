<?php

namespace Blog\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class DisplayDateHelper extends AbstractHelper
{
    public function __invoke($dateStr)
    {
        return date('d M Y H:i:s', strtotime($dateStr));
    }
}