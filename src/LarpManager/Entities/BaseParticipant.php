<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 3.0.3 (doctrine2-annotation) on 2019-10-02 19:08:34.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Entities\Participant
 *
 * @Entity()
 * @Table(name="participant", indexes={@Index(name="fk_joueur_gn_gn1_idx", columns={"gn_id"}), @Index(name="fk_joueur_gn_user1_idx", columns={"user_id"}), @Index(name="fk_participant_personnage_secondaire1_idx", columns={"personnage_secondaire_id"}), @Index(name="fk_participant_personnage1_idx", columns={"personnage_id"}), @Index(name="fk_participant_billet1_idx", columns={"billet_id"}), @Index(name="fk_participant_groupe_gn1_idx", columns={"groupe_gn_id"})})
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BaseParticipant", "extended":"Participant"})
 */
class BaseParticipant
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="datetime")
     */
    protected $subscription_date;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $billet_date;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $valide_ci_le;

    /**
     * @OneToMany(targetEntity="GroupeGn", mappedBy="participant")
     * @JoinColumn(name="id", referencedColumnName="responsable_id", nullable=false)
     */
    protected $groupeGns;

    /**
     * @OneToMany(targetEntity="ParticipantHasRestauration", mappedBy="participant", cascade={"persist", "remove"})
     * @JoinColumn(name="id", referencedColumnName="participant_id", nullable=false)
     */
    protected $participantHasRestaurations;

    /**
     * @OneToMany(targetEntity="Reponse", mappedBy="participant")
     * @JoinColumn(name="id", referencedColumnName="participant_id", nullable=false)
     */
    protected $reponses;

    /**
     * @ManyToOne(targetEntity="Gn", inversedBy="participants", cascade={"persist"})
     * @JoinColumn(name="gn_id", referencedColumnName="id", nullable=false)
     */
    protected $gn;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="participants")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="PersonnageSecondaire", inversedBy="participants")
     * @JoinColumn(name="personnage_secondaire_id", referencedColumnName="id")
     */
    protected $personnageSecondaire;

    /**
     * @ManyToOne(targetEntity="Personnage", inversedBy="participants")
     * @JoinColumn(name="personnage_id", referencedColumnName="id")
     */
    protected $personnage;

    /**
     * @ManyToOne(targetEntity="Billet", inversedBy="participants")
     * @JoinColumn(name="billet_id", referencedColumnName="id")
     */
    protected $billet;

    /**
     * @ManyToOne(targetEntity="GroupeGn", inversedBy="participants")
     * @JoinColumn(name="groupe_gn_id", referencedColumnName="id")
     */
    protected $groupeGn;

    /**
     * @ManyToMany(targetEntity="Potion", inversedBy="personnages")
     * @JoinTable(name="participant_potions_depart",
     *     joinColumns={@JoinColumn(name="participant_id", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@JoinColumn(name="potion_id", referencedColumnName="id", nullable=false)}
     * )
     * @OrderBy({"label" = "ASC", "niveau" = "ASC",})
     */
    protected $potions_depart;

    public function __construct()
    {
        $this->groupeGns = new ArrayCollection();
        $this->participantHasRestaurations = new ArrayCollection();
        $this->reponses = new ArrayCollection();
        $this->potions_depart = new ArrayCollection();        
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\Participant
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of subscription_date.
     *
     * @param \DateTime $subscription_date
     * @return \LarpManager\Entities\Participant
     */
    public function setSubscriptionDate($subscription_date)
    {
        $this->subscription_date = $subscription_date;

        return $this;
    }

    /**
     * Get the value of subscription_date.
     *
     * @return \DateTime
     */
    public function getSubscriptionDate()
    {
        return $this->subscription_date;
    }

    /**
     * Set the value of billet_date.
     *
     * @param \DateTime $billet_date
     * @return \LarpManager\Entities\Participant
     */
    public function setBilletDate($billet_date)
    {
        $this->billet_date = $billet_date;

        return $this;
    }

    /**
     * Get the value of billet_date.
     *
     * @return \DateTime
     */
    public function getBilletDate()
    {
        return $this->billet_date;
    }

    /**
     * Set the value of valide_ci_le.
     *
     * @param \DateTime $valide_ci_le
     * @return \LarpManager\Entities\Participant
     */
    public function setValideCiLe($valide_ci_le)
    {
        $this->valide_ci_le = $valide_ci_le;

        return $this;
    }

    /**
     * Get the value of valide_ci_le.
     *
     * @return \DateTime
     */
    public function getValideCiLe()
    {
        return $this->valide_ci_le;
    }

    /**
     * Add GroupeGn entity to collection (one to many).
     *
     * @param \LarpManager\Entities\GroupeGn $groupeGn
     * @return \LarpManager\Entities\Participant
     */
    public function addGroupeGn(GroupeGn $groupeGn)
    {
        $this->groupeGns[] = $groupeGn;

        return $this;
    }

    /**
     * Remove GroupeGn entity from collection (one to many).
     *
     * @param \LarpManager\Entities\GroupeGn $groupeGn
     * @return \LarpManager\Entities\Participant
     */
    public function removeGroupeGn(GroupeGn $groupeGn)
    {
        $this->groupeGns->removeElement($groupeGn);

        return $this;
    }

    /**
     * Get GroupeGn entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupeGns()
    {
        return $this->groupeGns;
    }

    /**
     * Add ParticipantHasRestauration entity to collection (one to many).
     *
     * @param \LarpManager\Entities\ParticipantHasRestauration $participantHasRestauration
     * @return \LarpManager\Entities\Participant
     */
    public function addParticipantHasRestauration(ParticipantHasRestauration $participantHasRestauration)
    {
        $this->participantHasRestaurations[] = $participantHasRestauration;

        return $this;
    }

    /**
     * Remove ParticipantHasRestauration entity from collection (one to many).
     *
     * @param \LarpManager\Entities\ParticipantHasRestauration $participantHasRestauration
     * @return \LarpManager\Entities\Participant
     */
    public function removeParticipantHasRestauration(ParticipantHasRestauration $participantHasRestauration)
    {
        $this->participantHasRestaurations->removeElement($participantHasRestauration);

        return $this;
    }

    /**
     * Get ParticipantHasRestauration entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipantHasRestaurations()
    {
        return $this->participantHasRestaurations;
    }

    /**
     * Add Reponse entity to collection (one to many).
     *
     * @param \LarpManager\Entities\Reponse $reponse
     * @return \LarpManager\Entities\Participant
     */
    public function addReponse(Reponse $reponse)
    {
        $this->reponses[] = $reponse;

        return $this;
    }

    /**
     * Remove Reponse entity from collection (one to many).
     *
     * @param \LarpManager\Entities\Reponse $reponse
     * @return \LarpManager\Entities\Participant
     */
    public function removeReponse(Reponse $reponse)
    {
        $this->reponses->removeElement($reponse);

        return $this;
    }

    /**
     * Get Reponse entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReponses()
    {
        return $this->reponses;
    }

    /**
     * Set Gn entity (many to one).
     *
     * @param \LarpManager\Entities\Gn $gn
     * @return \LarpManager\Entities\Participant
     */
    public function setGn(Gn $gn = null)
    {
        $this->gn = $gn;

        return $this;
    }

    /**
     * Get Gn entity (many to one).
     *
     * @return \LarpManager\Entities\Gn
     */
    public function getGn()
    {
        return $this->gn;
    }

    /**
     * Set User entity (many to one).
     *
     * @param \LarpManager\Entities\User $user
     * @return \LarpManager\Entities\Participant
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User entity (many to one).
     *
     * @return \LarpManager\Entities\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set PersonnageSecondaire entity (many to one).
     *
     * @param \LarpManager\Entities\PersonnageSecondaire $personnageSecondaire
     * @return \LarpManager\Entities\Participant
     */
    public function setPersonnageSecondaire(PersonnageSecondaire $personnageSecondaire = null)
    {
        $this->personnageSecondaire = $personnageSecondaire;

        return $this;
    }

    /**
     * Get PersonnageSecondaire entity (many to one).
     *
     * @return \LarpManager\Entities\PersonnageSecondaire
     */
    public function getPersonnageSecondaire()
    {
        return $this->personnageSecondaire;
    }

    /**
     * Set Personnage entity (many to one).
     *
     * @param \LarpManager\Entities\Personnage $personnage
     * @return \LarpManager\Entities\Participant
     */
    public function setPersonnage(Personnage $personnage = null)
    {
        $this->personnage = $personnage;

        return $this;
    }

    /**
     * Get Personnage entity (many to one).
     *
     * @return \LarpManager\Entities\Personnage
     */
    public function getPersonnage()
    {
        return $this->personnage;
    }

    /**
     * Set Billet entity (many to one).
     *
     * @param \LarpManager\Entities\Billet $billet
     * @return \LarpManager\Entities\Participant
     */
    public function setBillet(Billet $billet = null)
    {
        $this->billet = $billet;

        return $this;
    }

    /**
     * Get Billet entity (many to one).
     *
     * @return \LarpManager\Entities\Billet
     */
    public function getBillet()
    {
        return $this->billet;
    }

    /**
     * Set GroupeGn entity (many to one).
     *
     * @param \LarpManager\Entities\GroupeGn $groupeGn
     * @return \LarpManager\Entities\Participant
     */
    public function setGroupeGn(GroupeGn $groupeGn = null)
    {
        $this->groupeGn = $groupeGn;

        return $this;
    }

    /**
     * Get GroupeGn entity (many to one).
     *
     * @return \LarpManager\Entities\GroupeGn
     */
    public function getGroupeGn()
    {
        return $this->groupeGn;
    }

    /**
     * Add Potion entity to collection.
     *
     * @param \LarpManager\Entities\Potion $potion
     * @return \LarpManager\Entities\Participant
     */
    public function addPotionDepart(Potion $potion)
    {
        $this->potions_depart[] = $potion;
        return $this;
    }

    /**
     * Remove Potion entity from collection.
     *
     * @param \LarpManager\Entities\Potion $potion
     * @return \LarpManager\Entities\Participant
     */
    public function removePotionDepart(Potion $potion)
    {
        $this->potions_depart->removeElement($potion);
        return $this;
    }

    /**
     * Get Potion entity collection.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPotionsDepart()
    {
        return $this->potions_depart;
    }    

    public function __sleep()
    {
        return array('id', 'gn_id', 'subscription_date', 'user_id', 'personnage_secondaire_id', 'personnage_id', 'billet_id', 'billet_date', 'groupe_gn_id', 'valide_ci_le');
    }
}