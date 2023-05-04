<?php

declare(strict_types=1);

namespace TmiTranslation\Form\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TmiTranslation\Form\TranslationForm;

class TranslationFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TranslationForm
    {
        return new TranslationForm($container->get(EntityManager::class));
    }
}
