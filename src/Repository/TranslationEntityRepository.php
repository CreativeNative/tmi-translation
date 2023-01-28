<?php

declare(strict_types=1);

namespace TmiTranslation\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Gedmo\Translatable\TranslatableListener;

class TranslationEntityRepository extends TranslationRepository
{
    private string $defaultLocale = 'de_DE';

    public function setDefaultLocale(string $locale): void
    {
        $this->defaultLocale = $locale;
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    /**
     * Returns translated single result for given locale
     *
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getSingleResult(
        QueryBuilder $queryBuilder,
        ?string $locale = null,
        ?string $cacheName = null,
        string|int|null $hydrationMode = null
    ) {
        $translatedQuery = $this->getTranslatedQuery($queryBuilder, $locale);

        if ($cacheName !== null) {
            $translatedQuery->enableResultCache(null, $cacheName);
        }

        return $translatedQuery->getSingleResult($hydrationMode);
    }

    /**
     * Returns translated one (or null if not found) result for given locale
     *
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getOneOrNullResult(
        QueryBuilder $queryBuilder,
        ?string $locale = null,
        ?string $cacheName = null,
        string|int|null $hydrationMode = AbstractQuery::HYDRATE_OBJECT
    ) {
        $translatedQuery = $this->getTranslatedQuery($queryBuilder, $locale);

        if ($cacheName !== null) {
            $translatedQuery->enableResultCache(null, $cacheName);
        }

        return $translatedQuery->getOneOrNullResult($hydrationMode);
    }

    /**
     * @return mixed
     */
    public function getResult(
        QueryBuilder $queryBuilder,
        ?string $locale = null,
        ?string $cacheName = null,
        string|int|null $hydrationMode = AbstractQuery::HYDRATE_OBJECT
    ) {
        $translatedQuery = $this->getTranslatedQuery($queryBuilder, $locale);

        if ($cacheName !== null) {
            $translatedQuery->enableResultCache(null, $cacheName);
        }

        return $translatedQuery->getResult($hydrationMode);
    }

    /**
     * @return array<array-key, object>
     */
    public function getArrayResult(
        QueryBuilder $queryBuilder,
        ?string $locale = null,
        ?string $cacheName = null
    ): array {
        $translatedQuery = $this->getTranslatedQuery($queryBuilder, $locale);

        if ($cacheName !== null) {
            $translatedQuery->enableResultCache(null, $cacheName);
        }

        return $translatedQuery->getArrayResult();
    }

    /**
     * @return array<array-key, object>
     */
    public function getPaginatorArrayResult(
        QueryBuilder $queryBuilder,
        ?string $locale = null,
        ?string $cacheName = null,
        int $maxResults = 8
    ): ?array {
        $translatedQuery = $this->getTranslatedQuery($queryBuilder, $locale);

        if ($cacheName !== null) {
            $translatedQuery->enableResultCache(null, $cacheName);
        }

        $adapter = new DoctrinePaginator(
            new ORMPaginator(
                $translatedQuery->setHydrationMode(AbstractQuery::HYDRATE_ARRAY),
                true
            )
        );

        return $adapter->getItems(0, $maxResults)->getArrayCopy();
    }

    /**
     * Returns translated scalar result for given locale
     *
     * @return array<array-key, object>
     */
    public function getScalarResult(
        QueryBuilder $queryBuilder,
        ?string $locale = null,
        ?string $cacheName = null
    ): array {
        $translatedQuery = $this->getTranslatedQuery($queryBuilder, $locale);

        if ($cacheName !== null) {
            $translatedQuery->enableResultCache(null, $cacheName);
        }

        return $translatedQuery->getScalarResult();
    }

    /**
     * Returns translated single scalar result for given locale
     *
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getSingleScalarResult(QueryBuilder $queryBuilder, ?string $locale = null, ?string $cacheName = null)
    {
        $translatedQuery = $this->getTranslatedQuery($queryBuilder, $locale);

        if ($cacheName !== null) {
            $translatedQuery->enableResultCache(null, $cacheName);
        }

        return $translatedQuery->getSingleScalarResult();
    }

    protected function getTranslatedQuery(QueryBuilder $queryBuilder, ?string $locale = null): Query
    {
        $locale = $locale ?? $this->defaultLocale;

        $query = $queryBuilder->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            TranslationWalker::class
        );

        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);

        return $query;
    }
}
