<?php

declare(strict_types=1);

namespace TmiTranslation\Enum;

/**
 * Interface TranslationCategory
 */
interface TranslationCategoryInterface
{
    public const DEFAULT            = 0;
    public const CATEGORY_NAME      = 1;
    public const CATEGORY_SLUG      = 2;
    public const EQUIPMENT_NAME     = 3;
    public const EQUIPMENT_SLUG     = 4;
    public const EQUIPMENT_CATEGORY = 5;
    public const FAQ_CATEGORY_NAME  = 6;
    public const FAQ_CATEGORY_SLUG  = 7;

    /**
     * Returns an associative array of category constants and their labels.
     *
     * @return array<int, string> An associative array of category constants and their labels.
     */
    public static function getCategories(): array;
}
