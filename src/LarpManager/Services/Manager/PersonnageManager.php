<?php

namespace LarpManager\Services\Manager;

use LarpManager\Entities\Domaine;
use LarpManager\Entities\ExperienceUsage;
use LarpManager\Entities\Langue;
use LarpManager\Entities\Sort;
use LarpManager\Services\Manager\Competence\AbstractCompetenceHandler;
use LarpManager\Services\Manager\Competence\AlchimieHandler;
use LarpManager\Services\Manager\Competence\ArtisanatHandler;
use LarpManager\Services\Manager\Competence\CompetenceHandler;
use LarpManager\Services\Manager\Competence\ICompetenceHandler;
use LarpManager\Services\Manager\Competence\LitteratureHandler;
use LarpManager\Services\Manager\Competence\MagieHandler;
use LarpManager\Services\Manager\Competence\NoblesseHandler;
use LarpManager\Services\Manager\Competence\PretriseHandler;
use Silex\Application;
use Doctrine\Common\Collections\ArrayCollection;
use LarpManager\Entities\Personnage;
use LarpManager\Entities\CompetenceFamily;
use LarpManager\Entities\Competence;
use LarpManager\Entities\Religion;
use LarpManager\Services\Personnage\PersonnageSearchHandler;

/**
 * LarpManager\PersonnageManager
 *
 * @author kevin
 */
class PersonnageManager
{
    /** @var \Silex\Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Retourne une nouvelle instance du gestionnaire de recherche de personnage
     */
    public function getSearchHandler(): PersonnageSearchHandler
    {
        return new PersonnageSearchHandler($this->app);
    }

    /**
     * Stock le personnage courant de la session
     *
     * @param int|Personnage $personnage
     */
    public function setCurrentPersonnage(/*int|Personnage*/ $personnage): void
    {
        if ($personnage instanceof Personnage) {
            $this->app['session']->set('personnageId', $personnage->getId());

            return;
        }
        $this->app['session']->set('personnageId', $personnage);
    }

    /**
     * Récupére le personnage courant de la session
     */
    public function getCurrentPersonnage(): ?Personnage
    {
        $personnageId = $this->app['session']->get('personnageId');
        if ($personnageId) {
            return $this->app['converter.personnage']->convert($personnageId);
        }

        return null;
    }

    /**
     * Reset le personnage courant
     */
    public function resetCurrentPersonnage(): void
    {
        $this->app['session']->set('personnageId', null);
    }

    /**
     * Ajoute les compétences offertes à la création d'une classe et confère leurs bonus
     */
    public function addClasseCompetencesFamilyCreation(Personnage $personnage): ?CompetenceHandler
    {
        $personnage->setIsCreation(true);

        // ajout des compétences acquises à la création
        foreach ($personnage->getClasse()->getCompetenceFamilyCreations() as $competenceFamily) {
            if ($firstCompetence = $competenceFamily->getFirstCompetence()) {
                $competenceHandler = $this->addCompetence($personnage, $firstCompetence, true);
                if ($competenceHandler->hasErrors()) {
                    return $competenceHandler;
                }
            }
        }

        return null;
    }

    /**
     * Calcul le cout d'une compétence en fonction de la classe du personnage
     *
     * @param Personnage $personnage
     * @param Competence $competence
     */
    public function getCompetenceCout(Personnage $personnage, Competence $competence)
    {
        return $this->getCompetenceHandler($personnage, $competence)->getCompetenceCout();
    }

    /**
     * Ajoute une compétence à un personnage existant
     *
     * Si cela est impossible remonte un tableau d'erreur
     */
    public function addCompetence(Personnage $personnage, Competence $competence, bool $gratuite = false): CompetenceHandler
    {
        return $this->getCompetenceHandler($personnage, $competence)
            ->addCompetence($gratuite ? ICompetenceHandler::COUT_GRATUIT : ICompetenceHandler::COUT_DEFAUT);
    }

