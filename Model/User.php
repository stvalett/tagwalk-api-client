<?php
/**
 * PHP version 7.
 *
 * LICENSE: This source file is subject to copyright
 *
 * @author      Florian Ajir <florian@tag-walk.com>
 * @copyright   2016-2019 TAGWALK
 * @license     proprietary
 */

namespace Tagwalk\ApiClientBundle\Model;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Tagwalk\ApiClientBundle\Model\Traits\Nameable;
use Tagwalk\ApiClientBundle\Model\Traits\Sluggable;
use Tagwalk\ApiClientBundle\Model\Traits\Statusable;
use Tagwalk\ApiClientBundle\Model\Traits\Timestampable;

/**
 * Describe User document.
 *
 * Used for persistance and authentication
 *
 * @see Document
 * @see UserInterface
 */
class User implements UserInterface, EquatableInterface
{
    use Sluggable;
    use Statusable;
    use Timestampable;
    use Nameable;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $firstname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $lastname;

    /**
     * @var string
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $gender;

    /**
     * @var bool|null
     * @Assert\Type("bool")
     */
    private $fashionIndustry;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $jobTitle;

    /**
     * @var bool
     * @Assert\Type("bool")
     */
    private $newsletter;

    /**
     * @var bool|null
     * @Assert\Type("bool")
     */
    private $survey;

    /**
     * @var bool|null
     * @Assert\Type("bool")
     */
    private $vip = false;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $sector;

    /**
     * @var string|null
     * @Assert\NotBlank(groups={"Default", "ShowroomUser"})
     */
    private $country;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $locale;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $salt;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $password;

    /**
     * @var string[]|null
     */
    private $roles;

    /**
     * @var string|null
     */
    private $facebookId;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank(groups={"ShowroomUser"})
     */
    private $company;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank(groups={"ShowroomUser"})
     */
    private $address;

    /**
     * @var string
     * @Assert\Type("string")
     */
    private $note;

    /**
     * @param string      $name
     * @param string|null $password
     * @param string|null $salt
     * @param array|null  $roles
     */
    public function __construct(
        ?string $name = null,
        ?string $password = null,
        ?string $salt = null,
        ?array $roles = []
    ) {
        $this->name = $name;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = empty($roles) ? ['ROLE_USER'] : $roles;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     *
     * @return self
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     *
     * @return self
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     *
     * @return self
     */
    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFashionIndustry(): ?bool
    {
        return $this->fashionIndustry;
    }

    /**
     * @param bool|null $fashionIndustry
     *
     * @return User
     */
    public function setFashionIndustry(?bool $fashionIndustry): self
    {
        $this->fashionIndustry = $fashionIndustry;

        return $this;
    }

    /**
     * @return string
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @param string $jobTitle
     *
     * @return self
     */
    public function setJobTitle(?string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    /**
     * @param bool|null $newsletter
     *
     * @return self
     */
    public function setNewsletter(?bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSurvey(): ?bool
    {
        return $this->survey;
    }

    /**
     * @param bool $survey
     *
     * @return self
     */
    public function setSurvey(?bool $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVip(): ?bool
    {
        return $this->vip;
    }

    /**
     * @param bool $vip
     *
     * @return self
     */
    public function setVip(?bool $vip): self
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSector(): ?string
    {
        return $this->sector;
    }

    /**
     * @param string|null $sector
     *
     * @return self
     */
    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     *
     * @return self
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string|null $locale
     *
     * @return self
     */
    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     *
     * @return self
     */
    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     *
     * @return self
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null
     *
     * @return self
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return string
     */
    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId
     *
     * @return self
     */
    public function setFacebookId(?string $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * Also implementation should consider that $user instance may implement
     * the extended user interface `AdvancedUserInterface`.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }
        if ($this->email !== $user->getEmail()) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstname.' '.$this->lastname;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     */
    public function setCompany(?string $company): void
    {
        $this->company = $company;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string|null $note
     */
    public function setNote(?string $note): void
    {
        $this->note = $note;
    }
}
