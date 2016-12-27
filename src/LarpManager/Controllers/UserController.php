<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace LarpManager\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use InvalidArgumentException;

use LarpManager\Form\UserPersonnageSecondaireForm;
use LarpManager\Form\UserFindForm;
use LarpManager\Form\EtatCivilForm;
use LarpManager\Form\MessageForm;
use LarpManager\Form\NewMessageForm;
use LarpManager\Form\RestrictionForm;
use LarpManager\Form\UserRestrictionForm;
use LarpManager\Form\User\UserPersonnageDefaultForm;
use LarpManager\Form\User\UserNewForm;

use LarpManager\Entities\Restriction;
use LarpManager\Entities\User;
use LarpManager\Entities\Gn;
use LarpManager\Entities\Participant;
use LarpManager\Entities\Message;

use LarpManager\Repository\ParticipantRepository;

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
	 * 
	 * @var boolean $isEmailConfirmationRequired
	 */
	private $isEmailConfirmationRequired = true;
	
	/**
	 * 
	 * @var boolean $isPasswordResetEnabled
	 */
	private $isPasswordResetEnabled = true;
	
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
	 * Création d'un nouvel utilisateur
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function adminNewAction(Application $app, Request $request)
	{				
		$form = $app['form.factory']->createBuilder(new UserNewForm(), array())
			->add('save','submit', array('label' => "Créer l'utilisateur"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$plainPassword = $this->generatePassword();
			
			$user = $app['user.manager']->createUser(
					$data['email'],
					$plainPassword,
					$data['username'],
					array('ROLE_USER'));
				
			$user->setIsEnabled(true);
			$app['orm.em']->persist($user);
			
			
			if ( $data['gn'] )
			{
				$participant = new Participant();
				$participant->setUser($user);
				$participant->setGn($data['gn']);
				
				if ( $data['billet'] )
				{
					$participant->setBillet($data['billet']);
				}	
					
				$app['orm.em']->persist($participant);
			}
			
			$app['orm.em']->flush();
			
			$app['notify']->newUser($user, $plainPassword);
			
			$app['session']->getFlashBag()->add('success', 'L\'utilisateur a été ajouté.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		return $app['twig']->render('admin/user/new.twig', array(
				'form' => $form->createView()
		));
	}
	
	/**
	 * Choix du personnage par défaut de l'utilisateur
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function personnageDefaultAction(Application $app, Request $request, User $user)
	{
		if ( ! $app['security.authorization_checker']->isGranted('ROLE_ADMIN') && ! $user == $app['user'])
		{
			$app['session']->getFlashBag()->add('error', 'Vous n\'avez pas les droits necessaires pour cette opération.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}

		$form = $app['form.factory']->createBuilder(new UserPersonnageDefaultForm(), $app['user'], array('user_id' => $user->getId()))
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$user = $form->getData();
			
			$app['orm.em']->persist($user);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Vos informations ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		return $app['twig']->render('public/user/personnageDefault.twig', array(
				'form' => $form->createView(),
				'user' => $user,
		));
	}

	
	/**
	 * Choix des restrictions alimentaires par l'utilisateur
	 */
	public function restrictionAction(Application $app, Request $request)
	{	
		$form = $app['form.factory']->createBuilder(new UserRestrictionForm(), $app['user'])
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$user = $form->getData();
			$newRestriction = $form->get("new_restriction")->getData();
			if ( $newRestriction )
			{
				$restriction = new Restriction();
				$restriction->setUserRelatedByAuteurId($app['user']);
				$restriction->setLabel($newRestriction);
				
				$app['orm.em']->persist($restriction);
				$user->addRestriction($restriction);
			}	
			
			$app['orm.em']->persist($user);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Vos informations ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		
		return $app['twig']->render('public/user/restriction.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Affiche la liste des GN actifs
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function gnListAction(Application $app, Request $request)
	{
		$gns = $app['orm.em']->getRepository('LarpManager\Entities\Gn')->findActive();
		
		return $app['twig']->render('public/gn/list.twig', array(
				'gns' => $gns,
		));
	}
	
	/**
	 * Affiche les informations à propos d'un GN
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Gn $gn
	 */
	public function gnDetailAction(Application $app, Request $request, Gn $gn)
	{
		return $app['twig']->render('public/gn/detail.twig', array(
				'gn' => $gn,
		));
	}
	
	/**
	 * Formulaire de participation à un jeu
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Gn $gn
	 */
	public function gnParticipeAction(Application $app, Request $request, Gn $gn)
	{
		$form = $app['form.factory']->createBuilder()
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			
			$participant = new Participant();
			$participant->setUser($app['user']);
			$participant->setGn($gn);
						
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Vous participez maintenant à '.$gn->getLabel().' !');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		return $app['twig']->render('public/gn/participe.twig', array(
				'gn' => $gn,
				'form' => $form->createView(),
		));
	}
		
	/**
	 * Affiche le détail d'un billet d'un utilisateur
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param UserHasBillet $userHasBillet
	 */
	public function userHasBilletDetailAction(Application $app, Request $request, UserHasBillet $userHasBillet)
	{
		if ( $userHasBillet->getUser() != $app['user'])
		{
			$app['session']->getFlashBag()->add('error','Vous ne pouvez pas acceder à cette information');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		return $app['twig']->render('public/userHasBillet/detail.twig', array(
				'userHasBillet' => $userHasBillet,
		));
	}
	
	/**
	 * Affiche la liste des billets de l'utilisateur
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function userHasBilletListAction(Application $app, Request $request)
	{	
		$userHasBillets = $app['user']->getUserHasBillets();
		
		return $app['twig']->render('public/userHasBillet/list.twig', array(
				'userHasBillets' => $userHasBillets,
		));
	}
	
	/**
	 * Affiche les informations de la fédéGN
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function fedegnAction(Application $app, Request $request)
	{
		return $app['twig']->render('public/user/fedegn.twig', array(
				'etatCivil' => $app['user']->getEtatCivil(),
		));
	}
	
	/**
	 * Enregistrement de l'état-civil
	 *
	 * @param Application $app
	 * @param Request $request
	 * @param unknown $id
	 */
	public function etatCivilAction(Application $app, Request $request)
	{
		$etatCivil = $app['user']->getEtatCivil();
		
		if ( ! $etatCivil )
		{
			$etatCivil = new \LarpManager\Entities\EtatCivil();
		}
		
		$form = $app['form.factory']->createBuilder(new EtatCivilForm(), $etatCivil)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$etatCivil = $form->getData();
			$app['user']->setEtatCivil($etatCivil);
	
			$app['orm.em']->persist($app['user']);
			$app['orm.em']->persist($etatCivil);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Vos informations ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
	
		return $app['twig']->render('public/user/etatCivil.twig', array(
				'form' => $form->createView(),
		));
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
	
		return $app['twig']->render('public/user/detail.twig', array(
				'user' => $user
		));
	}
	
	public function likeAction(Application $app, Request $request, User $user)
	{
		if ( $user == $app['user'])
		{
			$app['session']->getFlashBag()->add('error', 'Désolé ... Avez vous vraiment cru que cela allait fonctionner ? un peu de patience !');				
		}
		else
		{
			$user->addCoeur();
			$app['orm.em']->persist($user);
			$app['orm.em']->flush();
			$app['notify']->coeur($app['user'],$user);
			$app['session']->getFlashBag()->add('success', 'Votre coeur a été envoyé !');
		}
		return $app->redirect($app['url_generator']->generate('user.view', array('id' => $user->getId())));
		
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
				'available_roles' => $app['larp.manager']->getAvailableRoles()
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
	public function adminListAction(Application $app, Request $request)
	{
		$order_by = $request->get('order_by') ?: 'username';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		$criteria = array();
		$type= null;
		$value = null;

		$form = $app['form.factory']->createBuilder(new UserFindForm())->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$type = $data['type'];
			$value = $data['value'];
		}
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
		$users = $repo->findList(
						$type,
						$value,
						array( 'by' =>  $order_by, 'dir' => $order_dir),
						$limit,
						$offset);
		
		$numResults = $repo->findCount($criteria);

		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('user.admin.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);

		return $app['twig']->render('admin/user/list.twig', array(
				'users' => $users,
				'paginator' => $paginator,
				'form' => $form->createView(),
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
				if ($this->isEmailConfirmationRequired) {
					$user->setEnabled(false);
					$user->setConfirmationToken($app['user.tokenGenerator']->generateToken());
				}
				$app['user.manager']->insert($user);
		
				if ($this->isEmailConfirmationRequired) {
					// Send email confirmation.
					$app['user.mailer']->sendConfirmationMessage($user);
		
					// Render the "go check your email" page.
					return $app['twig']->render('user/register-confirmation-sent.twig', array(
						'email' => $user->getEmail(),
					));
				} else {
					// Log the user in to the new account.
					$app['user.manager']->loginAsUser($user);
		
					$app['session']->getFlashBag()->set('success', 'Votre compte a été créé ! vous pouvez maintenant rejoindre un groupe et créer votre personnage');
		
					return $app->redirect($app['url_generator']->generate('homepage'));
				}
		
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
	 * Confirmation de l'adresse email
	 *
	 * @param Application $app
	 * @param Request $request
	 * @param string $token
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function confirmEmailAction(Application $app, Request $request, $token)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
		$user = $repo->findOneByConfirmationToken($token);
		
		if (!$user) {
			$app['session']->getFlashBag()->set('alert', 'Désolé, votre lien de confirmation a expiré.');
			return $app->redirect($app['url_generator']->generate('user.login'));
		}
		$user->setConfirmationToken(null);
		$user->setEnabled(true);
		$app['orm.em']->persist($user);
		$app['orm.em']->flush();
		
		$app['user.manager']->loginAsUser($user);
		$app['session']->getFlashBag()->set('alert', 'Merci ! Votre compte a été activé.');
		return $app->redirect($app['url_generator']->generate('user.view', array('id' => $user->getId())));
	}
	
	/**
	 * Renvoyer un email de confirmation
	 *
	 * @param Application $app
	 * @param Request $request
	 * @return mixed
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function resendConfirmationAction(Application $app, Request $request)
	{
		$email = $request->request->get('email');
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
		$user = $repo->findOneByEmail($email);
		
		if (!$user) {
			throw new NotFoundHttpException('Aucun compte n\'a été trouvé avec cette adresse email.');
		}
		if (!$user->getConfirmationToken()) {
			$user->setConfirmationToken($app['user.tokenGenerator']->generateToken());
			$app['orm.em']->persist($user);
			$app['orm.em']->flush();
		}
		$app['user.mailer']->sendConfirmationMessage($user);

		return $app['twig']->render('user/register-confirmation-sent.twig', array(
			'email' => $user->getEmail(),
		));
	}
	

	/**
	 * Traitement mot de passe oublié
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function forgotPasswordAction(Application $app, Request $request)
	{
		if (!$this->isPasswordResetEnabled) {
			throw new NotFoundHttpException('Password resetting is not enabled.');
		}
		$error = null;
		if ($request->isMethod('POST')) {
			$email = $request->request->get('email');
			
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
			$user = $repo->findOneByEmail($email);
			
			if ($user) {
				// Initialize and send the password reset request.
				$user->setTimePasswordResetRequested(time());
				if (!$user->getConfirmationToken()) {
					$user->setConfirmationToken($app['user.tokenGenerator']->generateToken());
				}
				$app['orm.em']->persist($user);
				$app['orm.em']->flush();
				
				$app['user.mailer']->sendResetMessage($user);
				$app['session']->getFlashBag()->set('alert', 'Les instructions pour enregistrer votre mot de passe ont été envoyé par mail.');
				$app['session']->set('_security.last_username', $email);
				return $app->redirect($app['url_generator']->generate('user.login'));
			}
			$error = 'No user account was found with that email address.';
		} else {
			$email = $request->request->get('email') ?: ($request->query->get('email') ?: $app['session']->get('_security.last_username'));
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $email = '';
		}
		return $app['twig']->render('user/forgot-password.twig', array(
				'email' => $email,
				'fromAddress' => $app['user.mailer']->getFromAddress(),
				'error' => $error,
		));
	}
	

	/**
	 * @param Application $app
	 * @param Request $request
	 * @param string $token
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function resetPasswordAction(Application $app, Request $request, $token)
	{
		if (!$this->isPasswordResetEnabled) {
			throw new NotFoundHttpException('Password resetting is not enabled.');
		}
		$tokenExpired = false;
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
		$user = $repo->findOneByConfirmationToken($token);
		
		if (!$user) {
			$tokenExpired = true;
		} else if ($user->isPasswordResetRequestExpired($app['config']['user']['passwordReset']['tokenTTL'])) {
			$tokenExpired = true;
		}
		if ($tokenExpired) {
			$app['session']->getFlashBag()->set('alert', 'Sorry, your password reset link has expired.');
			return $app->redirect($app['url_generator']->generate('user.login'));
		}
		$error = '';
		if ($request->isMethod('POST')) {
			// Validate the password
			$password = $request->request->get('password');
			if ($password != $request->request->get('confirm_password')) {
				$error = 'Passwords don\'t match.';
			} else if ($error = $app['user.manager']->validatePasswordStrength($user, $password)) {
				;
			} else {
				// Set the password and log in.
				$app['user.manager']->setUserPassword($user, $password);
				$user->setConfirmationToken(null);
				$user->setEnabled(true);
				$app['orm.em']->persist($user);
				$app['orm.em']->flush();
				$app['user.manager']->loginAsUser($user);
				$app['session']->getFlashBag()->set('alert', 'Your password has been reset and you are now signed in.');
				return $app->redirect($app['url_generator']->generate('user.view', array('id' => $user->getId())));
			}
		}
		return $app['twig']->render('user/reset-password.twig', array(
				'user' => $user,
				'token' => $token,
				'error' => $error,
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
				'roles' => $app['larp.manager']->getAvailableRoles())
		);
	}
}