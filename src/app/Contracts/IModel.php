<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This interface must be implemented by all the models. (aka Doctrine entities)
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Contracts;

/**
 * Interface for all the models.
 */
interface IModel
{
    /**
     * Get the ID of the model.
     * @return int|string
     */
    public function getId(): int|string;
}
