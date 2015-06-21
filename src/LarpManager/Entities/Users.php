<?php

namespace LarpManager\Entities;

/**
 * @Entity
 * @Table(name="users")
 */
 
class Users
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
	private $email = '';

	/**
	 * @column (type="string")
	 */
	private $password;
	
	/**
	 * @column (type="string")
	 */
	private $salt = '';
	
	/**
	 * @column (type="string")
	 */
	private $roles = '';
	

	/**
	 * @column (type="string")
	 */
	private $name = '';
	

	/**
	 * @column (type="integer")
	 */
	private $time_created = 0;
	
	/**
	 * @Column (type="string")
	 */
	private $username;
	

	/**
	 * @column (type="boolean")
	 */
	private $isEnabled = true;
	

	/**
	 * @column (type="string")
	 */
	private $confirmationToken;
	

	/**
	 * @column (type="integer")
	 */
	private $timePasswordResetRequested;

	/**
	 * @ManyToMany(targetEntity="Gn", inversedBy="users")
	 * @JoinTable(name="users_gn",
     *      joinColumns={@JoinColumn(name="users_id", referencedColumnName="id")},
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
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param string $roles
     *
     * @return Users
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return string
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Users
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
     * Set timeCreated
     *
     * @param integer $timeCreated
     *
     * @return Users
     */
    public function setTimeCreated($timeCreated)
    {
        $this->time_created = $timeCreated;

        return $this;
    }

    /**
     * Get timeCreated
     *
     * @return integer
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     *
     * @return Users
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set confirmationToken
     *
     * @param string $confirmationToken
     *
     * @return Users
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Get confirmationToken
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Set timePasswordResetRequested
     *
     * @param integer $timePasswordResetRequested
     *
     * @return Users
     */
    public function setTimePasswordResetRequested($timePasswordResetRequested)
    {
        $this->timePasswordResetRequested = $timePasswordResetRequested;

        return $this;
    }

    /**
     * Get timePasswordResetRequested
     *
     * @return integer
     */
    public function getTimePasswordResetRequested()
    {
        return $this->timePasswordResetRequested;
    }

    /**
     * Add gnCollection
     *
     * @param \LarpManager\Entities\Gn $gnCollection
     *
     * @return Users
     */
    public function addGnCollection(\LarpManager\Entities\Gn $gnCollection)
    {
        $this->gnCollection[] = $gnCollection;

        return $this;
    }

    /**
     * Remove gnCollection
     *
     * @param \LarpManager\Entities\Gn $gnCollection
     */
    public function removeGnCollection(\LarpManager\Entities\Gn $gnCollection)
    {
        $this->gnCollection->removeElement($gnCollection);
    }

    /**
     * Get gnCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGnCollection()
    {
        return $this->gnCollection;
    }

    /**
     * Add gn
     *
     * @param \LarpManager\Entities\Gn $gn
     *
     * @return Users
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
