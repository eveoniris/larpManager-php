<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\TrombineForm
 *
 * @author kevin
 *
 */
class RuleForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('rule','file', array(
					'label' => 'Choisissez votre fichier',
					'required' => true,
				))
				->add('label','text', array(
					'label' => 'Choisissez un titre',
					'required' => true,
				))
				->add('description','textarea', array(
					'label' => 'Ecrivez une petite description',
					'required' => true,
					'attr' => array(
							'class' => 'tinymce',
							'rows' => 5,
					)
						
				));
	}
	

	/**
	 * Définition de l'entité concerné
	 *
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'rule';
	}
}