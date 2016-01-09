<?php

namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\Type\PersonnageSecondairesCompetencesType
 *
 * @author kevin
 *
 */
class PersonnageSecondairesCompetencesType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('competence','entity', array(
					'label' => false,
					'required' => true,
					'property' => 'label',
					'class' => 'LarpManager\Entities\Competence',						
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\PersonnageSecondaireCompetence',
		));
	}
	
	public function getName()
	{
		return 'personnageSecondairesCompetences';
	}
}