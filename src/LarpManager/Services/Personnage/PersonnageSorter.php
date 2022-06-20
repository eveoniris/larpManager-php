<?php
namespace LarpManager\Services\Personnage;


use LarpManager\Entities\Personnage;
use LarpManager\Services\Utilities;

class PersonnageSorter
{

    public function __construct()
    {}
    
    
    /**
     * Tri du tableau personnages suivant le sortFieldName spécifié, asc ou desc.
     * Le tableau passé en paramètre est directement modifié.
     * Valeurs de nom de tri supportées :
     * - pugilat
     * - heroisme
     * - user
     * - hasAnomalie
     * - status
     * @param array $personnages
     * @param string $sortFieldName
     * @param bool $isAsc
     */
    public static function sort(array &$personnages, string $sortFieldName, bool $isAsc)
    {
        switch ($sortFieldName)
        {
            case 'id':
                $sortByFunctionName = 'sortById';
                break;
            case 'status':
                $sortByFunctionName = 'sortByStatus';
                break;
            case 'nom':
                $sortByFunctionName = 'sortByNom';
                break;
            case 'classe':
                $sortByFunctionName = 'sortByClasse';
                break;
            case 'groupe':
                $sortByFunctionName = 'sortByGroupe';
                break;
            case 'renomme':
                $sortByFunctionName = 'sortByRenomme';
                break;
            case 'pugilat':
                $sortByFunctionName = 'sortByPugilat';
                break;
            case 'heroisme':
                $sortByFunctionName = 'sortByHeroisme';
                break;
            case 'user':
                $sortByFunctionName = 'sortByUser';
                break;
            case 'xp':
                $sortByFunctionName = 'sortByXp';
                break;
            case 'hasAnomalie':
                $sortByFunctionName = 'sortByHasAnomalie';
                break;
            default:
                throw new \Exception('Le champ de tri '.$sortFieldName.' n\'a pas été implémenté');
        }
        if (!$isAsc)
        {
            $sortByFunctionName = $sortByFunctionName.'Desc';
        }
        
        Utilities::stable_uasort($personnages, array('\LarpManager\Services\Personnage\PersonnageSorter', $sortByFunctionName));       
        
    }
    
