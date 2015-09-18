<?php

namespace LarpManager\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use InvalidArgumentException;
use LarpManager\Form\UserForm;

use JasonGrimes\Paginator;


/**
 * LarpManager\Controllers\UserController
 *
 * @author kevin
 *
 */
class UserController
{
	/**
	 * Fourni la liste des ROLES utilisé dans LarpManager
	 * @return Array $availablesRoles
	 */
	protected function getAvailableRoles()
	{
		$availableRoles = array(
				array('label' => 'ROLE_USER', 'descr' => 'Utilisateur de larpManager'),
				array('label' => 'ROLE_ADMIN', 'descr' => 'Droit de modification sur tout'),
				array('label' => 'ROLE_STOCK', 'descr' => 'Droit de modification sur le stock'),
				array('label' => 'ROLE_REGLE', 'descr' => 'Droit de modification sur les règles'),
				array('label' => 'ROLE_SCENARISTE', 'descr' => 'Droit de modification sur le scénario, les groupes et le background'),
				array('label' => 'ROLE_MODERATOR', 'descr' => 'Modération du forum'),
			);
		return $availableRoles;
	}
	
	/**
	 * Fourni l'url de gravatar pour l'utilisateur
	 * 
	 * @param string $email
	 * @param int $size
	 * @return string
	 */
	protected function getGravatarUrl($email, $size = 80)
	{
		// See https://en.gravatar.com/site/implement/images/ for available options.
		return '//www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=' . $size . '&d=identicon';
	}
	
	/**
	 * Création d'un utilisateur
	 * 
	 * @param Request $request
	 * @return User
	 * @throws InvalidArgumentException
	 */
	protected function createUserFromRequest(Application $app, Request $request)
	{
		if ($request->request->get('password') != $request->request->get('confirm_password')) {
			throw new InvalidArgumentException('Passwords don\'t match.');
		}
	
		$user = $app['user.manager']->createUser(
				$request->request->get('email'),
				$request->request->get('password'),
				$request->request->get('name') ?: null,
				array('ROLE_USER'));
	
		if ($username = $request->request->get('username')) {
			$user->setUsername($username);
		}
	
		$errors = $app['user.manager']->validate($user);
	
		if (!empty($errors)) {
			throw new InvalidArgumentException(implode("\n", $errors));
		}
	
		return $user;
	}
	
	/**
	 * Genere un mot de passe aléatoire
	 * 
	 * @param number $length
	 * @return string $password
	 */
	protected function generatePassword($length = 10)
	{
		$alphabets = range('A','Z');
		$numbers = range('0','9');
		$additional_characters = array('_','.');
		
		$final_array = array_merge($alphabets,$numbers,$additional_characters);
		
		$password = '';
		
		while($length--) {
			$key = array_rand($final_array);
			$password .= $final_array[$key];
		}
		
		return $password;
	}

	/**
	 * Affiche le détail de l'utilisateur courant
	 * 
	 * @param Application $app
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function viewSelfAction(Application $app) {
		if (!$app['user']) {
			return $app->redirect($app['url_generator']->generate('user.login'));
		}
	
		return $app->redirect($app['url_generator']->generate('user.view', array('id' => $app['user']->getId())));
	}
	

	/**
	 * View user action.
	 *
	 * @param Application $app
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 * @throws NotFoundHttpException if no user is found with that ID.
	 */
	public function viewAction(Application $app, Request $request, $id)
	{
		$user = $app['user.manager']->getUser($id);
	
		if (!$user) {
			throw new NotFoundHttpException('No user was found with that ID.');
		}
	
		if (!$user->isEnabled() && !$app['security']->isGranted('ROLE_ADMIN')) {
			throw new NotFoundHttpException('That user is disabled (pending email confirmation).');
		}
	
		return $app['twig']->render('admin/user/detail.twig', array(
				'user' => $user,
				'imageUrl' => $this->getGravatarUrl($user->getEmail()),
		));
	}
	
