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
	 * @Column (type="string")
	 */
	private $username;
	
	/**
	 * @column (type="string")
	 */
	private $password;
	
	/**
	 * @column (type="string")
	 */
	private $roles;
	
	/**
	 * @column (type="string")
	 */
	private $email;
	
	/**
	 * @column (type="string")
	 */
	private $name;
	
}
