<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\PersonnageUpdateLangueForm
 *
 * @author kevin
 *
 */
class PersonnageUpdateLangueForm extends AbstractType
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
		$builder->add('langues','entity', array(
				'required' => true,
				'multiple' => true,
				'expanded' => true,
				'class' => 'LarpManager\Entities\Langue',
				'choice_label' => 'label',
				'label' => 'Choisissez les langues du personnage',
				'mapped' => false,
		));

	}

	/**
	 * Définition de l'entité concerné
	 *
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'personnageUpdateLangue';
	}
}