<?php

namespace Blog\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class ButtonHelper extends AbstractHelper
{
    private function body($type, $link, $value)
    {
        return "<a class=\"btn btn-$type\" href=\"$link\">$value</a>";
    }

    public function primary($link, $value): string
    {
        return $this->body('primary', $link, $value);
    }

    public function primaryOutline($link, $value): string
    {
        return $this->body('outline-primary', $link, $value);
    }

    public function success($link, $value): string
    {
        return $this->body('success', $link, $value);
    }

    public function danger($link, $value): string
    {
        return $this->body('danger', $link, $value);
    }
}