    /**
     * Fournis le handler qui attribut les bonus d'une compétence
     */
    public function getCompetenceHandler(Personnage $personnage, Competence $competence): CompetenceHandler
    {
        switch ($competence->getCompetenceFamily()->getLabel()) {
            case Competence::PRETRISE:
                return new PretriseHandler($competence, $personnage, $this->app);
            case Competence::NOBLESSE:
                return new NoblesseHandler($competence, $personnage, $this->app);
            case Competence::ALCHIMIE:
                return new AlchimieHandler($competence, $personnage, $this->app);
            case Competence::MAGIE:
                return new MagieHandler($competence, $personnage, $this->app);
            case Competence::ARTISANAT:
                return new ArtisanatHandler($competence, $personnage, $this->app);
            case Competence::LITTERATURE:
                return new LitteratureHandler($competence, $personnage, $this->app);
            default:
                return new CompetenceHandler($competence, $personnage, $this->app);
        }
    }

    /**
     * Fourni le score de pugilat du personnage
     */
    public function pugilat(Personnage $personnage): int
    {
        return $personnage->getPugilat();
    }

    /**
     * Fourni le titre du personnage en fonction de sa renommée
     */
    public function titre(Personnage $personnage): ?string
    {

        $result = null;
        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Titre');
        $titres = $repo->findByRenomme();
        foreach ($titres as $titre) {
            if ($personnage->getRenomme() >= $titre->getRenomme()) {
                $result = $titre;
            }
        }

        return $result;
    }

    /**
     * Indique si un personnage connait une famille de competence
     */
    public function knownCompetenceFamily(Personnage $personnage, CompetenceFamily $competenceFamily): bool
    {
        $competences = $personnage->getCompetences();

        foreach ($competences as $competence) {
            if ($competence->getCompetenceFamily() === $competenceFamily) {
                return true;
            }
        }

        return false;
    }

    /**
     * Indique si un personnage connait une religion
     */
    public function knownReligion(Personnage $personnage, Religion $religion): bool
    {
        $personnageReligions = $personnage->getPersonnagesReligions();

        foreach ($personnageReligions as $personnageReligion) {
            if ($personnageReligion->getReligion() === $religion) {
                return true;
            }
        }
        return false;
    }

    /**
     * Fourni la liste des premières compétences de chaque famille inconnues d'un personnage
     * @param Personnage $personnage
     * @return ArrayCollection|Competence[] $competenceNiveaux
     */
    public function getUnknownCompetences(Personnage $personnage): ArrayCollection
    {
        $unknownCompetences = new ArrayCollection();

        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\CompetenceFamily');
        $competenceFamilies = $repo->findAll();

        foreach ($competenceFamilies as $competenceFamily) {
            if (!$this->knownCompetenceFamily($personnage, $competenceFamily)) {
                $competence = $competenceFamily->getFirstCompetence();
                if ($competence) {
                    $unknownCompetences->add($competence);
                }
            }
        }

        return $unknownCompetences;
    }

    /**
     * Retourne la liste des toutes les religions inconnues d'un personnage
     * @return ArrayCollection|Religion[] $competenceNiveaux
     */
    public function getAvailableDescriptionReligion(Personnage $personnage): ArrayCollection
    {
        $availableDescriptionReligions = new ArrayCollection();

        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Religion');
        $religions = $repo->findAll();

        foreach ($religions as $religion) {
            if (!$personnage->getReligions()->contains($religion)) {
                $availableDescriptionReligions[] = $religion;
            }
        }

        return $availableDescriptionReligions;
    }

    /**
     * Récupére la liste des toutes les compétences accessibles pour un personnage
     *
     * @param Personnage $personnage
     * @return ArrayCollection|Competence[] $competenceNiveaux
     */
    public function getAvailableCompetences(Personnage $personnage): ArrayCollection
    {
        $availableCompetences = new ArrayCollection();

        // les compétences de niveau supérieur sont disponibles.
        $competences = $personnage->getCompetences();
        foreach ($competences as $competence) {
            $nextCompetence = $competence->getNext();
            if ($nextCompetence && !$personnage->getCompetences()->contains($nextCompetence)) {
                $availableCompetences->add($nextCompetence);
            }
        }

        // les compétences inconnues du personnage sont disponibles au niveau 1
        $competences = $this->getUnknownCompetences($personnage);

        foreach ($competences as $competence) {
            $availableCompetences->add($competence);
        }

        // trie des competences disponibles
        $iterator = $availableCompetences->getIterator();
        $iterator->uasort(static fn($a, $b) => $a->getLabel() <=> $b->getLabel());

        return new ArrayCollection(iterator_to_array($iterator));
    }

