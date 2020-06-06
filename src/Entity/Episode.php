<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeRepository::class)
 */
class Episode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $invites;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $sequence;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $date_creation;

    /**
     * @ORM\ManyToOne(targetEntity=Podcast::class, inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $podcast;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getInvites(): ?string
    {
        return $this->invites;
    }

    public function setInvites(?string $invites): self
    {
        $this->invites = $invites;

        return $this;
    }

    public function getSequence(): ?string
    {
        return $this->sequence;
    }

    public function setSequence(?string $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getPodcast(): ?Podcast
    {
        return $this->podcast;
    }

    public function setPodcast(?Podcast $podcast): self
    {
        $this->podcast = $podcast;

        return $this;
    }
}
