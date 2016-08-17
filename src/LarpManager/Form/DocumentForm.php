<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use LarpManager\Repository\LangueRepository;

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
					'attr' => array(
						'help' => 'Le code d\'un document permet de l\'identifier rapidement. Il se construit de la manière suivante : L3_DJ_TE_005. L3 correspond à l\'opus de création. DJ correspond à Document en Jeu. TE correspond à TExte. 005 correspond à son numéro (suivez la numérotation des documents déjà créé)',	
					),
				))
				->add('titre','text', array(
						'required' => true,
				))
				->add('auteur','text', array(
						'required' => true,
    					'empty_data'  => null,
						'attr' => array(
							'help' => 'Indiquez l\'auteur (en jeu) du document. Cet auteur est soit un personnage fictif (p.e. le célébre poète Archibald) ou l\'un des personnage joué par un joueur',
						)
				))
				->add('langues','entity', array(
						'required' => true,
						'multiple' => true,
						'expanded' => true,
						'label' => 'Langues dans lesquelles le document est rédigé',
						'class' => 'LarpManager\Entities\Langue',
						'query_builder' => function(LangueRepository $er) {
							return $er->createQueryBuilder('l')->orderBy('l.label', 'ASC');
						},
						'property' => 'label',
						'attr' => array(
								'help' => 'Vous pouvez choisir une ou plusieurs langues',
						)
				))
				->add('cryptage','choice', array(
						'required' => true,
						'choices' => array(false => 'Non crypté', true => 'Crypté'),
						'label' => 'Indiquez si le document est crypté',
						'attr' => array(
							'help' => 'Un document crypté est rédigé dans la langue indiqué, mais le joueur doit le décrypter de lui-même (p.e rédigé en aquilonien, mais utilisant un code type césar)',
						)
				))
				->add('description','textarea', array(
						'required' => true,
						'attr' => array(
							'class'=> 'tinymce',
							'rows' => 9,
							'help' => 'Une courte description du document permet d\'éviter de télécharger et d\'ouvrir le document pour comprendre quel est son contenu.'
						),
				))
				->add('statut','text', array(
						'required' => false,
						'attr' => array(
							'help' => 'Une courte description du document permet d\'éviter de télécharger et d\'ouvrir le document pour comprendre quel est son contenu.',
						),
				))
				->add('impression','choice', array(
						'required' => false,
						'choices' => array(false => 'Non imprimé', true => 'Imprimé'),
						'label' => 'Indiquez si le document a été imprimé',
						'attr' => array(
							'help' => 'Le responsable des documents devra indiqué pour chacun des documents s\ils ont été imprimés ou pas.',
						),
				))
				->add('document','file', array(
						'label' => 'Choisissez votre fichier',
						'required' => true,
						'mapped' => false,
						'attr' => array(
							'help' => 'Téléversez le fichier PDF correspondant à votre document.',
						),
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