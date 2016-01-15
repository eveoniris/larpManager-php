<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\ChronologieForm
 *
 * @author kevin
 *
 */
class ChronologieForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('territoire','entity', array(
					'required' => true,
					'label' => 'Territoire',
					'class' => 'LarpManager\Entities\Territoire',
					'property' => 'nom',
			))
			->add('description','textarea', array(
					'required' => true,
					'label' => 'Description',
					'attr' => array('rows' => 9),
			))
			->add('date','datetime', array(
					'required' => true,
					'label' => 'Date',
			));
				
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Chronologie',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'chronologie';
	}
}