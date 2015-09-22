<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\PostulantReponseForm
 *
 * @author kevin
 *
 */
class PostulantReponseForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('explanation','textarea', array(
				'label' => 'Votre réponse',
				'required' => true,
				'attr' => array(
						'rows' => 9,
						'help' => 'un petit mot de bienvenue, ou d\'explication de votre refus'),
		));
	}

	/**
	 * Définition de l'entité conercné
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
		return 'postulantReponse';
	}
}