<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * LarpManager\Form\GroupeInscriptionForm
 *
 * @author kevin
 *
 */
class GroupeInscriptionForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('code','text', array(
					'label' => "Entrez le code du groupe",
					'required' => true,	
				));
	}
		
	/**
	 * Nom du formulaire 
	 * @return string
	 */
	public function getName()
	{
		return 'groupeInscription';
	}
}