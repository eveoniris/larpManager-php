<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\RequestForm
 *
 * @author kevin
 *
 */
class RequestAllianceForm extends AbstractType
{

	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('requestedGroupe', 'entity', array(
				'required' => true,
				'label' => 'Groupe avec lequel vous voulez vous allier',
				'class' => 'LarpManager\Entities\Groupe',
				'property' => 'nom',
				))
				->add('message','textarea', array(
						'label' => 'Un petit mot pour expliquer votre démarche',
						'required' => true,
						'attr' => array(
							'class' => 'tinymce',
							'rows' => 9,
							'help' => 'Ce texte sera transmis au chef de groupe concerné.'),
				));
	}

	/**
	 * Définition de la classe d'entité concernée
	 *
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\GroupeAllie',
		));
	}

	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'requestAlliance';
	}
}