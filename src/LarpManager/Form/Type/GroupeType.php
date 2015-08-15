<?php

namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text')
				->add('description','textarea', array(
						'required' => false,
				))
				->add('code','text', array(
						'required' => false,
				))
				->add('user','entity', array(
							'label' => 'Scénariste',
							'required' => false, 
							'class' => 'LarpManager\Entities\User',
							'property' => 'name',
							))
				->add('jeu_strategique','choice', array(
						'required' => false,
						'choices' => array('false' =>'Ne participe pas','true' => 'Participe')))
				->add('jeu_maritime','choice', array(
						'required' => false,
						'choices' => array('false' =>'Ne participe pas','true' => 'Participe')));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Groupe',
		));
	}

	public function getName()
	{
		return 'groupe';
	}
}
