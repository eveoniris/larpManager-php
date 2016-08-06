<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\BackgroundForm
 *
 * @author kevin
 *
 */
class BackgroundForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('titre','text', array(
					'required' => true,
					'label' => 'Titre',
				))
				->add('text','textarea', array(
					'required' => true,
					'label' => 'Contenu',
					'attr' => array(
						'class' => 'tinymce',
						'rows' => 15),
				))
				->add('groupe','entity', array(
						'required' => true,
						'label' => 'Groupe',
						'class' => 'LarpManager\Entities\Groupe',
						'property' => 'nom',
				))
				->add('gn', 'entity', array(
						'required' => true,
						'label' => 'GN',
						'class' => 'LarpManager\Entities\Gn',
						'property' => 'label',
						'placeholder' => 'Choisissez le GN auquel est lié ce background',
						'empty_data'  => null
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
				'data_class' => 'LarpManager\Entities\Background',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'background';
	}
}