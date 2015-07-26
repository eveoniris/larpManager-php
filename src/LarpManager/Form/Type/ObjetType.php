<?php

namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ObjetType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text')
				->add('code','text')
				->add('description','textarea', array('required' => false))
				
				->add('photo', new PhotoType(),  array('required' => false))
				
				->add('proprietaire','entity', array('required' => false, 'class' => 'LarpManager\Entities\Proprietaire', 'property' => 'nom'))
				->add('responsable','entity', array('required' => false, 'class' => 'LarpManager\Entities\Users', 'property' => 'name'))
				->add('localisation','entity', array('required' => false, 'class' => 'LarpManager\Entities\Localisation', 'property' => 'label'))
				->add('etat','entity', array('required' => false, 'class' => 'LarpManager\Entities\Etat', 'property' => 'label'))				
				->add('tags','entity', array('required' => false, 'class' => 'LarpManager\Entities\tag', 'property' => 'nom', 'multiple' => true, 'expanded' => true))
				
				->add('objetCarac', new ObjetCaracType(), array('required' => false))
				
				->add('cout','integer', array('required' => false))
				->add('nombre','integer', array('required' => false))
				->add('budget','integer', array('required' => false))
				->add('investissement','choice', array('choices' => array('false' =>'usage unique','true' => 'ré-utilisable')));	
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Objet',
				'cascade_validation' => true,
		));
	}
	
	public function getName()
	{
		return 'objet';
	}
}