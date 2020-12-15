<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @ApiResource(
 * attributes={
 *      "security" = "(is_granted('ROLE_Admin') or is_granted('ROLE_Formateur'))",
 *      "security_message" = "vous n'avez pas accès a cette resource"
 *   },
 *
 * subresourceOperations={
 *     "tags_get_subresource"={
 *          "method" = "GET",
 *          "path"  = "/admin/grptags/{id}/tags",
 *          "normalization_context"={"groups"={"gpetags:read"}}
 *      },
 *
 * },
 *  collectionOperations={
 *      "get_grpe_tags"={
 *          "normalization_context"={"groups"={"grpeTags:read"}},
 *          "method" = "GET",
 *          "path" = "/admin/grptags"
 *  },
 *
 *  "create_grpe_tags"={
 *     "method" = "POST",
 *      "route_name" = "create_grpe_tags",
 *      "path" = "/admin/grptags"
 *   }
 * },
 *
 * itemOperations={
 *      "get_one_grpe_tags"={
 *          "normalization_context"={"groups"={"grpeTags:read"}},
 *          "method" = "GET",
 *          "path"  = "/admin/grptags/{id}"
 *      },
 *
 *      "edit_tags"={
 *          "method" = "PUT",
 *          "path"  = "/admin/grptags/{id}"
 *      }
 * }
 * )
 */
class GroupeTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gpetags:read"})
     */
    private $descriptif;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"gpetags:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags")
     * @ApiSubresource
     */
    private $tag;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
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
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }
}
