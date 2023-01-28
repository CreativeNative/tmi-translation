<?php

declare(strict_types=1);

namespace TmiTranslation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TmiTranslation\Repository\Translation")
 * @ORM\Table(
 *     name="translation",
 *     options={
 *         "charset":"utf8mb4",
 *         "collate":"utf8mb4_unicode_520_ci",
 *         "engine":"InnoDB"
 *      },
 * )
 */
class Translation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\Column(type="string", name="translation_key", length=150, unique=true) */
    private string $translationKey = '';

    /** @ORM\Column(type="text") */
    private string $german = '';

    /** @ORM\Column(type="text") */
    private string $english = '';

    /** @ORM\Column(type="text") */
    private string $italian = '';

    /** @ORM\Column(type="smallint", options={"default":0}) */
    private int $category = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    public function setTranslationKey(string $translationKey): void
    {
        $this->translationKey = $translationKey;
    }

    public function getGerman(): string
    {
        return $this->german;
    }

    public function setGerman(string $german): void
    {
        $this->german = $german;
    }

    public function getEnglish(): string
    {
        return $this->english;
    }

    public function setEnglish(string $english): void
    {
        $this->english = $english;
    }

    public function getItalian(): string
    {
        return $this->italian;
    }

    public function setItalian(string $italian): void
    {
        $this->italian = $italian;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): void
    {
        $this->category = $category;
    }
}
