<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GroupeClasseForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('classe','entity', array(
					'label' => false,
					'required' => true,
					'property' => 'label',
					'class' => 'LarpManager\Entities\Classe',						
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\GroupeClasse',
		));
	}
	
	public function getName()
	{
		return 'groupeClasse';
	}
}