<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\TokenForm
 * 
 * @author kevin
 *
 */
class TokenForm extends AbstractType
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
				->add('tag', 'text', array(
					'required' => true,
					'attr'=> array(
						'help' => 'Le tag est un marqueur permettant d\'utiliser ce jeton dans LarpManager',
					),
				))
				->add('description','textarea', array(
					'required' => true,
					'attr' => array(
						'class'=> 'tinymce',
						'rows' => 9),
				));
	}
	
	/**
	 * Définition de la classe d'entité concernée
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Token',
		));
	}
	
	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'token';
	}
}