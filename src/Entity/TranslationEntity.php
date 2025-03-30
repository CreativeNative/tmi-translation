<?php

declare(strict_types=1);

namespace TmiTranslation\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use TmiTranslation\Repository\TranslationRepository;

/**
 * @ORM\Entity(repositoryClass=TranslationRepository::class)
 * @ORM\Table(
 *     name="translation",
 *     options={
 *         "charset":"utf8mb4",
 *         "collate":"utf8mb4_unicode_520_ci",
 *         "engine":"InnoDB"
 *      },
 * )
 */
class TranslationEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int|null $id = null;

    /** @ORM\Column(type="string", name="translation_key", length=150, unique=true) */
    private string $translationKey;

    /** @ORM\Column(type="text") */
    private string $german;

    /** @ORM\Column(type="text") */
    private string $english;

    /** @ORM\Column(type="text") */
    private string $italian;

    /** @ORM\Column(type="string", length=50, options={"default": "default"}) */
    private string $domain;

    /** @ORM\Column(type="smallint", options={"default":0}) */
    private int $category;

    public function __construct()
    {
        $this->translationKey = '';
        $this->german         = '';
        $this->english        = '';
        $this->italian        = '';
        $this->domain         = 'default';
        $this->category       = 0;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    public function setTranslationKey(string $translationKey): self
    {
        if (empty($translationKey)) {
            throw new InvalidArgumentException('Translation key cannot be empty.');
        }
        $this->translationKey = $translationKey;
        return $this;
    }

    public function getGerman(): string
    {
        return $this->german;
    }

    public function setGerman(string $german): self
    {
        $this->german = $german;
        return $this;
    }

    public function getEnglish(): string
    {
        return $this->english;
    }

    public function setEnglish(string $english): self
    {
        $this->english = $english;
        return $this;
    }

    public function getItalian(): string
    {
        return $this->italian;
    }

    public function setItalian(string $italian): self
    {
        $this->italian = $italian;
        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;
        return $this;
    }
}
