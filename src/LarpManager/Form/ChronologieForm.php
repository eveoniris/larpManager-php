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
			->add('year','integer', array(
					'required' => true,
					'label' => 'Année',
					
			))
			->add('month','integer', array(
					'required' => false,
					'label' => 'Mois (falcultatif)',
					
			))
			->add('day','integer', array(
					'required' => false,
					'label' => 'Jour (falcultatif)',
					
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