<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * LarpManager\Form\PersonnageXpForm
 *
 * @author kevin
 *
 */
class PersonnageXpForm extends AbstractType
{
	
	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('xp','integer', array(
				'label' => 'Expérience à ajouter',
				'required' => true,
			))
			->add('explanation','text', array(
				'label' => 'Explication',
				'required' => true,
			));
	
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'personnageXp';
	}
}