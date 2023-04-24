<?php

namespace LarpManager\Services\Manager\Competence;

use LarpManager\Entities\Level;
use LarpManager\Entities\PersonnageTrigger;

class LitteratureHandler extends CompetenceHandler
{
    protected bool $hasBonus = true;

    public function give(): void
    {
        // Todo filter if personnage know all of them or will reach more than exists
        $this->applyRules(
            [
                // 2 langues communes supplémentaires de son choix
                Level::NIVEAU_1 => [PersonnageTrigger::TAG_LANGUE_COURANTE => 2],
                Level::NIVEAU_2 => [
                    //  Sait parler, lire et écrire trois autres langues vivantes (courante ou commune) de son choix.
                    PersonnageTrigger::TAG_LANGUE_COURANTE => 3,
                    // il obtient aussi la possibilité de choisir un sort de niveau 1
                    PersonnageTrigger::TAG_SORT_APPRENTI => 1,
                    // il obtient aussi la possibilité de choisir une potion de niveau 1
                    PersonnageTrigger::TAG_ALCHIMIE_APPRENTI => 1,
                ],
                Level::NIVEAU_3 => [
                    // Sait parler, lire et écrire un langage ancien ainsi que trois autres langues vivantes
                    // (courante ou commune) de son choix ainsi qu'une langue ancienne
                    PersonnageTrigger::TAG_LANGUE_COURANTE => 3,
                    PersonnageTrigger::TAG_LANGUE_ANCIENNE => 1,
                    // il obtient aussi la possibilité de choisir un sort et une potion de niveau 2
                    PersonnageTrigger::TAG_SORT_INITIE => 1,
                    // il obtient aussi la possibilité de choisir une potion de niveau 2
                    PersonnageTrigger::TAG_ALCHIMIE_INITIE => 1,
                ],
                Level::NIVEAU_4 => [
                    // Sait parler, lire et écrire un langage ancien ainsi que trois autres langues vivantes
                    // (courante ou commune) de son choix ainsi qu'une langue ancienne
                    PersonnageTrigger::TAG_LANGUE_COURANTE => 3,
                    PersonnageTrigger::TAG_LANGUE_ANCIENNE => 1,
                    // il obtient aussi la possibilité de choisir un sort et une potion de niveau 2
                    PersonnageTrigger::TAG_SORT_EXPERT => 1,
                    // il obtient aussi la possibilité de choisir une potion de niveau 2
                    PersonnageTrigger::TAG_ALCHIMIE_EXPERT => 1,
                ],
            ]
        );
    }
}