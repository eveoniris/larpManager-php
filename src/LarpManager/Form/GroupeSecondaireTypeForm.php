<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\GroupeSecondaireTypeForm
 *
 * @author kevin
 *
 */
class GroupeSecondaireTypeForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text', array(
					'required' => true,
				))
				->add('description','textarea', array(
					'required' => false,
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
				'data' => 'LarpManager\Entities\SecondaryGroupType',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'groupeSecondaireType';
	}
}