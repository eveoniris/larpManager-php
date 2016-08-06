<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\PersonnageUpdateAgeForm
 *
 * @author kevin
 *
 */
class PersonnageUpdateAgeForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * Seul les éléments ne dépendant pas des points d'expérience sont modifiables
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('age','entity', array(
					'required' => true,
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Age',
					'choice_label' => 'fullLabel',
					'label' => 'Choisissez la catégorie d\'age du personnage'
				))
				->add('ageReel', 'number', array(
					'required' => true,
					'label' => 'Indiquez l\'age du personnage'
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
				'data_class' => 'LarpManager\Entities\Personnage',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'personnageUpdateAge';
	}
}