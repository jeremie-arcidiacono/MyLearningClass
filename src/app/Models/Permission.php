<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a Doctrine entity representing a permission
 *                  E.g. 'create' permission on 'courseCategory' ressource
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Models;

use App\Contracts\IModel;
use Doctrine\ORM\Mapping\{Column, Entity, Id, Table};

/**
 * Entity representing a permission
 */
#[Entity, Table(name: 'PERMISSION')]
class Permission implements IModel
{
    #[Column(name: 'codePermission', length: 4, options: ['unsigned' => true])]
    #[Id]
    private int $code;

    #[Column(length: 30)]
    private string $action;

    #[Column(length: 30)]
    private string $ressource;


    /**
     * Get the id of the model
     * @return int
     */
    public function getId(): int
    {
        return $this->getCode();
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Permission
     */
    public function setCode(int $code): Permission
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Permission
     */
    public function setAction(string $action): Permission
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getRessource(): string
    {
        return $this->ressource;
    }

    /**
     * @param string $ressource
     * @return Permission
     */
    public function setRessource(string $ressource): Permission
    {
        $this->ressource = $ressource;
        return $this;
    }


}
