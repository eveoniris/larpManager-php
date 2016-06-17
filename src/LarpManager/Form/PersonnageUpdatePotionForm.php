<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\PersonnageUpdatePotionForm
 *
 * @author kevin
 *
 */
class PersonnageUpdatePotionForm extends AbstractType
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
		$builder->add('potions','entity', array(
				'required' => true,
				'multiple' => true,
				'expanded' => true,
				'class' => 'LarpManager\Entities\Potion',
				'choice_label' => 'fullLabel',
				'label' => 'Choisissez les potions du personnage'
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
		return 'personnageUpdatePotion';
	}
}