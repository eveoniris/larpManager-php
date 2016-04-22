<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\PersonnageOriginForm
 *
 * @author kevin
 *
 */
class PersonnageOriginForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('territoire','entity', array(
					'required' => true,
					'label' => 'Votre origine',
					'class' => 'LarpManager\Entities\Territoire',
					'property' => 'nom',
					'query_builder' => function(\LarpManager\Repository\TerritoireRepository $er) {
						$qb = $er->createQueryBuilder('t');
						$qb->andWhere('t.territoire IS NULL');
						$qb->orderBy('t.nom', 'ASC');
						return $qb;
					}
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
		return 'personnageOrigin';
	}
}