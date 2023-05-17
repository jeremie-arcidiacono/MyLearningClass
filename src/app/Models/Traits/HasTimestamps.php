<?php

declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    February 2023
 * Description :    This trait is used by models that has a createdAt and updatedAt column in the database.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models\Traits;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 *  Used to add timestamps (creation and update) to a model.
 */
trait HasTimestamps
{
    use HasCreatedAt;

    #[Column(name: 'updatedAt')]
    private \DateTime $updatedAt;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Update the timestamps of the model.
     * Doctrine calls this method automatically.
     * @return void
     */
    #[PrePersist, PreUpdate]
    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());
        if (!isset($this->createdAt)) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}
