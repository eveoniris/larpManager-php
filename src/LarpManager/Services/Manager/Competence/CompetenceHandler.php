<?php

namespace LarpManager\Services\Manager\Competence;

use LarpManager\Entities\ExperienceUsage;
use LarpManager\Entities\PersonnageTrigger;

class CompetenceHandler extends AbstractCompetenceHandler
{
    /**
     * Consomme les points d'xp d'un personnage et historise l'usage
     * Si le cout vaut 0 alors la compétence a été offerte
     */
    public function consumeXP(int $cout): void
    {
        $this->personnage->setXp($this->personnage->getXp() - $cout);

        $historique = new ExperienceUsage();
        $historique->setOperationDate(new \Datetime('NOW'));
        $historique->setXpUse($cout);
        $historique->setCompetence($this->competence);
        $historique->setPersonnage($this->personnage);
        $this->app['orm.em']->persist($historique);
    }

    public function addCompetence(int $cout = ICompetenceHandler::COUT_DEFAUT): self
    {
        if (!$this->canLearn($cout)) {
            return $this;
        }

        // Pré-Ajout en base
        $this->personnage->addCompetence($this->competence);
        $this->competence->addPersonnage($this->personnage);

        // Consommation d'expérience et historisation
        $this->consumeXP($cout);

        // Attribution des bonus
        $this->giveBonus();

        // Enregistrement en base du lot
        $this->save();

        return $this;
    }

    protected function applyRules(array $rules): void
    {
        $rule = $rules[$this->competence->getLevel()->getIndex()] ?? [];
        if (empty($rule)) {
            return;
        }

        foreach ($rule as $tagName => $nb) {
            while ($nb-- > 0) {
                $trigger = new PersonnageTrigger();
                $trigger->setPersonnage($this->personnage);
                $trigger->setTag($tagName);
                $trigger->setDone(false);
                $this->app['orm.em']->persist($trigger);
            }
        }
    }
}