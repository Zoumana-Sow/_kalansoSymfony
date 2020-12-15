<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *     attributes={
 *     "security" = "is_granted('ROLE_Admin')", "is_granted('ROLE_Formateur')",
 *     "security_message" = "vous n'avez pas accès a cette page",
 *     "normalizationContext"={"groups"={"grp:read"}},
 *     },
 *     routePrefix="admin",
 *     collectionOperations={
 *     "get",
 *     "get_Apprenant"={
 *          "method" = "get",
 *          "path" = "/groupes/apprenants",
 *          "normalization_context"={"groups"={"grpA:read"}},
 *     },
 *     "post"={
 *          "path" = "/groupes",
 *          "denormalization_context"={"groups"={"grp:write"}}
 * }
 * },
 *     itemOperations={
 *     "get"
 * ,"put"={
 *     "denormalization_context"={"groups"={"grp:write"}}
 *     },
 *     "delete_Apprenant"={
 *     "method" = "delete",
 *     "path" = "/groupes/{id}/apprenants/{id1}",
 *     "denormalization_context"={"groups"={"grp:write"}}
 *     }
 *     }

 * )
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @ApiSubresource
     * @Groups({"grp:read","grp:write","grpA:read"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @Groups({"grp:read","grp:write"})
     */
    private $formateur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grp:read","grp:write","grpA:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     * @Groups({"grp:read","grp:write","grpA:read"})
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grp:read","grp:write","grpA:read"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grp:read","grp:write","grpA:read"})
     */
    private $type;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->formateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenant->removeElement($apprenant);

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateur->removeElement($formateur);

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }


}
