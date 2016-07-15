<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\MessageForm
 * 
 * @author kevin
 *
 */
class MessageForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title','text', array(
					'required' => true,	
					'label' => 'Titre',
				))
				->add('text','textarea', array(
					'required' => true,
					'label' => 'Message',
					'attr' => array(
							'rows' => 9,
							'class' => 'tinymce'
					),
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
				'class' => 'LarpManager\Entities\Message',
		));
	}
	
	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'message';
	}
}