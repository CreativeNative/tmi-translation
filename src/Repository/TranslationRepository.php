<?php

declare(strict_types=1);

namespace TmiTranslation\Repository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use TmiTranslation\Entity\TranslationEntity;
use TmiTranslation\Enum\TranslationCategoryInterface;

/**
 * @extends EntityRepository<TranslationEntity>
 */
class TranslationRepository extends EntityRepository
{
    /**
     * @return array<array-key, object>
     */
    public function findAll(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->orderBy('Translation.id', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Query for updating a guide entry
     *
     * - NOT CACHED -
     *
     * @throws NonUniqueResultException
     */
    public function findById(int $id): ?TranslationEntity
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->where($queryBuilder->expr()->eq('Translation.id', ':id'))
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array<array-key, object>
     */
    public function forCategoryName(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->where($queryBuilder->expr()->eq('Translation.category', ':category'))
            ->setParameter('category', TranslationCategoryInterface::CATEGORY_NAME, Types::INTEGER);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<array-key, object>
     */
    public function forCategorySlug(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->where($queryBuilder->expr()->eq('Translation.category', ':category'))
            ->setParameter('category', TranslationCategoryInterface::CATEGORY_SLUG, Types::INTEGER);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<array-key, object>
     */
    public function forEquipmentName(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->where($queryBuilder->expr()->eq('Translation.category', ':category'))
            ->setParameter('category', TranslationCategoryInterface::EQUIPMENT_NAME, Types::INTEGER);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<array-key, object>
     */
    public function forEquipmentSlug(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->where($queryBuilder->expr()->eq('Translation.category', ':category'))
            ->setParameter('category', TranslationCategoryInterface::EQUIPMENT_SLUG, Types::INTEGER);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<array-key, object>
     */
    public function forEquipmentCategory(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->where($queryBuilder->expr()->eq('Translation.category', ':category'))
            ->setParameter('category', TranslationCategoryInterface::EQUIPMENT_CATEGORY, Types::INTEGER);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<array-key, object>
     */
    public function forFaqCategoryName(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->where($queryBuilder->expr()->eq('Translation.category', ':category'))
            ->setParameter('category', TranslationCategoryInterface::FAQ_CATEGORY_NAME, Types::INTEGER);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<array-key, object>
     */
    public function forFaqCategorySlug(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('Translation')
            ->from(TranslationEntity::class, 'Translation')
            ->where($queryBuilder->expr()->eq('Translation.category', ':category'))
            ->setParameter('category', TranslationCategoryInterface::FAQ_CATEGORY_SLUG, Types::INTEGER);

        return $queryBuilder->getQuery()->getResult();
    }
}
