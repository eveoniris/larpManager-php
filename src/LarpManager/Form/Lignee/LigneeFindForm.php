<?php

namespace LarpManager\Form\Lignee;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\LigneeFindForm
 *
 * @author gerald
 *
 */
class LigneeFindForm extends AbstractType
{
    /**
     * Construction du formulaire
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', TextType::class, array(
            'required' => true,
            'attr' => array(
                'placeholder' => 'Votre recherche',
            )
        ))
            ->add('type', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'nom' => 'Nom de la lignée',
                    'id' => 'ID de la lignée',
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
        return 'ligneeFind';
    }
}