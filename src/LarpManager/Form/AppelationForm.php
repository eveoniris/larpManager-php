<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppelationForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text', array(
				'label' => 'Label',
				'required' => true,
		))
		->add('description','textarea', array(
				'label' => 'Description',
				'required' => false,
				'attr' => array('rows' => 10),
		))
		->add('titre','text', array(
				'label' => 'Titre',
				'required' => false,
		))
		->add('appelation','entity', array(
				'label' => 'Cette appelation dépend de',
				'required' => false,
				'class' => 'LarpManager\Entities\Appelation',
				'property' => 'label',
		));
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Appelation',
		));
	}
	
	public function getName()
	{
		return 'appelation';
	}
}