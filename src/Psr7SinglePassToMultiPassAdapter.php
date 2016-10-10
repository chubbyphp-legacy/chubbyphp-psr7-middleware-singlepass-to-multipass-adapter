<?php

declare(strict_types=1);

namespace Chubbyphp\Psr7SinglePassToMultiPassAdapter;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Psr7SinglePassToMultiPassAdapter
{
    /**
     * @var callable
     */
    private $singlePassMiddleware;

    /**
     * @param callable $singlePassMiddleware
     */
    public function __construct(callable $singlePassMiddleware)
    {
        $this->singlePassMiddleware = $singlePassMiddleware;
    }

    /**
     * @param Request       $request
     * @param Response      $response
     * @param callable|null $next
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $singlePassMiddleware = $this->singlePassMiddleware;

        return $singlePassMiddleware($request, function (Request $request) use ($response, $next) {
            if (null === $next) {
                return $response;
            }

            return $next($request, $response);
        });
    }
}
