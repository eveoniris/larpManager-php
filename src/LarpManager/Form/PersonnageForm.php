<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use LarpManager\Form\Type\CompetenceType;

class PersonnageForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text', array(
					'required' => true,
					'label' => '' 
				))
				->add('surnom','text', array(
						'required' => false,
						'label' => ''
				))
				->add('age','entity', array(
						'required' => true,
						'label' => '',
						'class' => 'LarpManager\Entities\Age',
						'property' => 'label',
				))
				->add('genre','entity', array(
						'required' => true,
						'label' => '',
						'class' => 'LarpManager\Entities\Genre',
						'property' => 'label',
				))
				->add('intrigue','checkbox', array(
					'required' => true,
					'label' => 'Participer aux intrigues'
				));
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Personnage',
		));
	}
	
	public function getName()
	{
		return 'personnage';
	}
}