<?php

namespace TmiTranslation\Filter;

use Laminas\Filter\FilterInterface;
use voku\helper\HtmlMin;

class MinifyHtml implements FilterInterface
{
    public function filter($value)
    {
        $htmlMin = new HtmlMin();
        $htmlMin->doRemoveOmittedQuotes(false);
        return $htmlMin->minify($value);
    }
}
