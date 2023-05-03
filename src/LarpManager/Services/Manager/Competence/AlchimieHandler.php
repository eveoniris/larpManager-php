<?php

namespace LarpManager\Services\Manager\Competence;

use LarpManager\Entities\Level;
use LarpManager\Entities\PersonnageTrigger;

class AlchimieHandler extends CompetenceHandler
{
    protected bool $hasBonus = true;

    public function give(): void
    {
        // TODO FILTER if personnage know all
        $this->applyRules(
            [
                // le personnage doit choisir 2 potions de niveau apprenti
                Level::NIVEAU_1 => [PersonnageTrigger::TAG_ALCHIMIE_APPRENTI => 2],
                // le personnage doit choisir 1 potion de niveau initie et 1 potion de niveau apprenti
                Level::NIVEAU_2 => [
                    PersonnageTrigger::TAG_ALCHIMIE_APPRENTI => 1,
                    PersonnageTrigger::TAG_ALCHIMIE_INITIE => 1
                ],
                // le personnage doit choisir 1 potion de niveau expert
                Level::NIVEAU_3 => [PersonnageTrigger::TAG_ALCHIMIE_EXPERT => 1],
                // le personnage doit choisir 1 potion de niveau maitre
                Level::NIVEAU_4 => [PersonnageTrigger::TAG_ALCHIMIE_MAITRE => 1],
            ]
        );
    }
}