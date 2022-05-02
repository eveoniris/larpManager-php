<?php

namespace LarpManager\Form\Lignee;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\LigneeForm
 * Formulaire de mis à jour de lignée
 * @author gerald
 *
 */
class LigneeForm extends AbstractType
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
                    'attr' => array(
                        'placeholder' => 'Nom de la lignée'
                     )
            )
        )
            ->add('description', 'textarea', array(
                    'required' => false,
                    'attr' => array(
                            'placeholder' => 'Description de la lignée (aperçu et/ou effet de jeu)'
                    )
            ));
    }

    /**
     * Définition de l'entité concernée
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
        return 'ligneeUpdate';
    }
}