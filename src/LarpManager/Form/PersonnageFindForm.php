<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\PersonnageFindForm
 *
 * @author kevin
 *
 */
class PersonnageFindForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('value','text', array(
					'required' => true,	
					'label' => 'Recherche',
					'attr' => array(
						'placeholder' => 'Votre recherche',
						'aria-label' => "...",
					),
				))
				->add('type','choice', array(
					'required' => true,
					'choices' => array(
						'id' => 'ID',
						'nom' => 'Nom',
					),
					'label' => 'Type',
					'attr' => array(
						'aria-label' => "...",
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
		return 'personnageFind';
	}
}