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

use Psr\Http\Message\ServerRequestInterface;

/**
 * Pagination Service Interface
 */
class PaginationParamsFactory implements PaginationParamsFactoryInterface {

    /**
     * @var array
     */
    protected $map = [
        'limit' => 'limit',
        'page' => 'page',
        'direction' => 'direction'
    ];

    public function getPage(array $params, string $name = 'page'): int
    {
        if (isset($params[$name])) {
            return (int)$params[$name];
        }

        return 1;
    }

    public function getLimit(array $params, string $name = 'limit'): int
    {
        if (isset($params[$name])) {
            return (int)$params['limit'];
        }

        return 20;
    }

    public function getDirection(array $params, string $name = 'direction'): string
    {
        if (isset($params[$name])) {
            return (string)$params[$name];
        }

        return 'desc';
    }

    /**
     *
     */
    public function setQueryParamMapping(array $map): self
    {
        $this->map = $map;
    }

    protected function mapRequest(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        $params = new PaginationParams();

        foreach ($this->map as $setter => $value) {
            $setterMethod = 'set' . $setter;
            $getterMethod = 'get' . $setter;

            if (is_callable($value)) {
                $value = $value($request);
            }
            if (isset($queryParams[$value])) {
                $params->{$setterMethod}($this->{$getterMethod}($queryParams, $value));
            }
        }

        return $params;
    }

    /**
     * @inheritDoc
     */
    public function build(ServerRequestInterface $request): PaginationParamsInterface
    {
        return $this->mapRequest($request);
    }
}