    /**
     * Trouve toutes les langues non connues d'un personnage en fonction du niveau de diffusion voulu
     * @param Personnage $personnage
     * @param int $diffusion Langue::DIFFUSION_*
     */
    public function getAvailableLangues(Personnage $personnage, int $diffusion): ArrayCollection
    {
        $availableLangues = new ArrayCollection();

        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Langue');
        $langues = $repo->findBy(array(), array('label' => 'ASC'));

        foreach ($langues as $langue) {
            // On ne montre pas les langues secretes
            if ($langue->getSecret() === Langue::SECRET_VISIBLE) {
                // Le niveau Rare est indépendant des autres
                if ($diffusion === Langue::DIFFUSION_RARE) {
                    if ($langue->getDiffusion() === $diffusion
                        && !$personnage->isKnownLanguage($langue)) {
                        $availableLangues[] = $langue;
                    }
                } // Le niveau de diffusion confère les langues des niveaux suivants
                else if ($langue->getDiffusion() >= $diffusion
                    && !$personnage->isKnownLanguage($langue)) {
                    $availableLangues[] = $langue;
                }
            }
        }

        return $availableLangues;
    }

    /**
     * Trouve tous les sorts non connus d'un personnage en fonction du niveau du sort
     * @return ArrayCollection|Sort[]
     */
    public function getAvailableSorts(Personnage $personnage, $niveau): ArrayCollection
    {
        $availableSorts = new ArrayCollection();

        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Sort');
        $sorts = $repo->findByNiveau($niveau);

        foreach ($sorts as $sort) {
            if (!$personnage->isKnownSort($sort)) {
                $availableSorts[] = $sort;
            }
        }

        return $availableSorts;
    }

    /**
     * Trouve tous les domaines de magie non connus d'un personnage
     * @return ArrayCollection|Domaine[]
     */
    public function getAvailableDomaines(Personnage $personnage): ArrayCollection
    {
        $availableDomaines = new ArrayCollection();

        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Domaine');
        $domaines = $repo->findAll();

        foreach ($domaines as $domaine) {
            if (!$personnage->isKnownDomaine($domaine)) {
                $availableDomaines[] = $domaine;
            }
        }

        return $availableDomaines;
    }

    /**
     * Récupére la liste de toutes les religions non connues du personnage
     * @return ArrayCollection|Religion[]
     */
    public function getAvailableReligions(Personnage $personnage): ArrayCollection
    {
        $availableReligions = new ArrayCollection();

        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Religion');
        $religions = $repo->findAllPublicOrderedByLabel();

        foreach ($religions as $religion) {
            if (!$this->knownReligion($personnage, $religion)) {
                $availableReligions->add($religion);
            }
        }

        return $availableReligions;
    }

    /**
     * Récupére la liste de toutes les religions non connue du personnage, vue admin
     * @return ArrayCollection|Religion[]
     */
    public function getAdminAvailableReligions(Personnage $personnage): ?ArrayCollection
    {
        // Pour le moment aucune différence entre vue user et vue admin
        return $this->getAvailableReligions($personnage);
    }

    /**
     * Fourni la dernière compétence acquise par un presonnage
     */
    public function getLastCompetence(Personnage $personnage): ?Competence
    {
        $competence = null;
        $operationDate = null;

        foreach ($personnage->getExperienceUsages() as $experienceUsage) {
            if ($personnage->getCompetences()->contains($experienceUsage->getCompetence())) {
                if (!$operationDate || $operationDate < $experienceUsage->getOperationDate()) {
                    $operationDate = $experienceUsage->getOperationDate();
                    $competence = $experienceUsage->getCompetence();
                }
            }
        }

        return $competence;
    }

    /**
     * Trouve toutes les technologies non connues d'un personnage
     */
    public function getAvailableTechnologies(Personnage $personnage): ArrayCollection
    {
        $availableTechnologies = new ArrayCollection();

        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Technologie');
        $technologies = $repo->findPublicOrderedByLabel();

        foreach ($technologies as $technologie) {
            if (!$personnage->isKnownTechnologie($technologie)) {
                $availableTechnologies[] = $technologie;
            }
        }

        return $availableTechnologies;
    }
}