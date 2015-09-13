<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * LarpManager\Form\SearchObjetForm
 *
 * @author kevin
 *
 */
class SearchObjetForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$builder->add('type','choice', array(
					'required' => true,
					'choices' => array(
						'nom' => 'Nom',
						'numero' => 'Numero')))
				->add('value','text', array('required' => true));
		
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'searchObjetForm';
	}
}
