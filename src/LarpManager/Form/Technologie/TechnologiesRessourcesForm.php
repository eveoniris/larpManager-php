<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace LarpManager\Form\Technologie;

use LarpManager\Entities\Ressource;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use LarpManager\Entities\TechnologiesRessources;

/**
 * LarpManager\Form\Groupe\TechnologieForm
 *
 * @author Gérald
 */
class TechnologiesRessourcesForm extends AbstractType
{
    /**
     * Contruction du formulaire
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ressource', EntityType::class, array(
                    'class' => Ressource::class,
                    'property' => 'label',
                    'label' => 'Ressource requise'
        ))
                ->add('quantite', ChoiceType::class, array(
                    'choices' => array(
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5,
                        '6' => 6,
                        '7' => 7),
                    'label' => 'Quantité'
                ))
                ->add('submit', SubmitType::class, array('label' => 'Valider'));
    }

    /**
     * Définition de l'entité concernée
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TechnologiesRessources::class,
        ));
    }

    /**
     * Nom du formulaire
     */
    public function getName()
    {
        return 'technologiesRessources';
    }
}
