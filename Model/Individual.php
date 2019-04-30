<?php
/**
 * PHP version 7
 *
 * LICENSE: This source file is subject to copyright
 *
 * @author    Thomas Barriac <thomas@tag-walk.com>
 * @copyright 2019 TAGWALK
 * @license   proprietary
 */

namespace Tagwalk\ApiClientBundle\Model;

use Tagwalk\ApiClientBundle\Model\Traits\Coverable;
use Tagwalk\ApiClientBundle\Model\Traits\Descriptable;
use Tagwalk\ApiClientBundle\Model\Traits\Linkable;
use Symfony\Component\Validator\Constraints as Assert;

class Individual extends AbstractDocument
{
    use Coverable;
    use Descriptable;
    use Linkable;

    /**
     * @var bool
     * @Assert\Type("boolean")
     */
    private $model;

    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    private $gender = 'woman';

    /**
     * @var \DateTime|null
     * @Assert\Date()
     */
    private $birthdate;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $nationality;

    /**
     * @var Agency[]|null
     * @Assert\Valid()
     * @Assert\Type("array")
     */
    private $agencies;

    /**
     * @return bool
     */
    public function isModel(): bool
    {
        return $this->model;
    }

    /**
     * @param bool $model
     * @return self
     */
    public function setModel(bool $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return self
     */
    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime|null $birthdate
     * @return self
     */
    public function setBirthdate(?\DateTime $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    /**
     * @param string|null $nationality
     * @return self
     */
    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Agency[]|null
     */
    public function getAgencies(): ?array
    {
        return $this->agencies;
    }

    /**
     * @param Agency[]|null $agencies
     * @return self
     */
    public function setAgencies(?array $agencies): self
    {
        $this->agencies = $agencies;

        return $this;
    }
}
