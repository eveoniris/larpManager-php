<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2017-08-20 10:39:36.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

/**
 * LarpManager\Entities\PersonnageRessource
 *
 * @Entity()
 * @Table(name="personnage_ressource", indexes={@Index(name="fk_personnage_ressource_ressource1_idx", columns={"ressource_id"})})
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BasePersonnageRessource", "extended":"PersonnageRessource"})
 */
class BasePersonnageRessource
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
     * @ManyToOne(targetEntity="Personnage", inversedBy="personnageRessources", cascade={"persist", "remove"})
     * @JoinColumn(name="personnage_id", referencedColumnName="id", nullable=false)
     */
    protected $personnage;

    /**
     * @ManyToOne(targetEntity="Ressource", inversedBy="personnageRessources")
     * @JoinColumn(name="ressource_id", referencedColumnName="id", nullable=false)
     */
    protected $ressource;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\PersonnageRessource
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
     * @return \LarpManager\Entities\PersonnageRessource
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
     * @return \LarpManager\Entities\PersonnageRessource
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
     * Set Ressource entity (many to one).
     *
     * @param \LarpManager\Entities\Ressource $ressource
     * @return \LarpManager\Entities\PersonnageRessource
     */
    public function setRessource(Ressource $ressource = null)
    {
        $this->ressource = $ressource;

        return $this;
    }

    /**
     * Get Ressource entity (many to one).
     *
     * @return \LarpManager\Entities\Ressource
     */
    public function getRessource()
    {
        return $this->ressource;
    }

    public function __sleep()
    {
        return array('id', 'personnage_id', 'ressource_id', 'nombre');
    }
}