<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2015-12-22 15:53:50.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

/**
 * LarpManager\Entities\PersonnageSecondairesSkills
 *
 * @Entity()
 * @Table(name="personnage_secondaires_skills", indexes={@Index(name="fk_personnage_secondaire_skills_personnage_secondaire_idx", columns={"personnage_secondaire_id"}), @Index(name="fk_personnage_secondaire_skills_competence1_idx", columns={"competence_id"})})
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BasePersonnageSecondairesSkills", "extended":"PersonnageSecondairesSkills"})
 */
class BasePersonnageSecondairesSkills
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="PersonnageSecondaire", inversedBy="personnageSecondairesSkills")
     * @JoinColumn(name="personnage_secondaire_id", referencedColumnName="id", nullable=false)
     */
    protected $personnageSecondaire;

    /**
     * @ManyToOne(targetEntity="Competence", inversedBy="personnageSecondairesSkills")
     * @JoinColumn(name="competence_id", referencedColumnName="id", nullable=false)
     */
    protected $competence;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\PersonnageSecondairesSkills
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
     * Set PersonnageSecondaire entity (many to one).
     *
     * @param \LarpManager\Entities\PersonnageSecondaire $personnageSecondaire
     * @return \LarpManager\Entities\PersonnageSecondairesSkills
     */
    public function setPersonnageSecondaire(PersonnageSecondaire $personnageSecondaire = null)
    {
        $this->personnageSecondaire = $personnageSecondaire;

        return $this;
    }

    /**
     * Get PersonnageSecondaire entity (many to one).
     *
     * @return \LarpManager\Entities\PersonnageSecondaire
     */
    public function getPersonnageSecondaire()
    {
        return $this->personnageSecondaire;
    }

    /**
     * Set Competence entity (many to one).
     *
     * @param \LarpManager\Entities\Competence $competence
     * @return \LarpManager\Entities\PersonnageSecondairesSkills
     */
    public function setCompetence(Competence $competence = null)
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * Get Competence entity (many to one).
     *
     * @return \LarpManager\Entities\Competence
     */
    public function getCompetence()
    {
        return $this->competence;
    }

    public function __sleep()
    {
        return array('id', 'personnage_secondaire_id', 'competence_id');
    }
}