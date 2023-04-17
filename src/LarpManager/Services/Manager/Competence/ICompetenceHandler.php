<?php

namespace LarpManager\Services\Manager\Competence;

interface ICompetenceHandler
{

    public const ERR_CODE_XP = 1;
    public const ERR_CODE_LEARN = 2;

    public const COUT_GRATUIT = 0;
    public const COUT_DEFAUT = -1;

    public function hasBonus(): bool;

    public function canGetBonus(): bool;

    public function canLearn(int $cout): bool;

    public function giveBonus(): void;
}