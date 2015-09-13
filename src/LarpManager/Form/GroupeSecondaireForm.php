<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\GroupeSecondaireForm
 *
 * @author kevin
 *
 */
class GroupeSecondaireForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text')
				->add('description','textarea', array(
						'required' => true,
						'label' => 'Description',
						'attr' => array('rows' => 9)
				))
				->add('responsable', 'entity', array(
						'required' => false,
						'label' => 'Chef du groupe',
						'class' => 'LarpManager\Entities\Personnage',
						'property' => 'identity',
				))
				->add('secondaryGroupType','entity', array(
						'label' => 'Type',
						'required' => true,
						'class' => 'LarpManager\Entities\SecondaryGroupType',
						'property' => 'label',
				));
	}

	/**
	 * Définition de l'entité conercné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\SecondaryGroup',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'secondaryGroup';
	}
}
