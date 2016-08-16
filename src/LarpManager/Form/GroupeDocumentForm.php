<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use LarpManager\Repository\DocumentRepository;


/**
 * LarpManager\Form\GroupeDocumentForm
 *
 * @author kevin
 *
 */
class GroupeDocumentForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('documents','entity', array(
					'label' => "Choisissez les documents possédé par le groupe en début de jeu",
					'multiple' => true,
					'expanded' => true,
					'required' => false,
					'class' => 'LarpManager\Entities\Document',
					'property' => 'identity',
					'query_builder' => function(DocumentRepository $er) {
						return $er->createQueryBuilder('d')->orderBy('d.code', 'ASC');
					},
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
	 * @return string
	 */
	public function getName()
	{
		return 'groupeDocument';
	}
}