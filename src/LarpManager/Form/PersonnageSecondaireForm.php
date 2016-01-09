<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use LarpManager\Form\Type\PersonnageSecondairesCompetencesType;

/**
 * LarpManager\Form\PersonnageSecondaireForm
 *
 * @author kevin
 *
 */
class PersonnageSecondaireForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add(
					'classe','entity', array(
						'required' => true,
						'label' => 'Choisissez la classe',
						'class' => 'LarpManager\Entities\Classe',
						'property' => 'label',
				))
				->add(
					'personnageSecondaireCompetences', 'collection', array(
						'label' => "Competences",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new PersonnageSecondairesCompetencesType()					
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
				'data_class' => 'LarpManager\Entities\PersonnageSecondaire',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'personnageSecondaire';
	}
}