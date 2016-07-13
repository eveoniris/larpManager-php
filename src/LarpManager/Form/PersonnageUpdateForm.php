<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\PersonnageUpdateForm
 *
 * @author kevin
 *
 */
class PersonnageUpdateForm extends AbstractType
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
		$builder->add('nom','text', array(
					'required' => true,
					'label' => '' 
				))
				->add('surnom','text', array(
						'required' => false,
						'label' => ''
				))
				->add('age','entity', array(
						'required' => true,
						'label' => '',
						'class' => 'LarpManager\Entities\Age',
						'property' => 'label',
				))
				->add('genre','entity', array(
						'required' => true,
						'label' => '',
						'class' => 'LarpManager\Entities\Genre',
						'property' => 'label',
				))
				->add('territoire','entity', array(
						'required' => true,
						'label' => 'Origine du personnage',
						'class' => 'LarpManager\Entities\Territoire',
						'property' => 'nom',
						'query_builder' => function(\LarpManager\Repository\TerritoireRepository $er) {
							$qb = $er->createQueryBuilder('t');
							$qb->andWhere('t.territoire IS NULL');
							return $qb;
						}
				))
				->add('intrigue','choice', array(
					'required' => true,
					'choices' => array(true => 'Oui', false => 'Non'),
					'label' => 'Participer aux intrigues'
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
		return 'personnageUpdate';
	}
}