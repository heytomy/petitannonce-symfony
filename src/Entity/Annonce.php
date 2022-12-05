<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
#[ORM\HasLifecycleCallbacks]

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 * @UniqueEntity("title")
 */
class Annonce
{
    const STATUS_VERY_BAD  = 0;
    const STATUS_BAD       = 1;
    const STATUS_GOOD      = 2;
    const STATUS_VERY_GOOD = 3;
    const STATUS_PERFECT   = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
        /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      min = 40,
     *      minMessage = "La description doit faire plus de {{ limit }} charactères",
     * )
     */
    private $description;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?bool $sold = false;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(
     *      protocols = {"http", "https"}
     * )
     */
    private $imageUrl;


    public function getId(): ?int
    {

        return $this->id;
    }

    public function getTitle(): ?string
    {

        return $this->title;
    }

    public function setTitle(string $title): self
    {

        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {

        return $this->description;
    }

    public function setDescription(?string $description): self
    {

        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {

        return $this->price;
    }

    public function setPrice(int $price): self
    {

        $this->price = $price;

        return $this;
    }

    public function isSold(): ?bool
    {

        return $this->sold;
    }

    public function setSold(bool $sold): self
    {

        $this->sold = $sold;

        return $this;
    }

    public function getStatus(): ?int
    {

        return $this->status;
    }

    public function setStatus(int $status): self
    {

        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {

        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {

        $this->createdAt = $createdAt;

        return $this;
    }

    public function setSlug(string $slug): self
    {

        $slugify = new Slugify();
        $this->slug = $slugify->slugify($slug);

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->slug = (new Slugify())->slugify($this->title);
        $this->updatedAt = new \DateTime();
    }

    public function getSlug(): ?string
    {
        if (!$this->slug) {
            $this->setSlug($this->title);
        }

        return $this->slug;
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

}
