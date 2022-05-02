<?php

namespace LarpManager\Form\Lignee;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\LigneeUpdateForm
 * Formulaire de mis à jour de lignée
 * @author gerald
 *
 */
class LigneeUpdateForm extends AbstractType
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
                    'required' => true
            )
        )
            ->add('description', 'textarea', array(
                    'required' => false
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