<?php

namespace LarpManager\Services\Manager;

use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\DBAL\Connection;
use LarpManager\Entities\User;

class UserManager implements UserProviderInterface
{
	/** @var Connection */
	protected $conn;
	
	/** @var \Silex\Application */
	protected $app;
	
	/** @var EventDispatcher */
	protected $dispatcher;
	
	/** @var string */
	protected $userClass = '\LarpManager\Entities\User';
	
	/** @var string */
	protected $userTableName = 'user';
	
	/** @var User[] */
	protected $identityMap = array();
	
	/** @var Callable */
	protected $passwordStrengthValidator;
	
	// UserProviderInterface
	
	public function __construct(Connection $conn, Application $app)
	{
		$this->conn = $conn;
		$this->app = $app;
		$this->dispatcher = $app['dispatcher'];
	}
	
	public function loadUserByUsername($username)
	{
		if (strpos($username, '@') !== false) {
			$user = $this->findOneByEmail($username);
			if (!$user) {
				throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $username));
			}
		
			return $user;
		}
		
		$user = $this->findOneByUsername($username);
		if (!$user) {
			throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
		}
		
		return $user;
	}
	
	public function refreshUser(UserInterface $user)
	{
		if (!$this->supportsClass(get_class($user))) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
		}
		
		return $this->getUser($user->getId());
	}
	
	public function supportsClass($class)
	{
		return ($class === 'LarpManager\Entities\User') || is_subclass_of($class, 'LarpManager\Entities\User');
	}
	
	// End of UserProviderInterface
	
	
	/**
	 * Test whether a plain text password is strong enough.
	 *
	 * Note that controllers must call this explicitly,
	 * it's NOT called automatically when setting a password or validating a user.
	 *
	 * This is just a proxy for the Callable set by setPasswordStrengthValidator().
	 * If no password strength validator Callable is explicitly set,
	 * by default the only requirement is that the password not be empty.
	 *
	 * @param User $user
	 * @param $password
	 * @return string|null An error message if validation fails, null if validation succeeds.
	 */
	public function validatePasswordStrength(User $user, $password)
	{
		return call_user_func($this->getPasswordStrengthValidator(), $user, $password);
	}
	
	/**
	 * @return callable
	 */
	public function getPasswordStrengthValidator()
	{
		if (!is_callable($this->passwordStrengthValidator)) {
			return function(User $user, $password) {
				if (empty($password)) {
					return 'Password cannot be empty.';
				}
	
				return null;
			};
		}
	
		return $this->passwordStrengthValidator;
	}
	
	/**
	 * Specify a callable to test whether a given password is strong enough.
	 *
	 * Must take a User instance and a password string as arguments,
	 * and return an error string on failure or null on success.
	 *
	 * @param Callable $callable
	 * @throws \InvalidArgumentException
	 */
	public function setPasswordStrengthValidator($callable)
	{
		if (!is_callable($callable)) {
			throw new \InvalidArgumentException('Password strength validator must be Callable.');
		}
	
		$this->passwordStrengthValidator = $callable;
	}
	
	/**
	 * Test whether a given plain text password matches a given User's encoded password.
	 *
	 * @param User $user
	 * @param string $password
	 * @return bool
	 */
	public function checkUserPassword(User $user, $password)
	{
		return $user->getPassword() === $this->encodeUserPassword($user, $password);
	}
	
	/**
	 * Get a User instance for the currently logged in User, if any.
	 *
	 * @return UserInterface|null
	 */
	public function getCurrentUser()
	{
		if ($this->isLoggedIn()) {
			return $this->app['security']->getToken()->getUser();
		}
	
		return null;
	}
	
	/**
	 * Create User
	 * @param unknown $email
	 * @param unknown $plainPassword
	 * @param unknown $name
	 * @param unknown $roles
	 */
	public function createUser($email, $plainPassword, $name = null, $roles = array())
	{
		$userClass = $this->getUserClass();
		
		$user = new $userClass($email);
		
		if (!empty($plainPassword)) {
			$this->setUserPassword($user, $plainPassword);
		}
		
		if ($name !== null) {
			$user->setUsername($name);
		}
		
		if (!empty($roles)) {
			$user->setRoles($roles);
		}

		return $user;
	}
	
	/**
	 * Encode a plain text password and set it on the given User object.
	 *
	 * @param User $user
	 * @param string $password A plain text password.
	 */
	public function setUserPassword(User $user, $password)
	{
		$user->setPassword($this->encodeUserPassword($user, $password));
	}
	
	/**
	 * Encode a plain text password for a given user. Hashes the password with the given user's salt.
	 *
	 * @param User $user
	 * @param string $password A plain text password.
	 * @return string An encoded password.
	 */
	public function encodeUserPassword(User $user, $password)
	{
		$encoder = $this->getEncoder($user);
		return $encoder->encodePassword($password, $user->getSalt());
	}
	
	/**
	 * Get the password encoder to use for the given user object.
	 *
	 * @param UserInterface $user
	 * @return PasswordEncoderInterface
	 */
	protected function getEncoder(UserInterface $user)
	{
		return $this->app['security.encoder_factory']->getEncoder($user);
	}
	
	/**
	 * @param string $userClass The class to use for the user model. Must extend SimpleUser\User.
	 */
	public function setUserClass($userClass)
	{
		$this->userClass = $userClass;
	}
	
	/**
	 * @return string
	 */
	public function getUserClass()
	{
		return $this->userClass;
	}
	
	public function insert(User $user)
	{
		$user->setCreationDate(new \Datetime('NOW'));
		$user->setIsEnabled(false);
		
		$this->app['orm.em']->persist($user);
		$this->app['orm.em']->flush();
				
		$this->identityMap[$user->getId()] = $user;
	}
	
	/**
	 * Update data in the database for an existing user.
	 *
	 * @param User $user
	 */
	public function update(User $user)
	{
		$this->app['orm.em']->persist($user);
		$this->app['orm.em']->flush();
	}
	
	/**
	 * Test whether the current user is authenticated.
	 *
	 * @return boolean
	 */
	function isLoggedIn()
	{
		$token = $this->app['security']->getToken();
		if (null === $token) {
			return false;
		}
	
		return $this->app['security']->isGranted('IS_AUTHENTICATED_REMEMBERED');
	}
	
	/**
	 * Get a User instance by its ID.
	 *
	 * @param int $id
	 * @return User|null The User, or null if there is no User with that ID.
	 */
	public function getUser($id)
	{
		return $this->findOneById($id);
	}
		
	/**
	 * Find a user by id
	 * @param int $id
	 * @return User|null The User, or null if there is no User with that ID.
	 */
	public function findOneById($id)
	{
		if ( array_key_exists($id, $this->identityMap) ) {
			return $this->identityMap[$id];
		}
		
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\User');
		$user = $repo->findOneById($id);
		
		if ( empty($user))
		{
			return null;
		}
		
		if (array_key_exists($user->getId(), $this->identityMap))
		{
			$user = $this->identityMap[$user->getId()];
		}
		else
		{
			$this->identityMap[$user->getId()] = $user;
		}
		
		return $user;
	}
	
	/**
	 * 
	 * @param unknown $email
	 */
	public function findOneByEmail($email)
	{
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\User');
		$user = $repo->findOneByEmail($email);
		
		if ( empty($user) )
		{
			return null;
		}
		
		if (array_key_exists($user->getId(), $this->identityMap))
		{
			$user = $this->identityMap[$user->getId()];
		}
		else
		{
			$this->identityMap[$user->getId()] = $user;
		}
		
		return $user;
	}
	
	/**
	 * 
	 * @param unknown $username
	 */
	public function findOneByUsername($username)
	{
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\User');
		$user = $repo->findOneByUsername($username);
		
		if ( empty($user) )
		{
			return null;
		}
		
		if (array_key_exists($user->getId(), $this->identityMap))
		{
			$user = $this->identityMap[$user->getId()];
		}
		else
		{
			$this->identityMap[$user->getId()] = $user;
		}
		
		return $user;
	}
	
	public function findByEmail($email)
	{
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\User');
		$users = $repo->findByEmail($email);
		
		return $users;
	}
	
	public function findByUsername($username)
	{
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\User');
		$users = $repo->findByUsername($username);
		
		return $users;
	}
	
	public function findAll()
	{
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\User');
		$users = $repo->findAll();
		return $users;
	}
	
	public function findCount()
	{
		return  $this->app['orm.em']
        	->createQuery('SELECT COUNT(user) FROM \LarpManager\Entities\User user')
        	->getSingleScalarResult();
	}
	
	/**
	 * Reconstitute a User object from stored data.
	 *
	 * @param array $data
	 * @return User
	 * @throws \RuntimeException if database schema is out of date.
	 */
	protected function hydrateUser(array $data)
	{
		// Test for new columns added in v2.0.
		// If they're missing, throw an exception and explain that migration is needed.
		foreach (array('username', 'isEnabled', 'confirmationToken', 'timePasswordResetRequested') as $col) {
			if (!array_key_exists($col, $data)) {
				throw new \RuntimeException('Internal error: database schema appears out of date. See https://github.com/jasongrimes/silex-simpleuser/blob/master/sql/MIGRATION.md');
			}
		}
	
		$userClass = $this->getUserClass();
	
		/** @var User $user */
		$user = new $userClass($data['email']);
	
		$user->setId($data['id']);
		$user->setPassword($data['password']);
		$user->setSalt($data['salt']);
		$user->setName($data['name']);
		if ($roles = explode(',', $data['rights'])) {
			$user->setRoles($roles);
		}
		$user->setCreationDate($data['creation_date']);
		$user->setUsername($data['username']);
		$user->setEnabled($data['isEnabled']);
		$user->setConfirmationToken($data['confirmationToken']);
		$user->setTimePasswordResetRequested($data['timePasswordResetRequested']);
	
		if (!empty($data['customFields'])) {
			$user->setCustomFields($data['customFields']);
		}
	
		return $user;
	}
	
	/**
	 * Validate a user object.
	 *
	 * Invokes User::validate(),
	 * and additionally tests that the User's email address and username (if set) are unique across all users.'.
	 *
	 * @param User $user
	 * @return array An array of error messages, or an empty array if the User is valid.
	 */
	public function validate(User $user)
	{
		$errors = $user->validate();
	
		// Ensure email address is unique.
		$duplicates = $this->findByEmail($user->getEmail());
		if (!empty($duplicates)) {
			foreach ($duplicates as $dup) {
				if ($user->getId() && $dup->getId() == $user->getId()) {
					continue;
				}
				$errors['email'] = 'Adresse mail indisponible ou invalide, veuillez en choisir une autre.';
			}
		}
	
		// Ensure username is unique.
		$duplicates = $this->findByUsername($user->getRealUsername());
		if (!empty($duplicates)) {
			foreach ($duplicates as $dup) {
				if ($user->getId() && $dup->getId() == $user->getId()) {
					continue;
				}
				$errors['username'] = 'Pseudo invalide, veuillez en choisir un autre.';
			}
		}
	
		// If username is required, ensure it is set.
		if ( !$user->getRealUsername()) {
			$errors['username'] = 'Pseudo requis.';
		}
	
		return $errors;
	}
	
	/**
	 * Clear User instances from the identity map, so that they can be read again from the database.
	 *
	 * Call with no arguments to clear the entire identity map.
	 * Pass a single user to remove just that user from the identity map.
	 *
	 * @param mixed $user Either a User instance, an integer user ID, or null.
	 */
	public function clearIdentityMap($user = null)
	{
		if ($user === null) {
			$this->identityMap = array();
		} else if ($user instanceof User && array_key_exists($user->getId(), $this->identityMap)) {
			unset($this->identityMap[$user->getId()]);
		} else if (is_numeric($user) && array_key_exists($user, $this->identityMap)) {
			unset($this->identityMap[$user]);
		}
	}
	

	/**
	 * Log in as the given user.
	 *
	 * Sets the security token for the current request so it will be logged in as the given user.
	 *
	 * @param User $user
	 */
	public function loginAsUser(User $user)
	{
		if (null !== ($current_token = $this->app['security']->getToken())) {
			$providerKey = method_exists($current_token, 'getProviderKey') ? $current_token->getProviderKey() : $current_token->getKey();
			$token = new UsernamePasswordToken($user, null, $providerKey);
			$this->app['security']->setToken($token);
	
			$this->app['user'] = $user;
		}
	}
	
	
}