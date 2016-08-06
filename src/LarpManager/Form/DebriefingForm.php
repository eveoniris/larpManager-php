<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\DebriefingForm
 * 
 * @author kevin
 *
 */
class DebriefingForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('gn', 'entity', array(
					'required' => true,
					'attr'=> array(
						'help' => 'A quel GN correspond ce debriefing ?',
					),
				))
				->add('text','textarea', array(
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
				'class' => 'LarpManager\Entities\Debriefing',
		));
	}
	
	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'debriefing';
	}
}