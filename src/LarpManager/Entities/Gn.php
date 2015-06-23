<?php

namespace LarpManager\Entities;

/**
 * @Entity
 * @Table(name="gn")
 */

class Gn
{
	/**
	 * @Id
	 * @Column (type="integer")
	 * @generatedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @column (type="string")
	 */
	private $name;
	
    /**
     * @ManyToMany(targetEntity="Personne", mappedBy="gns")
     **/
	private $personnes;
	
	public function __construct() {
		$this->personnes = new \Doctrine\Common\Collections\ArrayCollection();
	}


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Gn
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add personne
     *
     * @param \LarpManager\Entities\Personne $personne
     *
     * @return Gn
     */
    public function addPersonne(\LarpManager\Entities\Personne $personne)
    {
        $this->personnes[] = $personne;

        return $this;
    }

    /**
     * Remove personne
     *
     * @param \LarpManager\Entities\Personne $personne
     */
    public function removePersonne(\LarpManager\Entities\Personne $personne)
    {
        $this->personnes->removeElement($personne);
    }

    /**
     * Get personnes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonnes()
    {
        return $this->personnes;
    }
}
