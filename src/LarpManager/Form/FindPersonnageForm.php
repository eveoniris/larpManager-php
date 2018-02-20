<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\FindJoueurForm
 *
 * @author kevin
 *
 */
class FindPersonnageForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('search','text', array(
						'required' => true,
				))
				->add('type', 'choice', array(
						'required' => true,
						'choices' => array(
							'lastName' => 'Nom du joueur',
							'firstName' => 'Prénom du joueur',
							'numero' => 'Numéro',
						)
				));
	}
	
	/**
	 * Définition de l'entité concernée
	 *
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'findPersonnage';
	}
}