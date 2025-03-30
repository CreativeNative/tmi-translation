<?php

declare(strict_types=1);

namespace TmiTranslation\Filter;

use Laminas\Filter\FilterInterface;
use voku\helper\HtmlMin;

final class MinifyHtml implements FilterInterface
{
    public function filter(mixed $value): string
    {
        $htmlMin = new HtmlMin();
        $htmlMin->doRemoveOmittedQuotes(false);
        $htmlMin->doRemoveOmittedHtmlTags(false);
        return $htmlMin->minify($value);
    }
}
