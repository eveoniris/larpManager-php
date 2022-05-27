<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2017-02-09 11:20:21.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * LarpManager\Entities\Ressource
 *
 * @Entity()
 * @Table(name="ressource", indexes={@Index(name="fk_ressource_rarete1_idx", columns={"rarete_id"})})
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BaseRessource", "extended":"Ressource"})
 */
class BaseRessource
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @Column(type="string", length=100)
     */
    protected string $label;

    /**
     * @OneToMany(targetEntity="GroupeHasRessource", mappedBy="ressource")
     * @JoinColumn(name="id", referencedColumnName="ressource_id", nullable=false)
     */
    protected ArrayCollection $groupeHasRessources;

    /**
     * @OneToMany(targetEntity="PersonnageRessource", mappedBy="ressource")
     * @JoinColumn(name="id", referencedColumnName="ressource_id", nullable=false)
     */
    protected ArrayCollection $personnageRessources;

    /**
     * @OneToMany(targetEntity="TechnologiesRessources", mappedBy="ressource")
     * @JoinColumn(name="id", referencedColumnName="ressource_id", nullable=false)
     */
    protected ArrayCollection $technologiesRessources;

    /**
     * @ManyToOne(targetEntity="Rarete", inversedBy="ressources")
     * @JoinColumn(name="rarete_id", referencedColumnName="id", nullable=false)
     */
    protected Rarete $rarete;

    public function __construct()
    {
        $this->groupeHasRessources = new ArrayCollection();
        $this->personnageRessources = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return Ressource
     */
    public function setId(int $id): Ressource
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
     * @return Ressource
     */
    public function setLabel(string $label): Ressource
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Add GroupeHasRessource entity to collection (one to many).
     *
     * @param GroupeHasRessource $groupeHasRessource
     * @return Ressource
     */
    public function addGroupeHasRessource(GroupeHasRessource $groupeHasRessource): Ressource
    {
        $this->groupeHasRessources[] = $groupeHasRessource;

        return $this;
    }

    /**
     * Remove GroupeHasRessource entity from collection (one to many).
     *
     * @param GroupeHasRessource $groupeHasRessource
     * @return Ressource
     */
    public function removeGroupeHasRessource(GroupeHasRessource $groupeHasRessource): Ressource
    {
        $this->groupeHasRessources->removeElement($groupeHasRessource);

        return $this;
    }

    /**
     * Get GroupeHasRessource entity collection (one to many).
     *
     * @return ArrayCollection
     */
    public function getGroupeHasRessources(): ArrayCollection
    {
        return $this->groupeHasRessources;
    }

    /**
     * Add PersonnageRessource entity to collection (one to many).
     *
     * @param PersonnageRessource $personnageRessource
     * @return Ressource
     */
    public function addPersonnageRessource(PersonnageRessource $personnageRessource): Ressource
    {
        $this->personnageRessources[] = $personnageRessource;

        return $this;
    }

    /**
     * Remove PersonnageRessource entity from collection (one to many).
     *
     * @param PersonnageRessource $personnageRessource
     * @return Ressource
     */
    public function removePersonnageRessource(PersonnageRessource $personnageRessource): Ressource
    {
        $this->personnageRessources->removeElement($personnageRessource);

        return $this;
    }

    /**
     * Get PersonnageRessource entity collection (one to many).
     *
     * @return ArrayCollection
     */
    public function getPersonnageRessources(): ArrayCollection
    {
        return $this->personnageRessources;
    }

    /**
     * Add TechnologiesRessources entity to collection (one to many).
     *
     * @param TechnologiesRessources $technologieRessource
     * @return Ressource
     */
    public function addTechnologieRessource(TechnologiesRessources $technologieRessource): Ressource
    {
        $this->technologiesRessources[] = $technologieRessource;

        return $this;
    }

    /**
     * Remove TechnologiesRessources entity from collection (one to many).
     *
     * @param TechnologiesRessources $technologieRessource
     * @return Ressource
     */
    public function removeTechnologieRessource(TechnologiesRessources $technologieRessource): Ressource
    {
        $this->technologiesRessources->removeElement($technologieRessource);

        return $this;
    }

    /**
     * Get TechnologiesRessources entity collection (one to many).
     *
     * @return ArrayCollection
     */
    public function getTechnologiesRessources(): ArrayCollection
    {
        return $this->technologiesRessources;
    }

    /**
     * Set Rarete entity (many to one).
     *
     * @param Rarete|null $rarete
     * @return Ressource
     */
    public function setRarete(Rarete $rarete = null): Ressource
    {
        $this->rarete = $rarete;

        return $this;
    }

    /**
     * Get Rarete entity (many to one).
     *
     * @return Rarete
     */
    public function getRarete(): Rarete
    {
        return $this->rarete;
    }

    public function __sleep()
    {
        return array('id', 'label', 'rarete_id');
    }
}