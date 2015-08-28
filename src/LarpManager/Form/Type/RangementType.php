<?php

namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\Type\RangementType
 *
 * @author kevin
 *
 */
class RangementType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text', array('attr' => array('help' => 'Les trois premières lettres (avec le numéro de l\'objet) servirons à créer le code identifiant un objet')))
				->add('localisation','entity', array('required' => false, 'class' => 'LarpManager\Entities\Localisation', 'property' => 'label'))
				->add('precision','textarea', array('attr' => array('help'  => '')));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Rangement',
		));
	}

	public function getName()
	{
		return 'rangement';
	}
}
