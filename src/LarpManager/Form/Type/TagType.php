<?php

namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\Type\TagType
 *
 * @author kevin
 *
 */
class TagType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text');
				
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Tag',
		));
	}
	
	public function getName()
	{
		return 'tag';
	}
}
