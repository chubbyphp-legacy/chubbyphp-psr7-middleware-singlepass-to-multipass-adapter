<?php

namespace Chubbyphp\Tests\Psr7SinglePassToMultiPassAdapter;

use Chubbyphp\Psr7SinglePassToMultiPassAdapter\Psr7SinglePassToMultiPassAdapter;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Chubbyphp\Psr7SinglePassToMultiPassAdapter\Psr7SinglePassToMultiPassAdapter
 */
class Psr7SinglePassToMultiPassAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutNext()
    {
        $givenRequest = $this->getMockBuilder(RequestInterface::class)->getMockForAbstractClass();
        $givenResponse = $this->getMockBuilder(ResponseInterface::class)->getMockForAbstractClass();

        $adapter = new Psr7SinglePassToMultiPassAdapter(function (RequestInterface $request, callable $next) {
            return $next($request);
        });

        self::assertSame($givenResponse, $adapter($givenRequest, $givenResponse));
    }

    public function testWithNext()
    {
        $givenRequest = $this->getMockBuilder(RequestInterface::class)->getMockForAbstractClass();
        $givenResponse = $this->getMockBuilder(ResponseInterface::class)->getMockForAbstractClass();

        $adapter = new Psr7SinglePassToMultiPassAdapter(function (RequestInterface $request, callable $next) {
            return $next($request);
        });

        self::assertSame(
            $givenResponse,
            $adapter(
                $givenRequest,
                $givenResponse,
                function (
                    RequestInterface $request,
                    ResponseInterface $response,
                    callable $next = null
                ) use ($givenRequest, $givenResponse) {
                    self::assertSame($givenRequest, $request);
                    self::assertSame($givenResponse, $response);
                    self::assertNull($next);

                    return $response;
                }
            )
        );
    }

    public function testWithoutNextButEarlyResponse()
    {
        $givenRequest = $this->getMockBuilder(RequestInterface::class)->getMockForAbstractClass();
        $givenResponse = $this->getMockBuilder(ResponseInterface::class)->getMockForAbstractClass();

        $adapter = new Psr7SinglePassToMultiPassAdapter(function (RequestInterface $request, callable $next) {
            return $this->getMockBuilder(ResponseInterface::class)->getMockForAbstractClass();
        });

        $returnedResponse = $adapter($givenRequest, $givenResponse);

        self::assertInstanceOf(ResponseInterface::class, $returnedResponse);
        self::assertNotSame($givenResponse, $returnedResponse);
    }

    public function testWithNextButEarlyResponse()
    {
        $givenRequest = $this->getMockBuilder(RequestInterface::class)->getMockForAbstractClass();
        $givenResponse = $this->getMockBuilder(ResponseInterface::class)->getMockForAbstractClass();

        $adapter = new Psr7SinglePassToMultiPassAdapter(function (RequestInterface $request, callable $next) {
            return $this->getMockBuilder(ResponseInterface::class)->getMockForAbstractClass();
        });

        $returnedResponse = $adapter(
            $givenRequest,
            $givenResponse,
            function (RequestInterface $request, ResponseInterface $response, callable $next) {
                throw new \Exception('This code should not be called!');
            }
        );

        self::assertInstanceOf(ResponseInterface::class, $returnedResponse);
        self::assertNotSame($givenResponse, $returnedResponse);
    }
}
