<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\ClasseForm
 *
 * @author kevin
 *
 */
class ClasseForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label_masculin','text', array(
					'required' => true,	)
				)
				->add('label_feminin','text', array(
						'required' => true,	)
				)
				->add('description','textarea', array(
					'required' => false,)
				)
				->add('competenceFamilyFavorites','entity', array(
					'label' => "Famille de compétences favorites (n'oubliez pas de cochez aussi la/les compétences acquises à la création)",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',	)
				)
				->add('competenceFamilyNormales','entity', array(
					'label' => "Famille de compétences normales",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',	)
				)
				->add('competenceFamilyCreations','entity', array(
					'label' => "Famille de compétences acquises à la création",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',	)
				);
	}
	
	/**
	 * Définition de l'entité concernée
	 * 
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Classe',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'classe';
	}
}