<?php

declare(strict_types=1);

namespace TmiTranslation\Form\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use TmiTranslation\Form\TranslationForm;

class TranslationFormFactory
{
    public function __invoke(ContainerInterface $container): TranslationForm
    {
        return new TranslationForm($container->get(EntityManager::class));
    }
}
