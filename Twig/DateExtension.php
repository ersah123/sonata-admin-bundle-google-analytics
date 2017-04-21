<?php

namespace Ersah\GABundle\Twig;


class DateExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('dashedDate', array($this, 'addDashesToDate')),
        );
    }

    public function addDashesToDate($string)
    {
        return preg_replace("/^(\d{4})(\d{2})(\d{2})$/", "$1-$2-$3", $string);
    }
}