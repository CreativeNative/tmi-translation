<?php

declare(strict_types=1);

namespace TmiTranslation\Form;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\ORM\EntityManager;
use Laminas\Filter;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;
use TmiTranslation\Entity\TranslationEntity;
use TmiTranslation\Enum\TranslationCategory;
use TmiTranslation\Filter\MinifyHtml;

use function array_map;

class TranslationForm extends Form
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct('translation');

        $this->setHydrator(new DoctrineHydrator($entityManager))
            ->setObject(new TranslationEntity());

        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements(): void
    {
        $this->add(
            [
                'type'       => Element\Hidden::class,
                'name'       => 'id',
                'attributes' => ['id' => 'translationId'],
            ]
        );

        $this->add(
            [
                'type'       => Element\Select::class,
                'name'       => 'category',
                'options'    => [
                    'label'         => 'Category',
                    'value_options' => array_map(
                        static fn($category) => $category,
                        TranslationCategory::getCategories()
                    ),
                ],
                'attributes' => [
                    'class' => 'form-control',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Element\Text::class,
                'name'       => 'domain',
                'options'    => [
                    'label' => 'Text Domain',
                ],
                'attributes' => [
                    'class' => 'form-control',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Element\Text::class,
                'name'       => 'translationKey',
                'options'    => [
                    'label' => 'Translation Key',
                ],
                'attributes' => [
                    'class' => 'form-control',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Element\Textarea::class,
                'name'       => 'german',
                'options'    => [
                    'label' => 'German',
                ],
                'attributes' => [
                    'id'    => 'german',
                    'class' => 'form-control',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Element\Textarea::class,
                'name'       => 'english',
                'options'    => [
                    'label' => 'English',
                ],
                'attributes' => [
                    'id'    => 'english',
                    'class' => 'form-control',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Element\Textarea::class,
                'name'       => 'italian',
                'options'    => [
                    'label' => 'Italian',
                ],
                'attributes' => [
                    'id'    => 'italian',
                    'class' => 'form-control',
                ],
            ]
        );

        $this->add(
            [
                'type'    => Element\Csrf::class,
                'name'    => 'csrf',
                'options' => [
                    'csrf_options' => [
                        'timeout' => 600,
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => Element\Submit::class,
                'name'       => 'submit',
                'options'    => [
                    'label' => 'Save',
                ],
                'attributes' => [
                    'value' => 'Save',
                    'id'    => 'submit',
                    'class' => 'btn btn-primary btn-block',
                ],
            ]
        );
    }

    private function addInputFilter(): void
    {
        $inputFilter = new InputFilter();

        $inputFilter->add(
            [
                'name'       => 'category',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\Digits::class],
                ],
                'validators' => [
                    ['name' => Validator\Digits::class],
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'domain',
                'filters'    => [
                    ['name' => Filter\StringTrim::class],
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StripNewlines::class],
                    ['name' => Filter\StringToLower::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\Regex::class,
                        'options' => [
                            'pattern' => '/^[a-z]+$/',
                        ],
                    ],
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'min' => 2,
                            'max' => 50,
                        ],
                    ],
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'translationKey',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StringTrim::class],
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StripNewlines::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'min' => 2,
                            'max' => 150,
                        ],
                    ],
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'german',
                'required'   => true,
                'filters'    => [
                    ['name' => MinifyHtml::class],
                ],
                'validators' => [
                    ['name' => Validator\NotEmpty::class],
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'english',
                'required'   => true,
                'filters'    => [
                    ['name' => MinifyHtml::class],
                ],
                'validators' => [
                    ['name' => Validator\NotEmpty::class],
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'italian',
                'required'   => true,
                'filters'    => [
                    ['name' => MinifyHtml::class],
                ],
                'validators' => [
                    ['name' => Validator\NotEmpty::class],
                ],
            ]
        );

        $this->setInputFilter($inputFilter);
    }
}
