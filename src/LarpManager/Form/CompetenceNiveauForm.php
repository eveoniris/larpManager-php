<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompetenceNiveauForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('competence','entity', array(
					'required' => true,
					'class' => 'LarpManager\Entities\Competence',
					'property' => 'nom',
				))
				->add('niveau','entity', array(
					'required' => true,
					'class' => 'LarpManager\Entities\Niveau',
					'property' => 'label',
				))
				->add('description','textarea', array(
					'required' => false,		
				));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data' => 'LarpManager\Entities\CompetenceNiveau',
		));
	}

	public function getName()
	{
		return 'competenceNiveauForm';
	}
}