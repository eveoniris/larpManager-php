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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;


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
     * @OneToMany(targetEntity="Technologie", mappedBy="competenceFamily", cascade={"persist"})
     * @JoinColumn (name="id", referencedColumnName="competence_family_id", nullable=false)
     */

    protected $technologies;

    /**
     * @OneToMany(targetEntity="Competence", mappedBy="competenceFamily", cascade={"persist"})
     * @JoinColumn(name="id", referencedColumnName="competence_family_id", nullable=false)
     */
    protected $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->technologies = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return CompetenceFamily
     */
    public function setId(int $id): CompetenceFamily
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
     * @return CompetenceFamily
     */
    public function setLabel(string $label): CompetenceFamily
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
     * Set the value of description.
     *
     * @param string $description
     * @return CompetenceFamily
     */
    public function setDescription(string $description): CompetenceFamily
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Add Competence entity to collection (one to many).
     *
     * @param Competence $competence
     * @return CompetenceFamily
     */
    public function addCompetence(Competence $competence): CompetenceFamily
    {
        $this->competences[] = $competence;

        return $this;
    }

    /**
     * Remove Competence entity from collection (one to many).
     *
     * @param Competence $competence
     * @return CompetenceFamily
     */
    public function removeCompetence(Competence $competence): CompetenceFamily
    {
        $this->competences->removeElement($competence);

        return $this;
    }

    /**
     * Get Competence entity collection (one to many).
     *
     * @return Collection
     */
    public function getCompetences()
    {
        return $this->competences;
    }

    /**
     * Add Technologie entity to collection (one to many).
     *
     * @param Technologie $technologie
     * @return CompetenceFamily
     */
    public function addTechnologie(Technologie $technologie): CompetenceFamily
    {
        $this->technologies[] = $technologie;

        return $this;
    }

    /**
     * Remove Technologie entity from collection (one to many).
     *
     * @param Technologie $technologie
     * @return CompetenceFamily
     */
    public function removeTechnologie(Technologie $technologie): CompetenceFamily
    {
        $this->technologies->removeElement($technologie);

        return $this;
    }

    /**
     * Get Technologie entity collection (one to many).
     *
     * @return Collection
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    public function __sleep()
    {
        return array('id', 'label', 'description');
    }
}