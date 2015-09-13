<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\GroupeSecondairePostulerForm
 *
 * @author kevin
 *
 */
class GroupeSecondairePostulerForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('explanation','textarea', array(
				'required' => true,
				'attr' => array(
						'rows' => 9,
						'help' => 'Soyez convaincant, votre explication sera transmise au chef de groupe qui validera (ou pas) votre demande.'),
		));
	}

	/**
	 * Définition de l'entité conercné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'secondaryGroupApply';
	}
}
