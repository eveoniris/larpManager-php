<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2015-12-23 11:01:58.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Entities\PersonnageReligion
 *
 * @Entity()
 * @Table(name="personnage_religion", indexes={@Index(name="fk_personnage_religion_religion1_idx", columns={"religion_id"}), @Index(name="fk_personnage_religion_religion_level1_idx", columns={"religion_level_id"})})
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BasePersonnageReligion", "extended":"PersonnageReligion"})
 */
class BasePersonnageReligion
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @OneToOne(targetEntity="Personnage", mappedBy="personnageReligion")
     */
    protected $personnage;

    /**
     * @ManyToOne(targetEntity="Religion", inversedBy="personnageReligions")
     * @JoinColumn(name="religion_id", referencedColumnName="id", nullable=false)
     */
    protected $religion;

    /**
     * @ManyToOne(targetEntity="ReligionLevel", inversedBy="personnageReligions")
     * @JoinColumn(name="religion_level_id", referencedColumnName="id", nullable=false)
     */
    protected $religionLevel;

    public function __construct()
    {
        $this->personnages = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\PersonnageReligion
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Personnage entity (one to one).
     *
     * @param \LarpManager\Entities\Personnage $personnage
     * @return \LarpManager\Entities\PersonnageReligion
     */
    public function setPersonnage(Personnage $personnage = null)
    {
        $personnage->setPersonnageReligion($this);
        $this->personnage = $personnage;

        return $this;
    }

    /**
     * Get Personnage entity (one to one).
     *
     * @return \LarpManager\Entities\Personnage
     */
    public function getPersonnage()
    {
        return $this->personnage;
    }

    /**
     * Set Religion entity (many to one).
     *
     * @param \LarpManager\Entities\Religion $religion
     * @return \LarpManager\Entities\PersonnageReligion
     */
    public function setReligion(Religion $religion = null)
    {
        $this->religion = $religion;

        return $this;
    }

    /**
     * Get Religion entity (many to one).
     *
     * @return \LarpManager\Entities\Religion
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * Set ReligionLevel entity (many to one).
     *
     * @param \LarpManager\Entities\ReligionLevel $religionLevel
     * @return \LarpManager\Entities\PersonnageReligion
     */
    public function setReligionLevel(ReligionLevel $religionLevel = null)
    {
        $this->religionLevel = $religionLevel;

        return $this;
    }

    /**
     * Get ReligionLevel entity (many to one).
     *
     * @return \LarpManager\Entities\ReligionLevel
     */
    public function getReligionLevel()
    {
        return $this->religionLevel;
    }

    public function __sleep()
    {
        return array('id', 'religion_id', 'religion_level_id');
    }
}