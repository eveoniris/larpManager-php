<?php

namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\Type\LocalisationType
 *
 * @author kevin
 *
 */
class LocalisationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text')
				->add('precision','textarea');
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Localisation',
		));
	}

	public function getName()
	{
		return 'localisation';
	}
}
