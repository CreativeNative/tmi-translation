<?php

declare(strict_types=1);

namespace TmiTranslation\Controller\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\I18n\Translator\Translator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TmiTranslation\Controller\TranslationBackController as Controller;
use TmiTranslation\Form\TranslationForm;

class TranslationBackControllerFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array|null $options = null): Controller
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var TranslationForm $translationForm */
        $translationForm = $container->get('FormElementManager')->get(TranslationForm::class);

        /** @var Translator $translator */
        $translator = $container->get(Translator::class);

        return new Controller($entityManager, $translationForm, $translator);
    }
}
