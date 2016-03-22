<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2016-03-22 16:30:52.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

/**
 * LarpManager\Entities\Message
 *
 * @Entity()
 * @Table(name="message", indexes={@Index(name="fk_message_user1_idx", columns={"auteur"}), @Index(name="fk_message_user2_idx", columns={"destinataire"})})
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"base":"BaseMessage", "extended":"Message"})
 */
class BaseMessage
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string", length=45, nullable=true)
     */
    protected $title;

    /**
     * @Column(name="`text`", type="text", nullable=true)
     */
    protected $text;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $creation_date;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $update_date;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $lu;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="messageRelatedByAuteurs")
     * @JoinColumn(name="auteur", referencedColumnName="id", nullable=false)
     */
    protected $userRelatedByAuteur;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="messageRelatedByDestinataires")
     * @JoinColumn(name="destinataire", referencedColumnName="id", nullable=false)
     */
    protected $userRelatedByDestinataire;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \LarpManager\Entities\Message
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
     * Set the value of title.
     *
     * @param string $title
     * @return \LarpManager\Entities\Message
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of text.
     *
     * @param string $text
     * @return \LarpManager\Entities\Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of creation_date.
     *
     * @param \DateTime $creation_date
     * @return \LarpManager\Entities\Message
     */
    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * Get the value of creation_date.
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Set the value of update_date.
     *
     * @param \DateTime $update_date
     * @return \LarpManager\Entities\Message
     */
    public function setUpdateDate($update_date)
    {
        $this->update_date = $update_date;

        return $this;
    }

    /**
     * Get the value of update_date.
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Set the value of lu.
     *
     * @param boolean $lu
     * @return \LarpManager\Entities\Message
     */
    public function setLu($lu)
    {
        $this->lu = $lu;

        return $this;
    }

    /**
     * Get the value of lu.
     *
     * @return boolean
     */
    public function getLu()
    {
        return $this->lu;
    }

    /**
     * Set User entity related by `auteur` (many to one).
     *
     * @param \LarpManager\Entities\User $user
     * @return \LarpManager\Entities\Message
     */
    public function setUserRelatedByAuteur(User $user = null)
    {
        $this->userRelatedByAuteur = $user;

        return $this;
    }

    /**
     * Get User entity related by `auteur` (many to one).
     *
     * @return \LarpManager\Entities\User
     */
    public function getUserRelatedByAuteur()
    {
        return $this->userRelatedByAuteur;
    }

    /**
     * Set User entity related by `destinataire` (many to one).
     *
     * @param \LarpManager\Entities\User $user
     * @return \LarpManager\Entities\Message
     */
    public function setUserRelatedByDestinataire(User $user = null)
    {
        $this->userRelatedByDestinataire = $user;

        return $this;
    }

    /**
     * Get User entity related by `destinataire` (many to one).
     *
     * @return \LarpManager\Entities\User
     */
    public function getUserRelatedByDestinataire()
    {
        return $this->userRelatedByDestinataire;
    }

    public function __sleep()
    {
        return array('id', 'title', 'text', 'creation_date', 'update_date', 'lu', 'auteur', 'destinataire');
    }
}