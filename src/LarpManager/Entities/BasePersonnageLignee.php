<?php

/**
 * Created by Kevin F.
 */

namespace LarpManager\Entities;

/**
 * LarpManager\Entities\PersonnageLignee
 *
 * @Entity()
 * @Table(name="personnages_lignee")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BasePersonnageLignee", "extended":"PersonnageLignee"})
 */
class BasePersonnageLignee
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Personnage", inversedBy="PersonnageLignee")
     * @JoinColumn(name="personnage_id", referencedColumnName="id", nullable=false)
     */
    protected $personnage;

    /**
     * @ManyToOne(targetEntity="Personnage", inversedBy="PersonnageLignee")
     * @JoinColumn(name="parent1_id", referencedColumnName="id", nullable=false)
     */
    protected $parent1;

    /**
     * @ManyToOne(targetEntity="Personnage", inversedBy="PersonnageLignee")
     * @JoinColumn(name="parent2_id", referencedColumnName="id", nullable=false)
     */
    protected $parent2;

    /**
     * @ManyToOne(targetEntity="Lignee", inversedBy="PersonnageLignee")
     * @JoinColumn(name="lignee_id", referencedColumnName="id", nullable=false)
     */
    protected $lignee;


    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\PersonnageLignee
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
     * Set Personnage entity (many to one).
     *
     * @param \LarpManager\Entities\Personnage $personnage
     * @return \LarpManager\Entities\PersonnageLignee
     */
    public function setPersonnage(Personnage $personnage = null)
    {
        $this->personnage = $personnage;

        return $this;
    }

    /**
     * Get Personnage entity (many to one).
     *
     * @return \LarpManager\Entities\Personnage
     */
    public function getPersonnage()
    {
        return $this->personnage;
    }

    /**
     * Set Parent1 entity (many to one).
     *
     * @param \LarpManager\Entities\Personnage $personnage
     * @return \LarpManager\Entities\PersonnageLignee
     */
    public function setParent1(Personnage $parent1 = null)
    {
        $this->parent1 = $parent1;

        return $this;
    }

    /**
     * Get Parent1 entity (many to one).
     *
     * @return \LarpManager\Entities\Personnage
     */
    public function getParent1()
    {
        return $this->parent1;
    }

    /**
     * Set Parent2 entity (many to one).
     *
     * @param \LarpManager\Entities\Personnage $personnage
     * @return \LarpManager\Entities\PersonnageLignee
     */
    public function setParent2(Personnage $parent2 = null)
    {
        $this->parent2 = $parent2;

        return $this;
    }

    /**
     * Get Parent2 entity (many to one).
     *
     * @return \LarpManager\Entities\Personnage
     */
    public function getParent2()
    {
        return $this->parent2;
    }

    /**
     * Set Lignee entity (many to one).
     *
     * @param \LarpManager\Entities\Lignee $lignee
     * @return \LarpManager\Entities\PersonnageLignee
     */
    public function setLignee(Personnage $lignee = null)
    {
        $this->lignee = $lignee;

        return $this;
    }

    /**
     * Get Lignee entity (many to one).
     *
     * @return \LarpManager\Entities\Personnage
     */
    public function getLignee()
    {
        return $this->lignee;
    }

    public function __sleep()
    {
        return array('id', 'personnage_id', 'parent1_id', 'parent2_id', 'ligne_id');
    }
}