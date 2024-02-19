<?php
namespace App\Entity;


use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mime\Message;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'integer')]

    private $id;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "email ne doit pas etre vide ")]
    #[Assert\Email(message: "the email '{{value}}' is not a valid email ")]

    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];



    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 6, minMessage: "Your password must be at least {{ limit }} characters.")]
    private $password;

    #[ORM\Column(length: 255)]


    private ?string $lastname = null;

    public function __construct()
    {
        // Ensure that each new user always has the ROLE_USER role by default
        $this->roles = ['ROLE_USER'];
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }



    public function getRoles(): array
    {
        /* // Ensure that each user always has the ROLE_USER role
                                $roles = ['ROLE_USER'];
                            
                                // If the user has additional roles defined, add them to the array
                                if (!empty($this->roles)) {
                                    $roles = array_merge($roles, $this->roles);
                                }
                            
                                // Make sure the roles array contains unique values
                                return array_unique($roles);
                                //return $this->roles;*/

        // Get the roles from the entity property
        $roles = $this->roles;

        // Ensure that every user always has the ROLE_USER role
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        // Make sure the roles array contains unique values
        $roles = array_unique($roles);

        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        // Not needed when using bcrypt or argon2i
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }
}
?>