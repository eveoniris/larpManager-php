<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2015-08-18 10:24:14.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Entities\Personnage
 *
 * @Entity()
 * @Table(name="personnage", indexes={@Index(name="fk_personnage_groupe1_idx", columns={"groupe_id"}), @Index(name="fk_personnage_users1_idx", columns={"user_id"}), @Index(name="fk_personnage_archetype1_idx", columns={"classe_id"}), @Index(name="fk_personnage_age1_idx", columns={"age_id"}), @Index(name="fk_personnage_genre1_idx", columns={"genre_id"})})
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BasePersonnage", "extended":"Personnage"})
 */
class BasePersonnage
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string", length=100)
     */
    protected $nom;

    /**
     * @Column(type="string", length=100, nullable=true)
     */
    protected $surnom;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $intrigue;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $renomme;

    /**
     * @Column(type="string", length=100, nullable=true)
     */
    protected $photo;

    /**
     * @ManyToOne(targetEntity="Groupe", inversedBy="personnages")
     * @JoinColumn(name="groupe_id", referencedColumnName="id")
     */
    protected $groupe;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="personnages")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="Classe", inversedBy="personnages")
     * @JoinColumn(name="classe_id", referencedColumnName="id")
     */
    protected $classe;

    /**
     * @ManyToOne(targetEntity="Age", inversedBy="personnages")
     * @JoinColumn(name="age_id", referencedColumnName="id")
     */
    protected $age;

    /**
     * @ManyToOne(targetEntity="Genre", inversedBy="personnages")
     * @JoinColumn(name="genre_id", referencedColumnName="id")
     */
    protected $genre;

    /**
     * @ManyToMany(targetEntity="Langue", inversedBy="personnages")
     * @JoinTable(name="personnage_langue",
     *     joinColumns={@JoinColumn(name="personnage_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="langue_id", referencedColumnName="id")}
     * )
     */
    protected $langues;

    public function __construct()
    {
        $this->langues = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\Personnage
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
     * Set the value of nom.
     *
     * @param string $nom
     * @return \LarpManager\Entities\Personnage
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of surnom.
     *
     * @param string $surnom
     * @return \LarpManager\Entities\Personnage
     */
    public function setSurnom($surnom)
    {
        $this->surnom = $surnom;

        return $this;
    }

    /**
     * Get the value of surnom.
     *
     * @return string
     */
    public function getSurnom()
    {
        return $this->surnom;
    }

    /**
     * Set the value of intrigue.
     *
     * @param boolean $intrigue
     * @return \LarpManager\Entities\Personnage
     */
    public function setIntrigue($intrigue)
    {
        $this->intrigue = $intrigue;

        return $this;
    }

    /**
     * Get the value of intrigue.
     *
     * @return boolean
     */
    public function getIntrigue()
    {
        return $this->intrigue;
    }

    /**
     * Set the value of renomme.
     *
     * @param integer $renomme
     * @return \LarpManager\Entities\Personnage
     */
    public function setRenomme($renomme)
    {
        $this->renomme = $renomme;

        return $this;
    }

    /**
     * Get the value of renomme.
     *
     * @return integer
     */
    public function getRenomme()
    {
        return $this->renomme;
    }

    /**
     * Set the value of photo.
     *
     * @param string $photo
     * @return \LarpManager\Entities\Personnage
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of photo.
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set Groupe entity (many to one).
     *
     * @param \LarpManager\Entities\Groupe $groupe
     * @return \LarpManager\Entities\Personnage
     */
    public function setGroupe(Groupe $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get Groupe entity (many to one).
     *
     * @return \LarpManager\Entities\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set User entity (many to one).
     *
     * @param \LarpManager\Entities\User $user
     * @return \LarpManager\Entities\Personnage
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User entity (many to one).
     *
     * @return \LarpManager\Entities\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set Classe entity (many to one).
     *
     * @param \LarpManager\Entities\Classe $classe
     * @return \LarpManager\Entities\Personnage
     */
    public function setClasse(Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get Classe entity (many to one).
     *
     * @return \LarpManager\Entities\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set Age entity (many to one).
     *
     * @param \LarpManager\Entities\Age $age
     * @return \LarpManager\Entities\Personnage
     */
    public function setAge(Age $age = null)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get Age entity (many to one).
     *
     * @return \LarpManager\Entities\Age
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set Genre entity (many to one).
     *
     * @param \LarpManager\Entities\Genre $genre
     * @return \LarpManager\Entities\Personnage
     */
    public function setGenre(Genre $genre = null)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get Genre entity (many to one).
     *
     * @return \LarpManager\Entities\Genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Add Langue entity to collection.
     *
     * @param \LarpManager\Entities\Langue $langue
     * @return \LarpManager\Entities\Personnage
     */
    public function addLangue(Langue $langue)
    {
        $langue->addPersonnage($this);
        $this->langues[] = $langue;

        return $this;
    }

    /**
     * Remove Langue entity from collection.
     *
     * @param \LarpManager\Entities\Langue $langue
     * @return \LarpManager\Entities\Personnage
     */
    public function removeLangue(Langue $langue)
    {
        $langue->removePersonnage($this);
        $this->langues->removeElement($langue);

        return $this;
    }

    /**
     * Get Langue entity collection.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLangues()
    {
        return $this->langues;
    }

    public function __sleep()
    {
        return array('id', 'nom', 'surnom', 'intrigue', 'groupe_id', 'user_id', 'classe_id', 'age_id', 'genre_id', 'renomme', 'photo');
    }
}