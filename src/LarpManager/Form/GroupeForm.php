<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use LarpManager\Form\Type\ClasseType;
use LarpManager\Form\Type\GnType;

/**
 * LarpManager\Form\GroupeForm
 *
 * @author kevin
 *
 */
class GroupeForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text')
				->add('numero','integer', array(
						'required' => true,
				))
				->add('description','textarea', array(
						'required' => false,
				))
				->add('territoire','entity', array(
						'label' => 'Territoire',
						'required' => false,
						'class' => 'LarpManager\Entities\Territoire',
						'property' => 'nom',
				))
				->add('code','text', array(
						'required' => false,
				))
				->add('scenariste','entity', array(
						'label' => 'Scénariste',
						'required' => false, 
						'class' => 'LarpManager\Entities\User',
						'property' => 'name',
						'query_builder' => function(EntityRepository $er) {
							$qb = $er->createQueryBuilder('u');
							$qb->where($qb->expr()->orX(
									$qb->expr()->like('u.rights', $qb->expr()->literal('%ROLE_SCENARISTE%')),
									$qb->expr()->like('u.rights', $qb->expr()->literal('%ROLE_ADMIN%'))));
							return $qb;
						}
				))
				->add('jeuStrategique','checkbox', array(
						'label' => "Participe au jeu stratégique ?",
						'required' => false,
				))
				->add('jeuMaritime','checkbox', array(
						'label' => "Participe au jeu maritime ?",
						'required' => false,
				))
				->add('gns', 'entity', array(
						'label' => 'GNs auquel ce groupe participe',
						'multiple' => true,
						'required' => false,
						'class' => 'LarpManager\Entities\Gn',
						'property' => 'label',
				))
				->add('groupeClasses', 'collection', array(
						'label' => "Composition",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new ClasseType()
				));
	}

	/**
	 * Définition de l'entité conercné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Groupe',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'groupe';
	}
}
