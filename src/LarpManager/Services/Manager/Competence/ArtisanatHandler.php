<?php

namespace LarpManager\Services\Manager\Competence;

use LarpManager\Entities\Level;
use LarpManager\Entities\PersonnageTrigger;

class ArtisanatHandler extends CompetenceHandler
{
    protected bool $hasBonus = true;

    public function give(): void
    {
        $this->applyRules(
            [
                // le personnage doit choisir 1 technologie
                Level::NIVEAU_3 => [PersonnageTrigger::TAG_TECHNOLOGIE => 1],
                Level::NIVEAU_4 => [PersonnageTrigger::TAG_TECHNOLOGIE => 1],
            ]
        );
    }
}