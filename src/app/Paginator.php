<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    April 2023
 * Description :    This class extends the Doctrine Paginator to add some automatic pagination features.
 *                  It will automatically calculate the total number of pages and verify that the current page is possible.
 *                  It will add the LIMIT and OFFSET to the query.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * This extends the Doctrine Paginator to add some automatic pagination features.
 * It will automatically calculate the total number of pages and verify that the current page is possible.
 * It will add the LIMIT and OFFSET to the query.
 */
class Paginator extends \Doctrine\ORM\Tools\Pagination\Paginator
{
    private int $totalPage;
    private int $totalItems;

    /**
     * @param Query|QueryBuilder $query A Doctrine ORM query or query builder.
     * @param int $currentPage          The current page number
     * @param int $perPage              The number of items per page
     * @param bool $fetchJoinCollection Whether the query joins a collection (true by default).
     * @throws \Exception               If the total number of pages cannot be determined.
     */
    public function __construct(
        $query,
        private int $currentPage,
        private readonly int $perPage,
        bool $fetchJoinCollection = true
    ) {
        // Define the maximum page number
        $maxPageQuery = clone $query;
        // Replace the SELECT part with a COUNT eg: SELECT u FROM User u => SELECT COUNT(u) FROM User u
        $selected = $maxPageQuery->getDQLPart('select'); // Get the SELECT part of the query
        $maxPageQuery->select('COUNT(' . $selected[0]->getParts()[0] . ')'); // Replace the SELECT part with a COUNT

        // Remove the eager loading part of the query (to avoid a bug with the COUNT)
        $maxPageQuery->resetDQLPart('join'); // To do : find a better way to do this, it's not well tested

        $count = $maxPageQuery->getQuery()->getSingleScalarResult();
        try {
            $this->totalPage = (int)ceil($count / $this->perPage);
        } catch (\Exception $e) {
            throw new \Exception('Paginator cannot determine the total number of pages. Please check your query.', 500, $e);
        }

        // Verify that the current page is not too high and minimum is 1
        $this->currentPage = max($this->currentPage, 1);
        $this->totalPage = max($this->totalPage, 1);
        $this->currentPage = min($this->currentPage, $this->totalPage);


        // Add the LIMIT and OFFSET to the query
        $query->setFirstResult(max(($this->currentPage - 1) * $perPage, 0))
            ->setMaxResults($perPage);

        $this->totalItems = (int)$count;
        parent::__construct($query, $fetchJoinCollection);
    }

    /**
     * Get the current page number
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get the total number of pages
     * @return int
     */
    public function getTotalPage(): int
    {
        return $this->totalPage;
    }

    /**
     * Get the number of items per page
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get the total number of items that match the query (without pagination)
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
}
