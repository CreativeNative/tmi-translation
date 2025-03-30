<?php

declare(strict_types=1);

namespace TmiTranslation\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use DoctrineModule\Validator\NoObjectExists;
use Laminas\Config\Writer\PhpArray;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\Response;
use Laminas\I18n\Translator\Translator;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use TmiTranslation\Entity\TranslationEntity;
use TmiTranslation\Form\TranslationForm;
use TmiTranslation\Repository\TranslationRepository;

use function array_merge_recursive;
use function getcwd;
use function sprintf;

class TranslationBackController extends AbstractActionController
{
    protected EntityManager $entityManager;

    protected TranslationForm $translationForm;

    protected Translator $translator;

    public function __construct(
        EntityManager $entityManager,
        TranslationForm $translationForm,
        Translator $translator
    ) {
        $this->entityManager   = $entityManager;
        $this->translationForm = $translationForm;
        $this->translator      = $translator;
    }

    /**
     * @throws NotSupported
     */
    public function indexAction(): ViewModel
    {
        /** @var TranslationRepository<TranslationEntity> $repository */
        $repository = $this->entityManager->getRepository(TranslationEntity::class);

        $entity = $repository->findAll();

        $messages = [];

        /** @var Request $request */
        $request = $this->getRequest();

        $params = $request->getQuery();
        if (!empty($params['delete'])) {
            $messages[] = [
                'message' => sprintf($this->translator->translate(
                    "Record with ID %s was successfully deleted."
                ), $params['delete']),
                'class'   => 'alert-danger',
            ];
        }

        if ($request->isPost()) {
            $data = $request->getPost();

            $writer = new PhpArray();
            $writer->setUseBracketArraySyntax(true);

            if (isset($data['translation']) && $data['translation'] === 'all') {
                $german  = [];
                $english = [];
                $italian = [];
                foreach ($entity as $translation) {
                    /** @var TranslationEntity $translation */
                    $german[$translation->getDomain() . '.' . $translation->getTranslationKey()]  = $translation->getGerman();
                    $english[$translation->getDomain() . '.' . $translation->getTranslationKey()] = $translation->getEnglish();
                    $italian[$translation->getDomain() . '.' . $translation->getTranslationKey()] = $translation->getItalian();
                }

                $writer->toFile(getcwd() . '/data/language/de_DE.php', $german);
                $writer->toFile(getcwd() . '/data/language/en_US.php', $english);
                $writer->toFile(getcwd() . '/data/language/it_IT.php', $italian);

                $messages[] = [
                    'message' => '<img class="flag" src="/resources/application/img/germany.png" width="16" 
                                    height="16" alt="german">&nbsp;<img class="flag" 
                                    src="/resources/application/img/usa.png" width="16" 
                                    height="16" alt="english">&nbsp;<img class="flag" 
                                    src="/resources/application/img/italy.png" width="16" 
                                    height="16" alt="italian">&nbsp;Alle Übersetzungen wurden erstellt!',
                    'class'   => 'alert-success',
                ];
            }

            // Wiederholen Sie den Vorgang für die einzelnen Sprachen
            if (isset($data['translation']) && $data['translation'] === 'german') {
                $german = [];
                foreach ($entity as $translation) {
                    /** @var TranslationEntity $translation */
                    $german[$translation->getDomain() . '.' . $translation->getTranslationKey()] = $translation->getGerman();
                }

                $writer->toFile(getcwd() . '/data/language/de_DE.php', $german);

                $messages[] = [
                    'message' => '<img class="flag" src="/resources/application/img/germany.png" width="16" 
                                   height="16" alt="german"> Deutsche Übersetzung wurde erstellt!',
                    'class'   => 'alert-success',
                ];
            }

            if (isset($data['translation']) && $data['translation'] === 'english') {
                $english = [];
                foreach ($entity as $translation) {
                    /** @var TranslationEntity $translation */
                    $english[$translation->getDomain() . '.' . $translation->getTranslationKey()] = $translation->getEnglish();
                }

                $writer->toFile(getcwd() . '/data/language/en_US.php', $english);

                $messages[] = [
                    'message' => '<img class="flag" src="/resources/application/img/usa.png" width="16" 
                                   height="16" alt="english"> Englische Übersetzung wurde erstellt!',
                    'class'   => 'alert-success',
                ];
            }

            if (isset($data['translation']) && $data['translation'] === 'italian') {
                $italian = [];
                foreach ($entity as $translation) {
                    /** @var TranslationEntity $translation */
                    $italian[$translation->getDomain() . '.' . $translation->getTranslationKey()] = $translation->getItalian();
                }

                $writer->toFile(getcwd() . '/data/language/it_IT.php', $italian);

                $messages[] = [
                    'message' => '<img class="flag" src="/resources/application/img/italy.png"  width="16"
                                   height="16" alt="italian"> Italienische Übersetzung wurde erstellt!',
                    'class'   => 'alert-success',
                ];
            }
        }

        return new ViewModel(['translation' => $entity, 'messages' => $messages]);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createAction(): Response|ViewModel
    {
        $form = $this->translationForm;

        $entity = new TranslationEntity();

        $form->bind($entity);

        /** @var Request $request */
        $request = $this->getRequest();

        $message = [];

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();

            $form->setData($data);

            $validator = new NoObjectExists([
                'object_repository' => $this->entityManager->getRepository(TranslationEntity::class),
                'fields'            => ['translationKey'],
                'messages'          => [
                    NoObjectExists::ERROR_OBJECT_FOUND => "This input already exists.",
                ],
            ]);

            $form->getInputFilter()->get('translationKey')->getValidatorChain()->attach($validator);

            if ($form->isValid()) {
                /** @var TranslationEntity $validatedEntity */
                $validatedEntity = $form->getData();

                $this->entityManager->persist($validatedEntity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('translation/edit', [
                    'id' => $validatedEntity->getId(),
                ]);
            }

            $message = [
                'message' => 'message_persistens_incomplete',
                'class'   => 'alert-danger',
            ];
        }

        return new ViewModel(['form' => $form, 'message' => $message]);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws NonUniqueResultException
     */
    public function editAction(): Response|ViewModel
    {
        $id = (int) $this->params()->fromRoute('id');

        if ($id === 0) {
            return $this->redirect()->toRoute('translation');
        }

        /** @var TranslationRepository<TranslationEntity> $repository */
        $repository = $this->entityManager->getRepository(TranslationEntity::class);

        $entity = $repository->findById($id);

        if ($entity === null) {
            return $this->redirect()->toRoute('translation');
        }

        $form = $this->translationForm;

        $form->bind($entity);

        /** @var Request $request */
        $request = $this->getRequest();

        $message = [];

        if ($request->isPost()) {
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            if ($form->isValid()) {
                /** @var TranslationEntity $validatedEntity */
                $validatedEntity = $form->getData();

                $this->entityManager->persist($validatedEntity);
                $this->entityManager->flush();

                $message = [
                    'message' => 'message_persistens_complete',
                    'class'   => 'alert-success',
                ];
            } else {
                $message = [
                    'message' => 'message_persistens_incomplete',
                    'class'   => 'alert-danger',
                ];
            }
        }

        return new ViewModel(['form' => $form, 'entity' => $entity, 'message' => $message]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteAction(): Response|ViewModel
    {
        $id = (int) $this->params()->fromRoute('id');

        if ($id === 0) {
            return $this->redirect()->toRoute('translation');
        }

        /** @var TranslationRepository<TranslationEntity> $repository */
        $repository = $this->entityManager->getRepository(TranslationEntity::class);

        $entity = $repository->findById($id);

        if ($entity === null) {
            return $this->redirect()->toRoute('translation');
        }

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            if ($data['delete']) {
                $this->entityManager->remove($entity);
                $this->entityManager->flush();

                return $this->redirect()->toRoute(
                    'translation',
                    [],
                    ['query' => ['delete' => $id]]
                );
            }
        }
        return new ViewModel(['entity' => $entity]);
    }
}
