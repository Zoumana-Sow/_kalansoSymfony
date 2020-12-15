<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @UniqueEntity(
 *    fields = {"libelle"},
 *     message = "Ce libelle est déjà utilisé!")
 * @ApiResource(
 * attributes={
 *      "security" = "(is_granted('ROLE_Admin') or is_granted('ROLE_Formateur'))",
 *      "security_message" = "vous n'avez pas accès a cette ressource"
 *   },
 *
 * collectionOperations={
 *      "get_tags"={
 *          "normalization_context"={"groups"={"tags:read"}},
 *          "method" = "GET",
 *          "path" = "/admin/tags"
 *      },
 *
 *      "create_tags"={
 *          "method" = "POST",
 *          "path" = "/admin/tags"
 *      }
 * },
 *
 * itemOperations={
 *
 *      "get_one_tags"={
 *          "normalization_context"={"groups"={"tags:read"}},
 *          "method" = "GET",
 *          "path" = "/admin/tags/{id}"
 *      },
 *
 *      "edit_tags"={
 *          "method" = "PUT",
 *          "path" = "/admin/tags/{id}"
 *      }
 * }
 * )
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpeTags:read","tags:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpeTags:read","tags:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tag")
     * @Groups({"tags:read"})
     */
    private $groupeTags;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
        }

        return $this;
    }
}
