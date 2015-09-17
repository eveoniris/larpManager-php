<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\JoueurForm
 *
 * @author kevin
 *
 */
class JoueurForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text', array(
					'label' => 'Nom civil',
					'required' => true,
				))
				->add('prenom','text', array(
					'label' => 'Prénom civil',
					'required' => true,
				))
				->add('prenom_usage','text', array(
					'label' => 'Nom d\'usage',
					'required' => false,
				))
				->add('telephone','text', array(
					'label' => 'Numéro de téléphone',
					'required' => true,
				))
				->add('probleme_medicaux','textarea', array(
					'label' => 'Eventuel problèmes médicaux',
					'required' => true,
				))
				->add('personne_a_prevenir','text', array(
					'label' => 'Personne à prévenir en cas de problème',
					'required' => true,
				))
				->add('tel_pap','text', array(
					'label' => 'Numéro de téléphone de la personne à prévenir',
					'required' => true,
				))
				->add('fedegn','text', array(
					'label' => 'Numéro d’adhérent FédéGN',
					'required' => true,
				))
				->add('dateFinValiditeFedegn','date', array(
						'label' => 'Date de fin de validité de votre carte FédéGN',
						'required' => true,
				));
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Joueur',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'joueur';
	}
}