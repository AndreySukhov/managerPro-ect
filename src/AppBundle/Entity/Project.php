<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project.
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("name")
 */
class Project
{
    /**
     * Идентификтор.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Уникальное название проекта.
     *
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * Источник.
     * Путь до git директории или путь до удалённого репозитория.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $source;

    /**
     * Ветка в проекте.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $branch;

    /**
     * Отслеживаемые файлы.
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProjectFile", mappedBy="project", cascade={"persist", "remove"})
     */
    private $files;

    /**
     * Дата создания.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Дата обновления.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $source
     *
     * @return Project
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $branch
     *
     * @return Project
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Project
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Project
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param ProjectFile $file
     *
     * @return Project
     */
    public function addFile(ProjectFile $file)
    {
        $this->files[] = $file;
        $file->setProject($this);

        return $this;
    }

    /**
     * @param ProjectFile $file
     *
     * @return Project
     */
    public function removeFile(ProjectFile $file)
    {
        $file->setProject(null);
        $this->files->removeElement($file);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
