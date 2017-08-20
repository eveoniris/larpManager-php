<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2017-08-20 10:39:35.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

/**
 * LarpManager\Entities\PersonnageIngredient
 *
 * @Entity()
 * @Table(name="personnage_ingredient", indexes={@Index(name="fk_personnage_ingredient_ingredient1_idx", columns={"ingredient_id"})})
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BasePersonnageIngredient", "extended":"PersonnageIngredient"})
 */
class BasePersonnageIngredient
{
    /**
     * @Id
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $nombre;

    /**
     * @ManyToOne(targetEntity="Personnage", inversedBy="personnageIngredients", cascade={"persist", "remove"})
     * @JoinColumn(name="personnage_id", referencedColumnName="id", nullable=false)
     */
    protected $personnage;

    /**
     * @ManyToOne(targetEntity="Ingredient", inversedBy="personnageIngredients")
     * @JoinColumn(name="ingredient_id", referencedColumnName="id", nullable=false)
     */
    protected $ingredient;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\PersonnageIngredient
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
     * Set the value of nombre.
     *
     * @param integer $nombre
     * @return \LarpManager\Entities\PersonnageIngredient
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of nombre.
     *
     * @return integer
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set Personnage entity (many to one).
     *
     * @param \LarpManager\Entities\Personnage $personnage
     * @return \LarpManager\Entities\PersonnageIngredient
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
     * Set Ingredient entity (many to one).
     *
     * @param \LarpManager\Entities\Ingredient $ingredient
     * @return \LarpManager\Entities\PersonnageIngredient
     */
    public function setIngredient(Ingredient $ingredient = null)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get Ingredient entity (many to one).
     *
     * @return \LarpManager\Entities\Ingredient
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    public function __sleep()
    {
        return array('id', 'personnage_id', 'ingredient_id', 'nombre');
    }
}