<?php

namespace LarpManager\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

/**
* LarpManager\Entities\BaseTechnologiesRessources
*
 * @Entity()
* @Table(name="technologies_ressources")
* @InheritanceType("SINGLE_TABLE")
* @DiscriminatorColumn(name="discr", type="string")
* @DiscriminatorMap({"base":"BaseTechnologiesRessources", "extended":"TechnologiesRessources"})
*/

class BaseTechnologiesRessources
{
    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\GreaterThan(0)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Column (type="integer")
     */
    protected $quantite;

    /**
     * @ManyToOne(targetEntity="Technologie", inversedBy="technologieRessource", cascade={"persist"})
     * @JoinColumn(name="technologie_id", referencedColumnName="id")
     */
    protected $technologie;

    /**
     * @ManyToOne(targetEntity="Ressource", inversedBy="technologieRessource", cascade={"persist"})
     * @JoinColumn(name="ressource_id", referencedColumnName="id")
     */
    protected $ressource;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @return Technologie
     */
    public function getTechnologie(): Technologie
    {
        return $this->technologie;
    }

    /**
     * @param Technologie $technologie
     */
    public function setTechnologie(Technologie $technologie): void
    {
        $this->technologie = $technologie;
    }

    /**
     * @return Ressource
     */
    public function getRessource()
    {
        return $this->ressource;
    }

    /**
     * @param Ressource $ressource
     */
    public function setRessource(Ressource $ressource): void
    {
        $this->ressource = $ressource;
    }


}