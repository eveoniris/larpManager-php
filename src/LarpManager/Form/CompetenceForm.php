<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\CompetenceForm
 *
 * @author kevin
 *
 */
class CompetenceForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('competenceFamily','entity', array(
					'label' => 'Famille',
					'required' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',
					'property' => 'label',
				))
				->add('level','entity', array(
					'label' => 'Niveau',
					'required' => true,
					'class' => 'LarpManager\Entities\Level',
					'property' => 'label',
				))
				->add('description','textarea', array(
					'required' => false,	
				))
				->add('document','file', array(
					'label' => 'Téléversez un document',
					'required' => true,
					'mapped' => false
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
				'class' => 'LarpManager\Entities\Competence',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'competence';
	}
}