<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use LarpManager\Form\Type\CompetenceType;

class ClasseForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label_masculin','text', array(
					'required' => true,	)
				)
				->add('label_feminin','text', array(
						'required' => true,	)
				)
				->add('description','textarea', array(
					'required' => false,)
				)
				->add('competenceFavorites','entity', array(
					'label' => "Compétences favorites",
					'required' => false,
					'property' => 'nom',
					'multiple' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\Competence',	)
				)
				->add('competenceNormales','entity', array(
					'label' => "Compétences normales",
					'required' => false,
					'property' => 'nom',
					'multiple' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\Competence',	)
				)
				->add('competenceCreations','entity', array(
					'label' => "Compétences acquises à la création",
					'required' => false,
					'property' => 'nom',
					'multiple' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\Competence',	)
				);
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Classe',
		));
	}

	public function getName()
	{
		return 'classe';
	}
}