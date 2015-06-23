<?php

namespace LarpManager\Entities;

/**
 * @Entity
 * @Table(name="personnage")
 */
 
class Personnage
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
	private $nom;
	
	/**
	 * @column (type="integer")
	 */
	private $age;
	
	/**
	 * @OneToOne(targetEntity="Personne")
	 * @JoinColumn(name="personne_id", referencedColumnName="id")
	 */
	private $personne;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Personnage
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Personnage
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set personne
     *
     * @param \LarpManager\Entities\Personne $personne
     *
     * @return Personnage
     */
    public function setPersonne(\LarpManager\Entities\Personne $personne = null)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return \LarpManager\Entities\Personne
     */
    public function getPersonne()
    {
        return $this->personne;
    }
}
