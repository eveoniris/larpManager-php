<?php

namespace LarpManager\Form\Lignee;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\Lignee\LigneeAddMembreForm
 *
 * @author Gérald
 *
 */
class LigneeAddMembreForm extends AbstractType
{
    /**
     * Contruction du formulaire
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('personnage', 'entity', array(
            'required' => true,
            'label' => 'Choisissez le personnage membre',
            'class' => 'LarpManager\Entities\Personnage',
            'query_builder' => function(EntityRepository $er) {
                $qb = $er->createQueryBuilder('p');
                $qb->orderBy('p.nom', 'ASC');
                return $qb;
            },
            'attr' => array(
                'class'	=> 'selectpicker',
                'data-live-search' => "true",
                'placeholder' => 'Nouveau membre',
            ),
            'property' => 'nom',
            'mapped' => false,
            ))

            ->add('parent1', 'entity', array(
            'required' => true,
            'label' => 'Choisissez le parent du membre',
            'class' => 'LarpManager\Entities\Personnage',
            'query_builder' => function(EntityRepository $er) {
                $qb = $er->createQueryBuilder('p');
                $qb->orderBy('p.nom', 'ASC');
                return $qb;
            },
            'attr' => array(
                'class'	=> 'selectpicker',
                'data-live-search' => "true",
                'placeholder' => 'Parent (obligatoire)',
            ),
            'property' => 'nom',
            'mapped' => false,
            ))

            ->add('parent2', 'entity', array(
                'required' => false,
                'label' => 'Choisissez le second parent',
                'class' => 'LarpManager\Entities\Personnage',
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('p');
                    $qb->orderBy('p.nom', 'ASC');
                    return $qb;
                },
                'attr' => array(
                    'class'	=> 'selectpicker',
                    'data-live-search' => "true",
                    'placeholder' => 'Second parent (facultatif)',
                ),
                'property' => 'nom',
                'mapped' => false,
            ))
            ->add('submit','submit', array('label' => "Ajouter"));;
    }

    /**
     * Définition de l'entité concernée
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * Nom du formulaire
     */
    public function getName()
    {
        return 'ligneeAddMembre';
    }
}