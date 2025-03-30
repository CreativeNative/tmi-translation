<?php

declare(strict_types=1);

namespace TmiTranslation\Enum;

class TranslationCategory implements TranslationCategoryInterface
{
    public static function getCategories(): array
    {
        return [
            self::DEFAULT => 'Default',
            self::CATEGORY_NAME => 'Category Name',
            self::CATEGORY_SLUG => 'Category Slug',
            self::EQUIPMENT_NAME => 'Equipment Name',
            self::EQUIPMENT_SLUG => 'Equipment Slug',
            self::EQUIPMENT_CATEGORY => 'Equipment Category',
            self::FAQ_CATEGORY_NAME => 'FAQ Category Name',
            self::FAQ_CATEGORY_SLUG => 'FAQ Category Slug',
        ];
    }
}