	/**
	 * Ajoute un utilisateur (mot de passe généré aléatoirement)
	 * @param Application $app
	 * @param Request $request
	 */
	public function addAction(Application $app, Request $request)
	{
		$form = $app['form.factory']->createBuilder(new UserForm(), array())
			->add('save','submit', array('label' => "Créer l'utilisateur"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ($request->isMethod('POST'))
		{
			$password = $this->generatePassword();
			$data = $form->getData();
			
			$user = $app['user.manager']->createUser(
					$data['email'],
					$password,
					$data['name'],
					array('ROLE_USER'));
			
			$app['user.manager']->insert($user);
			
			$app['session']->getFlashBag()->set('alert', 'L\'utilisateur a été créé. TEMPORAIRE : son mot de passe est '.$password);
			
			return $app->redirect($app['url_generator']->generate('user.list'));
		}
		
		return $app['twig']->render('user/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Edit user action.
	 *
	 * @param Application $app
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 * @throws NotFoundHttpException if no user is found with that ID.
	 */
	public function editAction(Application $app, Request $request, $id)
	{
		$errors = array();
	
		$user = $app['user.manager']->getUser($id);
		
		if ( !$user ) 
		{
			throw new NotFoundHttpException('No user was found with that ID.');
		}
		
		if ($request->isMethod('POST')) 
		{
			$user->setName($request->request->get('name'));
			$user->setEmail($request->request->get('email'));
			if ($request->request->has('username')) {
				$user->setUsername($request->request->get('username'));
			}
			if ($request->request->get('password')) {
				if ($request->request->get('password') != $request->request->get('confirm_password')) {
					$errors['password'] = 'Passwords don\'t match.';
				} else if ($error = $app['user.manager']->validatePasswordStrength($user, $request->request->get('password'))) {
					$errors['password'] = $error;
				} else {
					$app['user.manager']->setUserPassword($user, $request->request->get('password'));
				}
			}
			if ($app['security']->isGranted('ROLE_ADMIN') && $request->request->has('roles')) {
				$user->setRoles($request->request->get('roles'));
			}
		
			$errors += $app['user.manager']->validate($user);
			
			if (empty($errors)) {
				$app['user.manager']->update($user);
				$msg = 'Saved account information.' . ($request->request->get('password') ? ' Changed password.' : '');
				$app['session']->getFlashBag()->set('alert', $msg);
			}
			
		}
	
		return $app['twig']->render('admin/user/update.twig', array(
				'error' => implode("\n", $errors),
				'user' => $user,
				'available_roles' => $this->getAvailableRoles(),
				'image_url' => $this->getGravatarUrl($user->getEmail()),
		));
	}
	
	
	/**
	 * Login
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function loginAction(Application $app, Request $request)
	{
		$authException = $app['user.last_auth_exception']($request);
		
		if ($authException instanceof DisabledException) {
			// This exception is thrown if (!$user->isEnabled())
			// Warning: Be careful not to disclose any user information besides the email address at this point.
			// The Security system throws this exception before actually checking if the password was valid.
			$user = $app['user.manager']->refreshUser($authException->getUser());
		
			return $app['twig']->render('user/login-confirmation-needed.twig', array(
					'email' => $user->getEmail(),
					'fromAddress' => $app['user.mailer']->getFromAddress(),
					'resendUrl' => $app['url_generator']->generate('user.resend-confirmation'),
			));
		}
		
		return $app['twig']->render('user/login.twig', array(
				'error' => $authException ? $authException->getMessageKey() : null,
				'last_username' => $app['session']->get('_security.last_username'),
				'allowRememberMe' => isset($app['security.remember_me.response_listener']),
		));
	}
	
	/**
	 * Liste des utilisateurs
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function listAction(Application $app, Request $request)
	{
		$order_by = $request->get('order_by') ?: 'name';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		
		$criteria = array();

		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
		$users = $repo->findBy(
						$criteria,
						array( $order_by => $order_dir),
						$limit,
						$offset);
		
		$numResults = $repo->findCount($criteria);

		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('user.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);

		return $app['twig']->render('admin/user/list.twig', array(
				'users' => $users,
				'paginator' => $paginator,
		));
	}
	
	/**
	 * Enregistre un utilisateur
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @throws InvalidArgumentException
	 */
	public function registerAction(Application $app, Request $request)
	{
		if ($request->isMethod('POST')) {
			try {
				$user = $this->createUserFromRequest($app, $request);
				
				if ($error = $app['user.manager']->validatePasswordStrength($user, $request->request->get('password'))) {
					throw new InvalidArgumentException($error);
				}
				/*if ($this->isEmailConfirmationRequired) {
					$user->setEnabled(false);
					$user->setConfirmationToken($app['user.tokenGenerator']->generateToken());
				}*/
				$app['user.manager']->insert($user);
		
				/*if ($this->isEmailConfirmationRequired) {
					// Send email confirmation.
					$app['user.mailer']->sendConfirmationMessage($user);
		
					// Render the "go check your email" page.
					return $app['twig']->render($this->getTemplate('register-confirmation-sent'), array(
					'layout_template' => $this->getTemplate('layout'),
					'email' => $user->getEmail(),
					));
				} else {*/
					// Log the user in to the new account.
					$app['user.manager']->loginAsUser($user);
		
					$app['session']->getFlashBag()->set('success', 'Votre compte a été créé ! vous pouvez maintenant rejoindre un groupe et créer votre personnage');
		
					return $app->redirect($app['url_generator']->generate('homepage'));
				//}
		
			} catch (InvalidArgumentException $e) {
				$error = $e->getMessage();
			}
		}
		
		return $app['twig']->render('user/register.twig', array(
				'error' => isset($error) ? $error : null,
				'name' => $request->request->get('name'),
				'email' => $request->request->get('email'),
				'username' => $request->request->get('username'),
		));
	}
	
	/**
	 * Met a jours les droits des utilisateurs
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function rightAction(Application $app, Request $request)
	{
		$users = $app['user.manager']->findAll();
		
		if ( $request->getMethod() == 'POST')
		{			
			$newRoles = $request->get('user');
			
			foreach( $users as $user ) 
			{			
				$user->setRoles(array_keys($newRoles[$user->getId()]));
				$app['orm.em']->persist($user);
			}
			$app['orm.em']->flush();
		}
		
		// trouve tous les rôles
		return $app['twig']->render('user/right.twig', array(
				'users' => $users,
				'roles' => $this->getAvailableRoles())
		);
	}
}