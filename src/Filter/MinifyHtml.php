<?php

namespace TmiTranslation\Filter;

use Laminas\Filter\FilterInterface;
use voku\helper\HtmlMin;

class MinifyHtml implements FilterInterface
{
    public function filter($value)
    {
        return (new HtmlMin())->minify($value);
    }
}
