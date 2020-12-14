<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentsRepository::class)
 */
class Documents
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Folders::class, inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forlders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getForlders(): ?Folders
    {
        return $this->forlders;
    }

    public function setForlders(?Folders $forlders): self
    {
        $this->forlders = $forlders;

        return $this;
    }
}
