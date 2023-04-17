<?php

namespace LarpManager\Services\Manager\Competence;

use LarpManager\Entities\Level;
use LarpManager\Entities\PersonnageTrigger;

class PretriseHandler extends CompetenceHandler
{
    protected bool $hasBonus = true;

    public function validateApprendre(): void
    {
        // le personnage doit avoir une religion au niveau fervent ou fanatique
        if (
            !$this->personnage->isCreation() // Todo voir pour forcer le choix de religion et niveau si la classe offre Prêtrise
            && !$this->personnage->isFervent() && !$this->personnage->isFanatique()
        ) {
            $this->addError('Pour obtenir la compétence Prêtrise, vous devez être FERVENT ou FANATIQUE');
        }
    }

    /**
     * Ajoute toutes les prières de niveau de sa compétence
     * liées aux sphères de sa religion fervente ou fanatique.
     */
    public function give(): void
    {
        // ajoute toutes les prières de niveau de sa compétence liées aux sphères de sa religion fervente ou fanatique
        $religion = $this->personnage->getMainReligion();

        if (!$religion) {
            return;
        }

        foreach ($religion->getSpheres() as $sphere) {
            foreach ($sphere->getPrieres() as $priere) {
                if (
                    !$this->personnage->hasPriere($priere)
                    && $priere->getNiveau() === $this->competence->getLevel()->getId()
                ) {
                    $priere->addPersonnage($this->personnage);
                    $this->personnage->addPriere($priere);
                }
            }
        }

        $this->applyRules(
            [
                Level::NIVEAU_2 => [PersonnageTrigger::TAG_PRETRISE_INITIE => 3],
                Level::NIVEAU_3 => [PersonnageTrigger::TAG_PRETRISE_INITIE => 3],
                Level::NIVEAU_4 => [PersonnageTrigger::TAG_PRETRISE_INITIE => 3],
            ]
        );
    }
}