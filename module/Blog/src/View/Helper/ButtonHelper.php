<?php

namespace Blog\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class ButtonHelper extends AbstractHelper
{
    /**
     * @param $type
     * @param $link
     * @param $value
     * @return string
     */
    private function body($type, $link, $value)
    {
        return "<a class=\"btn btn-$type\" href=\"$link\">$value</a>";
    }

    /**
     * @param $link
     * @param $value
     * @return string
     */
    public function primary($link, $value): string
    {
        return $this->body('primary', $link, $value);
    }

    /**
     * @param $link
     * @param $value
     * @return string
     */
    public function primaryOutline($link, $value): string
    {
        return $this->body('outline-primary', $link, $value);
    }

    /**
     * @param $link
     * @param $value
     * @return string
     */
    public function success($link, $value): string
    {
        return $this->body('success', $link, $value);
    }

    /**
     * @param $link
     * @param $value
     * @return string
     */
    public function danger($link, $value): string
    {
        return $this->body('danger', $link, $value);
    }
}