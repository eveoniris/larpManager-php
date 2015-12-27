<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\PersonnageForm
 *
 * @author kevin
 *
 */
class PersonnageForm extends AbstractType
{
	/**
	 * Construction du formulaire
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
						'query_builder' => function(EntityRepository $er) {
							$qb = $er->createQueryBuilder('a');
							$qb->andWhere('a.enableCreation = true');
							return $qb;
						}
				))
				->add('genre','entity', array(
						'required' => true,
						'label' => '',
						'class' => 'LarpManager\Entities\Genre',
						'property' => 'label',
				))
				->add('territoire','entity', array(
						'required' => true,
						'label' => 'Votre origine',
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
		return 'personnage';
	}
}