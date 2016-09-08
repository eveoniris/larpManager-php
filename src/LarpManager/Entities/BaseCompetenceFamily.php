<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2016-09-07 17:41:29.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Entities\CompetenceFamily
 *
 * @Entity()
 * @Table(name="competence_family")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BaseCompetenceFamily", "extended":"CompetenceFamily"})
 */
class BaseCompetenceFamily
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string", length=45)
     */
    protected $label;

    /**
     * @Column(type="string", length=450, nullable=true)
     */
    protected $description;

    /**
     * @OneToMany(targetEntity="Competence", mappedBy="competenceFamily", cascade={"persist"})
     * @JoinColumn(name="id", referencedColumnName="competence_family_id", nullable=false)
     */
    protected $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\CompetenceFamily
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
     * Set the value of label.
     *
     * @param string $label
     * @return \LarpManager\Entities\CompetenceFamily
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of description.
     *
     * @param string $description
     * @return \LarpManager\Entities\CompetenceFamily
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add Competence entity to collection (one to many).
     *
     * @param \LarpManager\Entities\Competence $competence
     * @return \LarpManager\Entities\CompetenceFamily
     */
    public function addCompetence(Competence $competence)
    {
        $this->competences[] = $competence;

        return $this;
    }

    /**
     * Remove Competence entity from collection (one to many).
     *
     * @param \LarpManager\Entities\Competence $competence
     * @return \LarpManager\Entities\CompetenceFamily
     */
    public function removeCompetence(Competence $competence)
    {
        $this->competences->removeElement($competence);

        return $this;
    }

    /**
     * Get Competence entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompetences()
    {
        return $this->competences;
    }

    public function __sleep()
    {
        return array('id', 'label', 'description');
    }
}