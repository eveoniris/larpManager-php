<?php
/*
 * This file is based substantially on code from the FOSUserBundle package
 * <https://github.com/FriendsOfSymfony/FOSUserBundle>
 * which was released under the following license:
 *
 * Copyright (c) 2010-2011 FriendsOfSymfony
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace LarpManager\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use \Swift_Mailer;
use \Twig_Environment;
use LarpManager\Entities\User;
use LarpManager\Entities\Post;
use LarpManager\Entities\Message;
use LarpManager\Entities\SecondaryGroup;
use LarpManager\Entities\GroupeAllie;
use LarpManager\Entities\GroupeEnemy;
use LarpManager\Entities\Groupe;

class Mailer
{
    const ROUTE_CONFIRM_EMAIL = 'user.confirm-email';
    const ROUTE_RESET_PASSWORD = 'user.reset-password';
    const ROUTE_FORUM_POST = 'forum.post';
    const ROUTE_MESSAGERIE = 'user.messagerie';
    const ROUTE_GROUPE = 'groupe';

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var bool Whether to disable sending emails (ex. for dev environments). */
    protected $noSend = false;

    /** @var UrlGeneratorInterface  */
    protected $urlGenerator;

    /** @var \Twig_Environment */
    protected $twig;

    protected $fromAddress;
    protected $fromName;
    protected $confirmationTemplate;
    protected $notificationTemplate;
    protected $groupeSecondaireAcceptTemplate;
    protected $groupeSecondaireRejectTemplate;
    protected $groupeSecondaireWaitTemplate;
    protected $groupeSecondaireRemoveTemplate;
    protected $newMessageTemplate;
    protected $requestAllianceTemplate;
    protected $cancelRequestedAllianceTemplate;
    protected $acceptAllianceTemplate;
    protected $refuseAllianceTemplate;
    protected $breakAllianceTemplate;
    protected $declareWarTemplate;
    protected $requestPeaceTemplate;
    protected $acceptPeaceTemplate;
    protected $refusePeaceTemplate;
    protected $cancelRequestedPeaceTemplate;
    protected $resetTemplate;
    protected $resetTokenTtl = 86400;

    /**
     * Constructeur
     * 
     * @param \Swift_Mailer $mailer
     * @param UrlGeneratorInterface $urlGenerator
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $urlGenerator, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
    }
    
    /**
     * Définie la template de notification d'une demande d'alliance
     * @param unknown $requestAllianceTemplate
     */
    public function setRequestAllianceTemplate($requestAllianceTemplate)
    {
    	$this->requestAllianceTemplate = $requestAllianceTemplate;
    }
    
    /**
     * Défini la template de notification d'une annulation d'une demande d'alliance
     * @param unknown $cancelRequestedAllianceTemplate
     */
    public function setCancelRequestedAllianceTemplate($cancelRequestedAllianceTemplate)
    {
    	$this->cancelRequestedAllianceTemplate = $cancelRequestedAllianceTemplate;
    }
    
    /**
     * Définie la template de notification d'une acceptation d'alliance
     * @param unknown $acceptAllianceTemplate
     */
    public function setAcceptAllianceTemplate($acceptAllianceTemplate)
    {
    	$this->acceptAllianceTemplate = $acceptAllianceTemplate;
    }
    
    /**
     * Définie la template de notification d'un refus d'alliance
     * @param unknown $requestAllianceTemplate
     */
    public function setRefuseAllianceTemplate($refuseAllianceTemplate)
    {
    	$this->refuseAllianceTemplate = $refuseAllianceTemplate;
    }
    
    /**
     * Définie la template de notification d'une rupture d'alliance
     * @param unknown $requestAllianceTemplate
     */
    public function setBreakAllianceTemplate($breakAllianceTemplate)
    {
    	$this->breakAllianceTemplate = $breakAllianceTemplate;
    }
    
    /**
     * Définie la template de notification d'une déclaration de guerre
     * @param unknown $declareWarTemplate
     */
    public function setDeclareWarTemplate($declareWarTemplate)
    {
    	$this->declareWarTemplate = $declareWarTemplate;
    }    
    
    /**
     * Définie la template de notification d'une demande de paix
     * @param unknown $requestPeaceTemplate
     */
    public function setRequestPeaceTemplate($requestPeaceTemplate)
    {
    	$this->requestPeaceTemplate = $requestPeaceTemplate;
    }

    /**
     * Définie la template de notification d'une demande de paix
     * @param unknown $acceptPeaceTemplate
     */
    public function setAcceptPeaceTemplate($acceptPeaceTemplate)
    {
    	$this->acceptPeaceTemplate = $acceptPeaceTemplate;
    }
    
    /**
     * Définie la template de notification de refus d'une demande de paix
     * @param unknown $refusePeaceTemplate
     */
    public function setRefusePeaceTemplate($refusePeaceTemplate)
    {
    	$this->refusePeaceTemplate = $refusePeaceTemplate;
    }
    
    /**
     * Défini la template de notification d'une annulation d'une demande de paix
     * @param unknown $cancelRequestedPeaceTemplate
     */
    public function setCancelRequestedPeaceTemplate($cancelRequestedPeaceTemplate)
    {
    	$this->cancelRequestedPeaceTemplate = $cancelRequestedPeaceTemplate;
    }
    
    /**
     * Définie la template de notification d'un nouveau message
     * @param unknown $messageTemplate
     */
    public function setNewMessageTemplate($newMessageTemplate)
    {
    	$this->newMessageTemplate = $newMessageTemplate;
    }
    
    /**
     * @param string $confirmationTemplate
     */
    public function setConfirmationTemplate($confirmationTemplate)
    {
        $this->confirmationTemplate = $confirmationTemplate;
    }
    
    /**
     * @param string $notificationTemplate
     */
    public function setNotificationTemplate($notificationTemplate)
    {
    	$this->notificationTemplate = $notificationTemplate;
    }
    
    /**
     * @param string $groupeSecondaireAcceptTemplate
     */
    public function setGroupeSecondaireAcceptTemplate($groupeSecondaireAcceptTemplate)
    {
    	$this->groupeSecondaireAcceptTemplate = $groupeSecondaireAcceptTemplate;
    }
    
    /**
     * @param string $groupeSecondaireRejectTemplate
     */
    public function setGroupeSecondaireRejectTemplate($groupeSecondaireRejectTemplate)
    {
    	$this->groupeSecondaireRejectTemplate = $groupeSecondaireRejectTemplate;
    }
    
    /**
     * @param string $groupeSecondaireWaitTemplate
     */
    public function setGroupeSecondaireWaitTemplate($groupeSecondaireWaitTemplate)
    {
    	$this->groupeSecondaireWaitTemplate = $groupeSecondaireWaitTemplate;
    }
    
    /**
     * @param string $groupeSecondaireRejectTemplate
     */
    public function setGroupeSecondaireRemoveTemplate($groupeSecondaireRemoveTemplate)
    {
    	$this->groupeSecondaireRemoveTemplate = $groupeSecondaireRemoveTemplate;
    }
    
    /**
     * @return string
     */
    public function getConfirmationTemplate()
    {
        return $this->confirmationTemplate;
    }

    /**
     * @param string $fromAddress
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @param string $fromName
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param string $resetTemplate
     */
    public function setResetTemplate($resetTemplate)
    {
        $this->resetTemplate = $resetTemplate;
    }

    /**
     * @return string
     */
    public function getResetTemplate()
    {
        return $this->resetTemplate;
    }

    /**
     * @param int $resetTokenTtl
     */
    public function setResetTokenTtl($resetTokenTtl)
    {
        $this->resetTokenTtl = $resetTokenTtl;
    }

    /**
     * @return int
     */
    public function getResetTokenTtl()
    {
        return $this->resetTokenTtl;
    }

    /**
     * Envoi de la notification de demande d'alliance
     * @param unknown $alliance
     */
    public function sendRequestAlliance(GroupeAllie $alliance)
    {
    	$requestedGroupe = $alliance->getRequestedGroupe();
    	$responsable = $requestedGroupe->getResponsable();
    	if ( ! $responsable ) return;
    	
    	$context = array(
    		'alliance' => $alliance,
    	);
    	
    	$this->sendMessage($this->requestAllianceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Envoi de la notification d'annulation d'une demande d'alliance
     * @param unknown $alliance
     */
    public function sendCancelRequestedAlliance(GroupeAllie $alliance)
    {
    	$requestedGroupe = $alliance->getRequestedGroupe();
    	$responsable = $requestedGroupe->getResponsable();
    	if ( ! $responsable ) return;
    	 
    	$context = array(
    			'alliance' => $alliance,
    	);
    	 
    	$this->sendMessage($this->cancelRequestedAllianceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Envoi de la notification de demande d'alliance
     * @param unknown $alliance
     */
    public function sendDeclareWar(GroupeEnemy $war)
    {
    	$requestedGroupe = $war->getRequestedGroupe();
    	$responsable = $requestedGroupe->getResponsable();
    	if ( ! $responsable ) return;
    	 
    	$context = array(
    			'war' => $war,
    	);
    	 
    	$this->sendMessage($this->declareWarTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Envoi de la notification d'acceptation d'alliance
     */
    public function sendAcceptAlliance(GroupeAllie $alliance)
    {
    	$groupe = $alliance->getGroupe();
    	$responsable = $groupe->getResponsable();
    	if ( ! $responsable ) return;
    	 
    	$context = array(
    			'alliance' => $alliance
    	);
    	
    	$this->sendMessage($this->acceptAllianceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Envoi de la notification de refus d'alliance
     */
    public function sendRefuseAlliance(GroupeAllie $alliance)
    {
    	$groupe = $alliance->getGroupe();
    	$responsable = $groupe->getResponsable();
    	if ( ! $responsable ) return;
    
    	$context = array(
    			'alliance' => $alliance
    	);
    	 
    	$this->sendMessage($this->refuseAllianceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Notification Brisée une alliance
     * 
     * @param GroupeAllie $alliance
     * @param Groupe $groupe
     */
    public function sendBreakAlliance(GroupeAllie $alliance, Groupe $groupe)
    {   	
    	$responsable = $groupe->getResponsable();
    	if ( ! $responsable ) return;
    	
    	$context = array(
    			'alliance' => $alliance
    	);
    	
    	$this->sendMessage($this->breakAllianceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Notification Demander la paix
     * 
     * @param Groupe $groupe
     */
    public function sendRequestPeace(GroupeEnemy $war, Groupe $groupe)
    {
    	if ( $war->getGroupe() == $groupe)
    	{
    		$responsable = $war->getRequestedGroupe()->getResponsable();
    	}
    	else
    	{
    		$responsable = $war->getGroupe()->getResponsable();
    	}
    	if ( ! $responsable ) return;
    	 
    	$context = array(
    			'groupe' => $groupe
    	);
    	 
    	$this->sendMessage($this->requestPeaceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Notification Accepter la paix
     * 
     * @param Groupe $groupe
     */
    public function sendAcceptPeace(GroupeEnemy $war, Groupe $groupe)
    {
        if ( $war->getGroupe() == $groupe)
    	{
    		$responsable = $war->getRequestedGroupe()->getResponsable();
    	}
    	else
    	{
    		$responsable = $war->getGroupe()->getResponsable();
    	}
    	if ( ! $responsable ) return;
    
    	$context = array(
    			'groupe' => $groupe
    	);
    
    	$this->sendMessage($this->acceptPeaceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Notification Accepter la paix
     *
     * @param Groupe $groupe
     */
    public function sendRefusePeace(GroupeEnemy $war, Groupe $groupe)
    {
        if ( $war->getGroupe() == $groupe)
    	{
    		$responsable = $war->getRequestedGroupe()->getResponsable();
    	}
    	else
    	{
    		$responsable = $war->getGroupe()->getResponsable();
    	}
    	if ( ! $responsable ) return;
    
    	$context = array(
    			'groupe' => $groupe
    	);
    
    	$this->sendMessage($this->refusePeaceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
    
    /**
     * Notification Annuler la demande de paix
     *
     * @param Groupe $groupe
     */
    public function sendCancelRequestedPeace(GroupeEnemy $war, Groupe $groupe)
    {
        if ( $war->getGroupe() == $groupe)
    	{
    		$responsable = $war->getRequestedGroupe()->getResponsable();
    	}
    	else
    	{
    		$responsable = $war->getGroupe()->getResponsable();
    	}
    	if ( ! $responsable ) return;
    
    	$context = array(
    			'groupe' => $groupe
    	);
    
    	$this->sendMessage($this->cancelRequestedPeaceTemplate, $context, $this->getFromEmail(), $responsable->getEmail());
    }
        
    /**
     * 
     * @param Message $message
     */
    public function sendNewMessage(Message $message)
    {
    	$url = $this->urlGenerator->generate(self::ROUTE_MESSAGERIE, array(), true);
    	
    	$context = array(
    		'message' => $message,
    		'messagerieUrl' => $url
    	);
    	 
    	$this->sendMessage($this->newMessageTemplate, $context, $this->getFromEmail(), $message->getUserRelatedByDestinataire()->getEmail());    	
    }
    
    /**
     * Envoi du message de desabonnement d'un groupe secondaire
     * @param User $user
     * @param GroupeSecondaire $groupeSecondaire
     */
    public function sendGroupeSecondaireRemoveMessage(User $user, GroupeSecondaire $groupeSecondaire)
    {
    	$context = array(
    			'user' => $user,
    			'groupeSecondaire' => $groupeSecondaire,
    	);
    
    	$this->sendMessage($this->groupeSecondaireRemoveTemplate, $context, $this->getFromEmail(), $user->getEmail());
    }
    
    /**
     * Envoi du message de confirmation
     * @param User $user
     */
    public function sendConfirmationMessage(User $user)
    {
        $url = $this->urlGenerator->generate(self::ROUTE_CONFIRM_EMAIL, array('token' => $user->getConfirmationToken()), true);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );

        $this->sendMessage($this->confirmationTemplate, $context, $this->getFromEmail(), $user->getEmail());
    }

    /**
     * Envoi le mail pour mettre à zero son mot de passe
     * @param User $user
     */
    public function sendResetMessage(User $user)
    {
        $url = $this->urlGenerator->generate(self::ROUTE_RESET_PASSWORD, array('token' => $user->getConfirmationToken()), true);

        $context = array(
            'user' => $user,
            'resetUrl' => $url
        );

        $this->sendMessage($this->resetTemplate, $context, $this->getFromEmail(), $user->getEmail());
    }
    
    /**
     * Send a notification mail to an user
     * 
     * @param User $user
     * @param Post $post
     */
    public function sendNotificationMessage(User $user, Post $post)
    {
    	$url = $this->urlGenerator->generate(self::ROUTE_FORUM_POST,array('index'=> $post->getAncestor()->getId()), true);
    	
    	$context = array(
    			'user' => $user,
    			'postUrl' => $url,
    			'post' => $post,
    	);
    	
    	$this->sendMessage($this->notificationTemplate, $context, $this->getFromEmail(), $user->getEmail());    	
    }

    /**
     * Format the fromEmail parameter for the Swift_Mailer.
     *
     * @return array|string|null
     */
    protected function getFromEmail()
    {
        if (!$this->fromAddress) {
            return null;
        }

        if ($this->fromName) {
            return array($this->fromAddress => $this->fromName);
        }

        return $this->fromAddress;
    }


    /**
     * @param string $templateName
     * @param array  $context
     * @param string|array $fromEmail
     * @param string|array $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        if ($this->noSend) {
            return;
        }

        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }

    /**
     * @param boolean $noSend
     */
    public function setNoSend($noSend)
    {
        $this->noSend = (bool) $noSend;
    }
}
