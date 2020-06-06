<?php

namespace App\Entity;

use App\Repository\PodcastRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PodcastRepository::class)
 */
class Podcast
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
    private $nomPodcast;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $typePodcast;

    /**
     * @ORM\OneToMany(targetEntity=Episode::class, mappedBy="podcast", orphanRemoval=true)
     */
    private $episodes;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPodcast(): ?string
    {
        return $this->nomPodcast;
    }

    public function setNomPodcast(?string $nomPodcast): self
    {
        $this->nomPodcast = $nomPodcast;

        return $this;
    }

    public function getTypePodcast(): ?string
    {
        return $this->typePodcast;
    }

    public function setTypePodcast(?string $typePodcast): self
    {
        $this->typePodcast = $typePodcast;

        return $this;
    }

    /**
     * @return Collection|Episode[]
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
            $episode->setPodcast($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        if ($this->episodes->contains($episode)) {
            $this->episodes->removeElement($episode);
            // set the owning side to null (unless already changed)
            if ($episode->getPodcast() === $this) {
                $episode->setPodcast(null);
            }
        }

        return $this;
    }
}