    /**
     * Tri sur Id
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortById(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getId(), $b->getId());
    }
    
    /**
     * Tri sur Id Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByIdDesc(Personnage $a, Personnage $b)
    {
        return self::sortById($b, $a);
    }
    
    /**
     * Tri sur Classe
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByClasse(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getClasseName(), $b->getClasseName());
    }
    
    /**
     * Tri sur Classe Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByClasseDesc(Personnage $a, Personnage $b)
    {
        return self::sortByClasse($b, $a);
    }
    
    /**
     * Tri sur Groupe
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByGroupe(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getLastParticipantGnGroupeNom(), $b->getLastParticipantGnGroupeNom());
    }
    
    /**
     * Tri sur Groupe Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByGroupeDesc(Personnage $a, Personnage $b)
    {
        return self::sortByGroupe($b, $a);
    }
    
    /**
     * Tri sur Renommée
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByRenomme(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getRenomme(), $b->getRenomme());
    }
    
    /**
     * Tri sur Groupe Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByRenommeDesc(Personnage $a, Personnage $b)
    {
        return self::sortByRenomme($b, $a);
    }
    
    /**
     * Tri sur points d'expérience
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByXp(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getXp(), $b->getXp());
    }
    
    /**
     * Tri sur points d'expérience Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByXpDesc(Personnage $a, Personnage $b)
    {
        return self::sortByXp($b, $a);
    }
    
    /**
     * Tri sur Pugilat
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByPugilat(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getPugilat(), $b->getPugilat());
    }
    
    /**
     * Tri sur Pugilat Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByPugilatDesc(Personnage $a, Personnage $b)
    {
        return self::sortByPugilat($b, $a);
    }
    
    /**
     * Tri sur Heroisme
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByHeroisme(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getHeroisme(), $b->getHeroisme());
    }
    
    /**
     * Tri sur Heroisme Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByHeroismeDesc(Personnage $a, Personnage $b)
    {
        return self::sortByHeroisme($b, $a);
    }
    
    /**
     * Tri sur User Full Name
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByUser(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getUserFullName(), $b->getUserFullName());
    }
    
    /**
     * Tri sur User Full Name Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByUserDesc(Personnage $a, Personnage $b)
    {
        return self::sortByUser($b, $a);
    }
    
    /**
     * Tri sur HasAnomalie
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByHasAnomalie(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->hasAnomalie(), $b->hasAnomalie());
    }
    
    /**
     * Tri sur HasAnomalieDesc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByHasAnomalieDesc(Personnage $a, Personnage $b)
    {
        return self::sortByHasAnomalie($b, $a);
    }
    
    /**
     * Tri sur Status Code
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByStatusCode(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getStatusCode(), $b->getStatusCode());
    }
    
    /**
     * Tri sur Status Code Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByStatusCodeDesc(Personnage $a, Personnage $b)
    {
        return self::sortByStatus($b, $a);
    }
    
    /**
     * Tri sur Status On Active GN
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByStatusOnActiveGn(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getStatusOnActiveGnCode(), $b->getStatusOnActiveGnCode());
    }
    
    /**
     * Tri sur Status On Active GN Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByStatusOnActiveGnDesc(Personnage $a, Personnage $b)
    {
        return self::sortByStatusOnActiveGn($b, $a);
    }
    
    /**
     * Tri sur Nom
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByNom(Personnage $a, Personnage $b)
    {
        return Utilities::sortBy($a->getNom(), $b->getNom());
    }
    
    /**
     * Tri sur Nom Desc
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByNomDesc(Personnage $a, Personnage $b)
    {
        return self::sortByNom($b, $a);
    }
    
    /**
     * Tri sur Status GN, du + récent (+ grand) au - récent (+ petit) puis par nom ASC
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByStatusGn(Personnage $a, Personnage $b)
    {
        $aStatus = $a->getStatusGnCode();
        $bStatus = $b->getStatusGnCode();
        if ($aStatus == $bStatus) {
            return self::sortByNom($a, $b);
        }
        // on prend le statut à l'envers, ici 0 = mort donc on veut plutôt du + grand au + petit
        return ($aStatus > $bStatus) ? -1 : 1;
    }
    
    /**
     * Tri sur Status GN DESC, du - récent (+ petit) au + récent (+ grand) puis par nom DESC
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByStatusGnDesc(Personnage $a, Personnage $b)
    {
        return self::sortByStatusGn($b, $a);
    }
    
    /**
     * Tri sur Last Participant GN Number, du + récent (+ grand) au - récent (+ petit) puis par nom ASC
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByLastParticipantGnNumber(Personnage $a, Personnage $b)
    {
        $aStatus = $a->getLastParticipantGnNumber();
        $bStatus = $b->getLastParticipantGnNumber();
        if ($aStatus == $bStatus) {
            return self::sortByNom($a, $b);
        }
        // on prend le statut à l'envers, ici 0 = mort donc on veut plutôt du + grand au + petit
        return ($aStatus > $bStatus) ? -1 : 1;
    }
    
    /**
     * Tri sur Last Participant GN Number DESC, du - récent (+ petit) au + récent (+ grand) puis par nom DESC
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByLastParticipantGnNumberDesc(Personnage $a, Personnage $b)
    {
        return self::sortByLastParticipantGnNumber($b, $a);
    }
    
    /**
     * Tri sur Status :
     * - d'abord les PJs vivants sur le GN actif,
     * - puis les PNJ,
     * - puis les PJ anciens,
     * - puis les morts,
     * et pour chaque groupe, du + récent gn (+ grand) au - récent (+ petit) puis par nom ASC
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByStatus(Personnage $a, Personnage $b)
    {
        $aStatus = $a->getStatusOnActiveGnCode();
        $bStatus = $b->getStatusOnActiveGnCode();
        
        // si les 2 sont pnj ou les 2 sont morts, on se base sur le gn
        if ($a->isPnj() && $b->isPnj() || !$a->getVivant() && !$b->getVivant())
        {
            return self::sortByLastParticipantGnNumber($a, $b);
        }
        if ($aStatus == $bStatus) {
            return self::sortByStatusGn($a, $b);
        }
        // on prend le statut à l'envers, ici 0 = mort donc on veut plutôt du + grand au + petit
        return ($aStatus > $bStatus) ? -1 : 1;
    }
    
    /**
     * Tri sur Status DESC:
     * - d'abord les morts,
     * - puis les PJ anciens,
     * - puis les PNJ,
     * - puis les PJs vivants sur le GN actif
     * et pour chaque groupe, du - récent gn (+ petit) au + récent (+ grand) puis par nom DESC
     * @param Personnage $a
     * @param Personnage $b
     * @return number
     */
    public static function sortByStatusDesc(Personnage $a, Personnage $b)
    {
        return self::sortByStatus($b,$a);
    }
}

