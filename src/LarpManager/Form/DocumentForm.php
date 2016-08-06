<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\DocumentForm
 * 
 * @author kevin
 *
 */
class DocumentForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('code','text', array(
					'required' => true,	
				))
				->add('titre','text', array(
						'required' => true,
				))
				->add('auteur','text', array(
						'required' => true,
    					'empty_data'  => null,
						'attr' => array(
							'help' => 'Indiquez l\'auteur (en jeu) du document',
						)
				))
				->add('langues','entity', array(
						'required' => true,
						'multiple' => true,
						'expanded' => false,
						'label' => 'Langues dans lesquelles le document est rédigé',
						'class' => 'LarpManager\Entities\Langue',
						'property' => 'label',
						
				))
				->add('lieus','entity', array(
						'required' => true,
						'multiple' => true,
						'expanded' => false,
						'label' => 'Lieu ou se trouve le document',
						'class' => 'LarpManager\Entities\Lieu',
						'property' => 'label',
				
				))
				->add('cryptage','choice', array(
						'required' => true,
						'choices' => array(false => 'Non crypté', true => 'Crypté'),
						'label' => 'Indiquez si le document est crypté',
						'attr' => array(
							'help' => 'Un document crypté est rédigé dans la langue indiqué, mais le joueur doit le décrypter de lui-même',
						)
				))
				->add('description','textarea', array(
					'required' => true,
					'attr' => array(
						'class'=> 'tinymce',
						'rows' => 9),
				))
				->add('statut','text', array(
						'required' => false,
				))
				->add('document','file', array(
					'label' => 'Choisissez votre fichier',
					'required' => true,
					'mapped' => false,
				));
	}
	
	/**
	 * Définition de la classe d'entité concernée
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Document',
		));
	}
	
	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'document';
	}
}