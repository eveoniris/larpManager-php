<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupeInscriptionForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('code','text', array(
					'label' => "Entrez le code du groupe",
					'required' => true,	
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	}
	
	public function getName()
	{
		return 'groupeInscription';
	}
}