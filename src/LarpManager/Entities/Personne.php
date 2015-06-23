<?php

namespace LarpManager\Entities;

/**
 * @Entity
 * @Table(name="personne")
 */
 
class Personne
{
	/**
	 * @Id
	 * @Column (type="integer")
	 * @generatedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @OneToOne(targetEntity="Users", inversedBy="personne")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;
	
	/**
	 * @ManyToMany(targetEntity="Gn", inversedBy="personnes")
	 * @JoinTable(name="personnes_gn",
	 *      joinColumns={@JoinColumn(name="personne_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="gn_id", referencedColumnName="id")}
	 *      )
	 */
	private $gns;
	

	public function __construct() {
		$this->gns = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set user
     *
     * @param \LarpManager\Entities\Users $user
     *
     * @return Personne
     */
    public function setUser(\LarpManager\Entities\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \LarpManager\Entities\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add gn
     *
     * @param \LarpManager\Entities\Gn $gn
     *
     * @return Personne
     */
    public function addGn(\LarpManager\Entities\Gn $gn)
    {
        $this->gns[] = $gn;

        return $this;
    }

    /**
     * Remove gn
     *
     * @param \LarpManager\Entities\Gn $gn
     */
    public function removeGn(\LarpManager\Entities\Gn $gn)
    {
        $this->gns->removeElement($gn);
    }

    /**
     * Get gns
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGns()
    {
        return $this->gns;
    }
}
