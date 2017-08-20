<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2017-01-06 12:33:28.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use LarpManager\Entities\BaseQuestion;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Entities\Question
 *
 * @Entity(repositoryClass="LarpManager\Repository\QuestionRepository")
 */
class Question extends BaseQuestion
{
		/**
		 * Fourni la réponse à une question en fonction de son hash
		 * 
		 * @param unknown $hash
		 */
		public function getReponse($hash)
		{
			foreach ( preg_split('/[;]+/',$this->getChoix()) as $reponse)
			{
				if ( sha1($reponse) == $hash ) return $reponse;
			}
			return false;
		}
		
		/**
		 * Compte les réponse à une question
		 * 
		 * @param unknown $reponse
		 */
		public function getReponsesCount($reponse)
		{
			$count = 0;
			foreach( $this->getReponses() as $rep)
			{
				if ( $rep->getReponse() == sha1($reponse)) $count++;
			}
			return $count;
		}
		
		/**
		 * Obtient la liste des participants ayant répondu (en fonction de la réponse)
		 * 
		 * @param unknown $rep
		 */
		public function getParticipants($rep)
		{
			$participants = new ArrayCollection();
			
			foreach( $this->getReponses() as $reponse)
			{
				if ( $reponse->getReponse() == sha1($rep))
				{
					$participants[] = $reponse->getParticipant();
				}
			}
			return $participants;
		}
}