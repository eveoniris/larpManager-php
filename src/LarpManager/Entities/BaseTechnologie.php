<?php

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * LarpManager\Entities\Technologie
 *
 * @Entity()
 * @Table(name="technologie")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BaseTechnologie", "extended":"Technologie"})
 */
class BaseTechnologie
{
    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Column(type="string", length=45)
     */
    protected ?string $label = null;

    /**
     * @Column(type="text", length=450)
     */
    protected ?string $description = null;

    /**
     * @Column(type="string", length=45, nullable=true)
     */
    protected string $documentUrl;

    /**
     * @Column(type="boolean", nullable=false, options={"default":0})
     */
    protected bool $secret;

    /**
     * @OneToMany(targetEntity="BaseTechnologiesRessources", mappedBy="technologie", cascade={"persist"})
     * @JoinColumn (name="id", referencedColumnName="technology_id", nullable=false)
     */

    protected Collection $ressources;

    /**
     * @ManyToOne(targetEntity="CompetenceFamily", inversedBy="technologies", cascade={"persist"})
     * @JoinColumn(name="competence_family_id", referencedColumnName="id", nullable=false)
     */
    protected CompetenceFamily $competenceFamily;

    /**
     * @ManyToMany(targetEntity="Personnage", mappedBy="technologies")
     */
    protected Collection $personnages;

    public function __construct()
    {
        $this->personnages = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return Technologie
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of label.
     *
     * @param string $label
     * @return Technologie
     */
    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of label.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Set the value of description.
     *
     * @param string $description
     * @return Technologie
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the value of documentUrl
     *
     * @return string
     */
    public function getDocumentUrl(): string
    {
        return $this->documentUrl;
    }

    /**
     * Set the value of documentUrl
     *
     * @param string $documentUrl
     */
    public function setDocumentUrl(string $documentUrl): void
    {
        $this->documentUrl = $documentUrl;
    }

    /**
     * Get the value of secret
     * @return bool
     */
    public function isSecret(): bool
    {
        return $this->secret;
    }

    /**
     * Set the value of secret
     * @param bool $secret
     */
    public function setSecret(bool $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * Add BaseTechnologiesRessources entity to collection (one to many).
     *
     * @param BaseTechnologiesRessources $ressource
     * @return Technologie
     */
    public function addRessources(BaseTechnologiesRessources $ressource): Technologie
    {
        $this->ressources[] = $ressource;

        return $this;
    }

    /**
     * Remove BaseTechnologiesRessources entity from collection (one to many).
     *
     * @param BaseTechnologiesRessources $ressource
     * @return Technologie
     */
    public function removeTechnologie(BaseTechnologiesRessources $ressource): Technologie
    {
        $this->ressources->removeElement($ressource);

        return $this;
    }

    /**
     * Get BaseTechnologiesRessources entity collection (one to many).
     *
     * @return Collection
     */
    public function getRessources()
    {
        return $this->ressources;
    }

    /**
     * Set CompetenceFamily entity (many to one).
     *
     * @param CompetenceFamily|null $competenceFamily
     * @return BaseTechnologie
     */
    public function setCompetenceFamily(CompetenceFamily $competenceFamily = null)
    {
        $this->competenceFamily = $competenceFamily;

        return $this;
    }

    /**
     * Get CompetenceFamily entity (many to one).
     *
     * @return CompetenceFamily
     */
    public function getCompetenceFamily()
    {
        return $this->competenceFamily;
    }

    /**
     * Add Personnage entity to collection.
     *
     * @param Personnage $personnage
     * @return Technologie
     */
    public function addPersonnage(Personnage $personnage)
    {
        $this->personnages[] = $personnage;

        return $this;
    }

    /**
     * Remove Personnage entity from collection.
     *
     * @param Personnage $personnage
     * @return Technologie
     */
    public function removePersonnage(Personnage $personnage)
    {
        $this->personnages->removeElement($personnage);

        return $this;
    }

    /**
     * Get Personnage entity collection.
     *
     * @return Collection
     */
    public function getPersonnages()
    {
        return $this->personnages;
    }

    public function __sleep()
    {
        return array('id', 'label', 'description');
    }
}