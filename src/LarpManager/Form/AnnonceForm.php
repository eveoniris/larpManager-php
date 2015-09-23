<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\AnnonceForm
 * 
 * @author kevin
 *
 */
class AnnonceForm extends AbstractType
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
				))
				->add('archive','choice', array(
						'required' => true,
						'choices' => array(false => 'Publique', true => 'Dans les archive'),
						'label' => 'Choisissez la visibilité de votre annonce',
				))
				->add('text','textarea', array(
					'required' => true,
					'attr' => array('rows' => 9),
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
				'class' => 'LarpManager\Entities\Annonce',
		));
	}
	
	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'annonce';
	}
}