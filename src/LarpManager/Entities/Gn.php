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
     * @ManyToMany(targetEntity="Users", mappedBy="gnCollection")
     **/
	private $users;
	
	public function __construct() {
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add userCollection
     *
     * @param \LarpManager\Entities\Users $userCollection
     *
     * @return Gn
     */
    public function addUserCollection(\LarpManager\Entities\Users $userCollection)
    {
        $this->userCollection[] = $userCollection;

        return $this;
    }

    /**
     * Remove userCollection
     *
     * @param \LarpManager\Entities\Users $userCollection
     */
    public function removeUserCollection(\LarpManager\Entities\Users $userCollection)
    {
        $this->userCollection->removeElement($userCollection);
    }

    /**
     * Get userCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserCollection()
    {
        return $this->userCollection;
    }

    /**
     * Add user
     *
     * @param \LarpManager\Entities\Users $user
     *
     * @return Gn
     */
    public function addUser(\LarpManager\Entities\Users $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \LarpManager\Entities\Users $user
     */
    public function removeUser(\LarpManager\Entities\Users $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
