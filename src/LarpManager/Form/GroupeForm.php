<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use LarpManager\Form\Type\ClasseType;

class GroupeForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text')
				->add('numero','integer', array(
						'required' => true,
				))
				->add('description','textarea', array(
						'required' => false,
				))
				->add('region','entity', array(
						'label' => 'Fief',
						'required' => false,
						'class' => 'LarpManager\Entities\Region',
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
				->add('classeOpen','integer', array(
						'label' => "Nombre de place ouverte",
						'required' => false,
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

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Groupe',
		));
	}

	public function getName()
	{
		return 'groupe';
	}
}
