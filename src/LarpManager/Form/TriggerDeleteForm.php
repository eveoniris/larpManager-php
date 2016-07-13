<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\TriggerDeleteForm
 *
 * @author kevin
 *
 */
class TriggerDeleteForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\PersonnageTrigger',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'triggerDelete';
	}
}