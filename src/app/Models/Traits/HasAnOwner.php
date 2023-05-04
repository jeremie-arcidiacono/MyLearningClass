<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    February 2023
 * Description :    This trait is used by models that have an owner (like a comment, a post, etc.)
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models\Traits;

use App\Contracts\IModel;
use App\Models\User;

/**
 * Used to add an owner to a model
 */
trait HasAnOwner
{
    /**
     * Get the owner of the model
     * @return User
     */
    abstract public function getOwner(): User;

    /**
     * Set the owner of the model
     * @param User $owner
     * @return IModel
     */
    abstract public function setOwner(User $owner): IModel;
}
