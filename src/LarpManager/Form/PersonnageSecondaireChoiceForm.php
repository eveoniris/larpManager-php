<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\PersonnageSecondaireChoiceForm
 * 
 * @author kevin
 *
 */
class PersonnageSecondaireChoiceForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('personnageSecondaire','entity', array(
						'label' => 'Archétype',
						'required' => true,
						'class' => 'LarpManager\Entities\PersonnageSecondaire',
						'property' => 'label'));
	}
	
	/**
	 * Définition de la classe d'entité concernée
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Participant',
		));
	}
	
	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'personnageSecondaireChoice';
	}
}