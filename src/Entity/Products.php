<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: Commentaires::class)]
    private Collection $commentaire_id;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: Images::class)]
    private Collection $images;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $image = null;

    public function __construct()
    {
        $this->commentaire_id = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaireId(): Collection
    {
        return $this->commentaire_id;
    }

    public function addCommentaireId(Commentaires $commentaireId): self
    {
        if (!$this->commentaire_id->contains($commentaireId)) {
            $this->commentaire_id->add($commentaireId);
            $commentaireId->setProducts($this);
        }

        return $this;
    }

    public function removeCommentaireId(Commentaires $commentaireId): self
    {
        if ($this->commentaire_id->removeElement($commentaireId)) {
            // set the owning side to null (unless already changed)
            if ($commentaireId->getProducts() === $this) {
                $commentaireId->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProducts($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProducts() === $this) {
                $image->setProducts(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

}
