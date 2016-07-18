<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\PersonnageTransfertForm
 *
 * @author kevin
 *
 */
class PersonnageTransfertForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('participant','entity', array(
					'required' => true,
					'label' => 'Nouveau propriétaire',
					'class' => 'LarpManager\Entities\User',
					'property' => 'identity',
					'mapped' => false,
/*					'query_builder' => function(\LarpManager\Repository\TerritoireRepository $er) {
						$qb = $er->createQueryBuilder('t');
						$qb->andWhere('t.territoire IS NULL');
						$qb->orderBy('t.nom', 'ASC');
						return $qb;
					}*/
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
		return 'personnageTransfert';
	}
}