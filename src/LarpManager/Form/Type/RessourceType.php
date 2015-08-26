<?php

namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class RessourceType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('ressource','entity', array(
					'label' => false,
					'required' => true,
					'property' => 'label',
					'class' => 'LarpManager\Entities\Ressource',						
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\RegionRessource',
		));
	}
	
	public function getName()
	{
		return 'regionRessource';
	}
}