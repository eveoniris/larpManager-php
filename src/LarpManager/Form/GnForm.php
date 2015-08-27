<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GnForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text', array(
					'required' => true,	
				))
				->add('description','textarea', array(
					'required' => false,	
				))
				->add('xpCreation','integer', array(
					'label' => 'Point d\'expérience à la création d\'un personnage',
					'required' => false,
				))
				->add('dateDebut','datetime', array(
						'label' => 'Date et heure de début du jeu',
						'required' => false,
				))
				->add('dateFin','datetime', array(
						'label' => 'Date et heure de fin du jeu',
						'required' => false,
				))
				->add('dateInstallationJoueur','datetime',array(
						'label' => 'Date et heure du début de l\'acceuil des joueurs',
						'required' => false,
				))
				->add('dateFinOrga','datetime', array(
						'label' => 'Date limite pour libérer le site',
						'required' => false,
				))
				->add('adresse','textarea', array(
						'label' => 'Adresse du site',
						'required' => false,
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Gn',
		));
	}
	
	public function getName()
	{
		return 'gn';
	}
}