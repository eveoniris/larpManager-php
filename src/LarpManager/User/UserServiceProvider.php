<?php

namespace LarpManager\User;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Request;


class UserServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{		
		// Token generator.
		$app['user.tokenGenerator'] = $app->share(function($app) { 
			return new TokenGenerator($app['logger']); 
		});
		
		// Mailer
		$app['user.mailer'] = $app->share(function($app) {
			$mailer = new Mailer($app['mailer'], $app['url_generator'], $app['twig']);
			$mailer->setFromAddress($app['config']['user']['mailer']['fromEmail']['address'] ?: null);
			$mailer->setFromName($app['config']['user']['mailer']['fromEmail']['name'] ?: null);
			$mailer->setConfirmationTemplate('user/email/confirm-email.twig');
			$mailer->setResetTemplate('user/email/reset-password.twig');
			$mailer->setResetTokenTtl($app['config']['user']['passwordReset']['tokenTTL']);
			return $mailer;
		});

		// User manager
		$app['user.manager'] = $app->share(function($app) {
			$userManager = new UserManager($app['db'], $app);
			return $userManager;
		});
		
		// Current user.
		$app['user'] = $app->share(function($app) {
			return ($app['user.manager']->getCurrentUser());
		});
		
		// Add a custom security voter to support testing user attributes.
		$app['security.voters'] = $app->extend('security.voters', function($voters) use ($app) {
			foreach ($voters as $voter) {
				if ($voter instanceof RoleHierarchyVoter) {
					$roleHierarchyVoter = $voter;
					break;
				}
			}
			$voters[] = new EditUserVoter($roleHierarchyVoter);
			return $voters;
		});
		
		// Helper function to get the last authentication exception thrown for the given request.
		// It does the same thing as $app['security.last_error'](),
		// except it returns the whole exception instead of just $exception->getMessage()
		$app['user.last_auth_exception'] = $app->protect(function (Request $request) {
			if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
				return $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
			}
		
			$session = $request->getSession();
			if ($session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
				$exception = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
				$session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
		
				return $exception;
			}
		});
	}
	
	public function boot(Application $app)
	{				
		if (isset($app['user.passwordStrengthValidator'])) {
			$app['user.manager']->setPasswordStrengthValidator($app['user.passwordStrengthValidator']);
		}
	}
}
