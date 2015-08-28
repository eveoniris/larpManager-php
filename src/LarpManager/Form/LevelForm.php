<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\LevelForm
 *
 * @author kevin
 *
 */
class LevelForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text', array(
					'required' => true,	
				))
				->add('index','integer', array(
					'required' => true,
				))
				->add('cout_favori','integer', array(
						'label' => "Coût favori",
						'required' => true,
				))
				->add('cout','integer', array(
					'label' => "Coût normal",
					'required' => true,
				))
				->add('cout_meconu','integer', array(
					'label' => "Coût méconnu",
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
				'data' => 'LarpManager\Entities\Level',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'levelForm';
	}
}