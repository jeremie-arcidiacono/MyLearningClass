<?php
declare(strict_types=1);


/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    May 2023
 * Description :    This trait is used by models that has a createdAt column in the database.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models\Traits;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 *  Used to add creation timestamp to a model.
 */
trait HasCreatedAt
{
    #[Column(name: 'createdAt')]
    private \DateTime $createdAt;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    private function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Update the created date of the model.
     * This method is called automatically by Doctrine.
     * @return void
     */
    #[PrePersist, PreUpdate]
    public function updateTimestamps(): void
    {
        if (!isset($this->createdAt)) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

