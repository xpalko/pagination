<?php
declare(strict_types = 1);
/**
 * Copyright (c) Phauthentic (https://github.com/Phauthentic)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Phauthentic (https://github.com/Phauthentic)
 * @link          https://github.com/Phauthentic
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Phauthentic\Pagination;

/**
 * Pagination Service Interface
 */
interface PaginationParamsInterface
{
    /**
     * Sets the total count of all records matching the parameters
     *
     * @param int $count Count
     * @return $this
     */
    public function setCount(int $count): self;

    /**
     * Gets the total count of all records
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * Sets the page number
     *
     * @param int $page Page
     * @return self
     */
    public function setPage(int $page): self;

    /**
     * Gets the current page number
     *
     * @return int
     */
    public function getPage(): int;
}
