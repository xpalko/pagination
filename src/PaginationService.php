<?php
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
declare(strict_types = 1);

namespace Phauthentic\Pagination;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Pagination Service
 *
 * Application layer pagination service that should in theory be able to paginate
 * any data / persistence layer implementation through the mappers.
 */
class PaginationService
{
    /**
     * Pagination Params Factory
     *
     * @var \Phauthentic\Pagination\PaginationParamsFactoryInterface
     */
    protected $paginationParamsFactory;

    /**
     * Pagination to data layer implementation mapper
     *
     * @var \Phauthentic\Pagination\PaginationToRepositoryMapperInterface
     */
    protected $paginationToRepositoryMapper;

    /**
     * Constructor
     */
    public function __construct(
        PaginationParamsFactory $paginationParamsFactory,
        PaginationToRepositoryMapperInterface $paginationToRepositoryMapper
    ) {
        $this->paginationParamsFactory = $paginationParamsFactory;
        $this->paginationToRepositoryMapper = $paginationToRepositoryMapper;
    }

    /**
     * Sets the object that maps the pagination data to the underlying implementation
     *
     * @return $this
     */
    public function setPaginationToRepositoryMapper(PaginationToRepositoryMapperInterface $mapper): self
    {
        $this->paginationToRepositoryMapper = $mapper;

        return $this;
    }

    /**
     * Sets the pagination params factory
     *
     * @return $this
     */
    public function setPaginationParamsFactory(PaginationParamsFactory $factory): self
    {
        $this->paginationParamsFactory = $factory;

        return $this;
    }

    /**
     * Gets the pagination params from the request
     *
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest Server Request
     * @return \Phauthentic\Pagination\PaginationParamsInterface
     */
    public function getPagingParamsFromRequest(
        ServerRequestInterface $serverRequest
    ): PaginationParamsInterface {
        return $this->paginationParamsFactory->build($serverRequest);
    }

    public function setPagingRequestAttribute(
        ServerRequestInterface $serverRequest,
        PaginationParamsInterface $paginationParams,
        string $attributeName = 'paging'
    ): ServerRequestInterface {
        return $serverRequest->setAttribute($attributeName, $paginationParams);
    }

    /**
     * Triggers the pagination on an object
     *
     * @param \Psr\Http\Message\ServerRequestInterface
     * @param mixed $object The object to paginate on
     * @param callable $callable Optional callable to do whatever you want instead of using a mapper
     * @return mixed
     */
    public function paginateFromRequest(ServerRequestInterface $request, $repository, ?callable $callable = null)
    {
        $params = $this->getPagingParamsFromRequest($request);

        return $this->paginate($params, $repository, $callable);
    }

    /**
     * Triggers the pagination on an object
     *
     * @param \Psr\Http\Message\ServerRequestInterface
     * @param mixed $object The object to paginate on
     * @param callable $callable Optional callable to do whatever you want instead of using a mapper
     * @return mixed
     */
    public function paginate(PaginationParamsInterface $paginationParams, $repository, ?callable $callable = null)
    {
        if ($callable) {
            return $callable($repository, $paginationParams);
        }

        return $this->paginationToRepositoryMapper->map($paginationParams, $repository);
    }
}
