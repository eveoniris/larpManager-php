<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2016-09-04 10:51:10.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Entities\SecondaryGroupType
 *
 * @Entity()
 * @Table(name="secondary_group_type")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BaseSecondaryGroupType", "extended":"SecondaryGroupType"})
 */
class BaseSecondaryGroupType
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
     * @Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @OneToMany(targetEntity="SecondaryGroup", mappedBy="secondaryGroupType")
     * @JoinColumn(name="id", referencedColumnName="secondary_group_type_id", nullable=false)
     */
    protected $secondaryGroups;

    public function __construct()
    {
        $this->secondaryGroups = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\SecondaryGroupType
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
     * @return \LarpManager\Entities\SecondaryGroupType
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
     * @return \LarpManager\Entities\SecondaryGroupType
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
     * Add SecondaryGroup entity to collection (one to many).
     *
     * @param \LarpManager\Entities\SecondaryGroup $secondaryGroup
     * @return \LarpManager\Entities\SecondaryGroupType
     */
    public function addSecondaryGroup(SecondaryGroup $secondaryGroup)
    {
        $this->secondaryGroups[] = $secondaryGroup;

        return $this;
    }

    /**
     * Remove SecondaryGroup entity from collection (one to many).
     *
     * @param \LarpManager\Entities\SecondaryGroup $secondaryGroup
     * @return \LarpManager\Entities\SecondaryGroupType
     */
    public function removeSecondaryGroup(SecondaryGroup $secondaryGroup)
    {
        $this->secondaryGroups->removeElement($secondaryGroup);

        return $this;
    }

    /**
     * Get SecondaryGroup entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecondaryGroups()
    {
        return $this->secondaryGroups;
    }

    public function __sleep()
    {
        return array('id', 'label', 'description');
    }
}