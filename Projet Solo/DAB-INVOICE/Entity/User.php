<?php

namespace App\Entity;

use App\Entity\Trait\ArchivableEntity;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: [ "email" ], message: User::EMAIL_ALREADY_USED)]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    const string ROLE_ADMIN = 'ROLE_ADMIN';
    const string ROLE_USER = 'ROLE_USER';

    const array ROLE_LABEL = [
        self::ROLE_ADMIN => "Administrateur",
    ];
    const string EMAIL_PATTERN = '/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/';
    const string EMAIL_PATTERN_MESSAGE = "L'adresse email n'est pas valide";
    const string PASSWORD_PATTERN = '/^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\p{P}\p{S}]).{10,}$/';
    const string PASSWORD_PATTERN_HELP = "Le mot de passe doit contenir au moins 10 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial";
    const string PASSWORD_PATTERN_MESSAGE = "Mot de passe non conforme";
    const string EMAIL_ALREADY_USED = "Cet email est déjà en cours d'utilisation par un autre utilisateur.";
    const int AVATAR_SIZE = 3145728;
    const array AVATAR_TYPE = ['image/png', 'image/jpg', 'image/jpeg'];
    const array AVATAR_EXTENSION = ['png', 'jpg', 'jpeg'];
    const string AVATAR_TYPE_INVALID = "Le fichier doit être de type jpeg, jpg ou png";
    const string AVATAR_SIZE_INVALID = "Le fichier doit faire moins de ";

    #[ORM\Column(type: 'integer'), ORM\Id, ORM\GeneratedValue()]
    private ?int $id = null;

    #[Groups(["api:response"])]
    #[ORM\Column(type: 'string', length: '128', unique: 'true')]
    private ?string $email = null;

    #[Groups(["api:response"])]
    #[ORM\Column(type: 'string', length: '64')]
    private string $firstname = "";

    #[Groups(["api:response"])]
    #[ORM\Column(type: 'string', length: '64')]
    private string $lastname = "";

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    // will not be stored in db
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private bool $passwordChanged = false;

    #[ORM\Column(type: 'boolean', options: ["default" => true])]
    private bool $activated = true;

    // Ajout du champ archivedAt
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $archivedAt = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFullname(): string
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    public function getSlugname(): string
    {
        return str_replace([' ', '-'], '', strtolower($this->getFullname()));
    }

    public function getInitials(): string
    {
        return mb_strtoupper(
            mb_substr($this->getFirstname(), 0, 1)
            . mb_substr($this->getLastname(), 0, 1)
        );
    }

    public function getMainRole(): string
    {
        return $this->getRoles()[0];
    }

    public function getMainRoleLabel(): string
    {
        return self::ROLE_LABEL[$this->getRoles()[0]] ?? "Utilisateur";
    }

    public function hasEmailVerified(): ?bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $emailVerified): self
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    public function isActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function hasPasswordChanged(): bool
    {
        return $this->passwordChanged;
    }

    public function setPasswordChanged(bool $passwordChanged): self
    {
        $this->passwordChanged = $passwordChanged;

        return $this;
    }

    public function getPasswordChanged(): ?bool
    {
        return $this->passwordChanged;
    }

    public function getArchivedAt(): ?\DateTimeInterface
    {
        return $this->archivedAt;
    }

    public function setArchivedAt(?\DateTimeInterface $archivedAt): self
    {
        $this->archivedAt = $archivedAt;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFullname();
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function setAvatarName(?string $avatarName): self
    {
        $this->avatarName = $avatarName;
        return $this;
    }

    public function getVichFile(): ?File
    {
        return $this->vichFile;
    }

    public function setVichFile(?File $vichFile): self
    {
        $this->vichFile = $vichFile;
        if ($this->vichFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * Return only the security relevant data
     *
     * @return array
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    /**
     * Restore security relevant data
     *
     * @param array $data
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword = null): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}
