<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\JoueurXpForm
 *
 * @author kevin
 *
 */
class JoueurXpForm extends AbstractType
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
		return 'joueurXp';
	}
}