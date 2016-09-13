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

namespace LarpManager\Services\Manager;

use Silex\Application;
use Doctrine\Common\Collections\ArrayCollection;

use LarpManager\Entities\User;
use LarpManager\Entities\Message;
use LarpManager\Entities\Billet;
use LarpManager\Entities\GroupeGn;
use LarpManager\Entities\Notification;

class NotifyManager
{
	/** @var \Silex\Application */
	protected $app;
	protected $fromAddress;
	protected $fromName;
	
	/**
	 * Constructeur
	 *
	 * @param \Silex\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->fromAddress = $app['config']['user']['mailer']['fromEmail']['address'];
		$this->fromName = $app['config']['user']['mailer']['fromEmail']['name'];
	}
	
	/**
	 * Gestion de la notification "Nouveau Message"
	 * - envoi d'un mail de notification
	 * - creation de la notification
	 * 
	 * @param User $user
	 */
	public function newMessage(User $user, Message $message)
	{
		$notification = new Notification();
		$notification->setText('Vous avez reçu un nouveau message de '.$message->getUserRelatedByAuteur()->getUserName());
		$notification->setUser($user);
		$notification->setUrl($this->app['url_generator']->generate('messagerie'));
		
		$this->app['orm.em']->persist($notification);
		$this->app['orm.em']->flush();

		$url = $this->app['url_generator']->generate('homepage');
		
		$this->sendMessage(
				'user/email/newMessage.twig', 
				array(
					'message' => $message,
					'messagerieUrl' => $url),
				$this->fromAddress, 
				$user->getEmail()
			);
	}
	
	/**
	 * Notification "nouveau Billet"
	 * 
	 * @param User $user
	 * @param Billet $billet
	 */
	public function newBillet(User $user, Billet $billet)
	{
		$notification = new Notification();
		$notification->setText('Vous avez reçu un nouveau billet : '.$billet->getGn()->getLabel().' '.$billet->getLabel());
		$notification->setUser($user);
		
		$this->app['orm.em']->persist($notification);
		$this->app['orm.em']->flush();
		
		$url = $this->app['url_generator']->generate('homepage');
		
		$this->sendMessage(
				'user/email/newBillet.twig',
				array(
						'billet' => $billet,
						'messagerieUrl' => $url),
				$this->fromAddress,
				$user->getEmail()
			);
	}
	
	/**
	 * Notification désigner comme responsable
	 * 
	 * @param User $user
	 * @param GroupeGn $groupeGn
	 */
	public function newResponsable($user, $groupeGn)
	{
		$notification = new Notification();
		$notification->setText('Vous avez été désigné responsable du groupe : '.$groupeGn->getGroupe()->getNom());
		$notification->setUser($user);
		
		$this->app['orm.em']->persist($notification);
		$this->app['orm.em']->flush();
		
		$url = $this->app['url_generator']->generate('homepage');
		
		$this->sendMessage(
				'user/email/newResponsable.twig',
				array(
						'groupeGn' => $groupeGn,
						'messagerieUrl' => $url),
				$this->fromAddress,
				$user->getEmail()
				);
	}
	
	/**
	 * Notification désigner comme nouveau membre d'un groupe
	 *
	 * @param User $user
	 * @param GroupeGn $groupeGn
	 */
	public function newMembre($user, $groupeGn)
	{
		$notification = new Notification();
		$notification->setText('Vous avez été ajouté au groupe : '.$groupeGn->getGroupe()->getNom());
		$notification->setUser($user);
	
		$this->app['orm.em']->persist($notification);
		$this->app['orm.em']->flush();
	
		$url = $this->app['url_generator']->generate('homepage');
	
		$this->sendMessage(
				'user/email/newMembre.twig',
				array(
						'groupeGn' => $groupeGn,
						'messagerieUrl' => $url),
				$this->fromAddress,
				$user->getEmail()
				);
	}
	
	/**
	 * Envoi du message
	 * 
	 * @param unknown $templateName
	 * @param unknown $context
	 * @param unknown $from
	 * @param unknown $to
	 */
	private function sendMessage($templateName, $context, $from, $to)
	{
		$context = $this->app['twig']->mergeGlobals($context);
		$template = $this->app['twig']->loadTemplate($templateName);
		$subject = $template->renderBlock('subject', $context);
		$textBody = $template->renderBlock('body_text', $context);
		$htmlBody = $template->renderBlock('body_html', $context);
		
		$message = \Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom($from)
			->setTo($to);
		
		if (!empty($htmlBody)) {
			$message->setBody($htmlBody, 'text/html')
			->addPart($textBody, 'text/plain');
		} else {
			$message->setBody($textBody);
		}
		
		$this->app['mailer']->send($message);
	}
}