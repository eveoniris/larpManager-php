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

namespace LarpManager\Services\Manager\Competence;

use LarpManager\Entities\Classe;
use LarpManager\Entities\CompetenceFamily;
use LarpManager\Entities\Level;
use LarpManager\Entities\Personnage;
use Silex\Application;
use LarpManager\Entities\Competence;

/**
 * LarpManager\Services\Manager\Competence
 *
 * @see https://github.com/eveoniris/larpManager-php/issues/442 pour extension possible
 *
 * @author Gectou4
 */
abstract class AbstractCompetenceHandler implements ICompetenceHandler
{
    protected Application $app;
    protected Competence $competence;
    protected CompetenceFamily $competenceFamily;
    protected Level $competenceLevel;
    protected Personnage $personnage;
    protected Classe $classe;
    protected array $errors;

    protected bool $hasBonus = false;

    public function __construct(Competence $competence, Personnage $personnage, Application $app)
    {
        // Di
        $this->app = $app;
        $this->competence = $competence;
        $this->personnage = $personnage;

        // Cache object (optimize Query cost)
        $this->classe = $personnage->getClasse();
        $this->competenceFamily = $competence->getCompetenceFamily();
        $this->competenceLevel = $competence->getLevel();

        $this->resetErrors();
    }

    /**
     * Sauvegarde en BDD
     */
    public function save(): void
    {
        $this->app['orm.em']->persist($this->competence);
        $this->app['orm.em']->persist($this->personnage);
        $this->app['orm.em']->flush();
    }

    /**
     * Indique si une class de compétence héritée donne droit à des bonus
     */
    public function hasBonus(): bool
    {
        return $this->hasBonus;
    }

    /**
     * Détermine si le personnage peut recevoir des bonus
     */
    public function canGetBonus(): bool
    {
        return $this->hasBonus();
    }

    /**
     * Indique si une class de compétence héritée peut être apprise
     * Si le cout n'est pas précisé, on prend le cout par défaut de la compétence pour la classe du personnage
     * Si le cout vaut 0 on considère alors que la compétence est gratuite ou offerte
     */
    final public function canLearn(int $cout = ICompetenceHandler::COUT_DEFAUT): bool
    {
        if ($cout < 0) {
            $cout = $this->getCompetenceCout();
        }

        // Si un personnage à un XP négatif. Il ne peut apprendre que des gratuites
        if ($cout > 0 && $this->personnage->getXp() - $cout < 0) {
            $this->addError("Vous n'avez pas suffisamment de points d'expérience pour acquérir cette compétence.");
        }

        $this->validateApprendre();

        return !$this->hasErrors();
    }

    /**
     * Étendre cette méthode et appeler $this->addError() au besoin
     */
    protected function validateApprendre(): void
    {
    }

    /**
     * Calcul le cout d'une compétence en fonction de la classe du personnage
     */
    public function getCompetenceCout(): int
    {
        if ($this->competenceLevel->getIndex() === Level::NIVEAU_1
            && $this->classe->getCompetenceFamilyCreations()->contains($this->competenceFamily)
        ) {
            return 0;
        }

        if ($this->classe->getCompetenceFamilyFavorites()->contains($this->competenceFamily)) {
            return $this->competenceLevel->getCoutFavori();
        }

        if ($this->classe->getCompetenceFamilyNormales()->contains($this->competenceFamily)) {
            return $this->competenceLevel->getCout();
        }

        return $this->competenceLevel->getCoutMeconu();
    }


    /**
     * Donne les bonus de la compétence au personnage
     */
    final public function giveBonus(): void
    {
        if (!$this->canGetBonus()) {
            return;
        }

        $this->give();
    }

    protected function give(): void
    {
        throw new \RuntimeException('Method "give" is not implemented for competence:' . static::class);
    }

    final public function getErrors(): array
    {
        return $this->errors;
    }

    final public function getErrorsAsString(): string
    {
        return implode(', ' . PHP_EOL, $this->errors);
    }

    final public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    final public function addError($error, ?int $code = null): void
    {
        $code ? $this->errors[$code] = $error : $this->errors[] = $error;
    }

    final public function resetErrors(): void
    {
        $this->errors = [];
    }
}