<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\AgeForm
 *
 * @author kevin
 *
 */
class BreakAllianceForm extends AbstractType
{

	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

	}

	/**
	 * Définition de la classe d'entité concernée
	 *
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	}

	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'breakAlliance';
	}
}