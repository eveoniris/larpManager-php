<?php

namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\Type\CompetenceType
 *
 * @author kevin
 *
 */
class CompetenceType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('competence','entity', array(
					'label' => false,
					'required' => true,
					'property' => 'nom',
					'class' => 'LarpManager\Entities\Competence',						
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\PersonnageCompetence',
		));
	}
	
	public function getName()
	{
		return 'personnageCompetence';
	}
}