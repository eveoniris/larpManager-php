<?php

namespace LarpManager\Services\Manager\Competence;

use LarpManager\Entities\Level;
use LarpManager\Entities\PersonnageTrigger;
use LarpManager\Repository\SortRepository;

class MagieHandler extends CompetenceHandler
{
    protected bool $hasBonus = true;

    public function canGetBonus(): bool
    {
        // TODO by competence LEVEL
        // Todo Domaine magie ?
        $personnageNbSorts = $this->personnage->getSorts()->count();
        $availableSorts = $this->app['orm.em']->getRepository('\LarpManager\Entities\Sort')->count();

        // Plus de sorts à apprendre
        if ($personnageNbSorts >= $availableSorts) {
            return false;
        }

        return parent::canGetBonus();
    }

    public function give(): void
    {
        // TODO filter Domaine if Personnage know all domaine;
        // TODO Filter SORT tag if Personnage know all Sort of given Level
        $this->applyRules([
            // le personnage doit choisir un domaine de magie et un sort de niveau 1
            Level::NIVEAU_1 => [
                PersonnageTrigger::TAG_DOMAINE_MAGIE => 1,
                PersonnageTrigger::TAG_SORT_APPRENTI => 1
            ],
            // le personnage peut choisir un nouveau domaine de magie et un sort de niveau 2
            Level::NIVEAU_2 => [PersonnageTrigger::TAG_SORT_INITIE => 1],
            // il obtient aussi la possibilité de choisir un sort de niveau 3
            Level::NIVEAU_3 => [
                PersonnageTrigger::TAG_DOMAINE_MAGIE => 1,
                PersonnageTrigger::TAG_SORT_EXPERT => 1
            ],
            //  il obtient aussi la possibilité de choisir un sort de niveau 4
            Level::NIVEAU_4 => [PersonnageTrigger::TAG_SORT_MAITRE => 1],
        ]);
    }
}