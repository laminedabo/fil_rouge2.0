<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *  normalizationContext = {"groups" = {"profil_read"}},
 *  collectionOperations = {
 *      "get" = {
 *          "method" = "GET",
 *          "path" = "/admin/profils",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "action non autorisé",
 *          "normalizationContext" = {"groups" = {"profil_user_read"}}
 *      },
 *      "post" = {
 *          "method" = "POST",
 *          "path" = "/admin/profils",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "action non autorisé",
 *      }
 * },
 * itemOperations = {
 *      "get" = {
 *          "method" = "GET",
 *          "path" = "/admin/profils/{id}",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "accès non autorisé",
 *          "normalizationContext" = {"groups" = {"profil_read"}}
 *      },
 *      "put" = {
 *          "method" = "PUT",
 *          "path" = "/admin/profils/{id}",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "action non autorisé",
 *      },
 *      "delete" = {
 *          "method" = "DELETE",
 *          "path" = "/admin/profils{id}",
 *          "security" = "is_granted('ROLE_ADMIN')",
 *          "security_message" = "action non autorisé",
 *      }
 * }
 * )
 * @ApiFilter(SearchFilter::class, properties={"etat":"exact", "libelle":"exact"})
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil_read","profil_user_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil_read","profil_user_read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource()
     * @Groups({"profil_read"})
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"actif"})
     */
    private $etat;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
