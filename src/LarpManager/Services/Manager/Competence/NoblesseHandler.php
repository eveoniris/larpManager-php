<?php

namespace LarpManager\Services\Manager\Competence;

use LarpManager\Entities\RenommeHistory;

class NoblesseHandler extends CompetenceHandler
{
    protected bool $hasBonus = true;

    protected function give(): void
    {
        $competenceLevelId = $this->competence->getLevel()->getId();
        $value = $competenceLevelId + 1;

        if ($value > 1 && $value < 7) {
            $this->personnage->addRenomme($value);
            $renomme_history = new RenommeHistory();
            $renomme_history->setRenomme($value);
            $renomme_history->setExplication(sprintf('Compétence Noblesse niveau %d', $competenceLevelId));
            $renomme_history->setPersonnage($this->personnage);
            $this->app['orm.em']->persist($renomme_history);
        }

        $this->app['orm.em']->flush();
    }
}