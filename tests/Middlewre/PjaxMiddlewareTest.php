<?php

namespace Slince\Pjax\Test\Middleware;

use Cake\Http\ServerRequestFactory;
use Cake\Network\Response;
use Cake\Network\Request;
use Slince\Pjax\Middleware\PjaxMiddleware;
use Slince\Pjax\Test\TestCase;
use Zend\Diactoros\Uri;

class PjaxMiddlewareTest extends TestCase
{
    /**
     * @var PjaxMiddleware
     */
    protected $middleware;

    public function setUp()
    {
        $this->middleware = new PjaxMiddleware();
    }

    public function testNonPjaxRequest()
    {
        $request = new Request();
        $response = new Response();
        $response = call_user_func($this->middleware, $request, $response, $this->createNext());

        $this->assertFalse($this->isPjaxResponse($response));

        $this->assertEquals($this->getHtml(), (string)$response->getBody());
    }

    public function testPjaxRequest()
    {
        $request = static::createPjaxRequest();

        $response = call_user_func($this->middleware, $request, new Response(), $this->createNext());
        $this->assertTrue($this->isPjaxResponse($response));

        $this->assertEquals('<title>Pjax title</title>Content', (string)$response->getBody());
    }

    public function testPjaxResponseWithoutTitle()
    {
        $request = static::createPjaxRequest();

        $response = call_user_func($this->middleware, $request, new Response(), $this->createNext('pageWithoutTitle'));

        $this->assertTrue($this->isPjaxResponse($response));

        $this->assertEquals('Content', (string)$response->getBody());
    }

    public function testRequestUrlForPjaxResponse()
    {
        $request = static::createPjaxRequest();
        $request = $request->withUri((new Uri())->withPath('/test'));

        $response = call_user_func($this->middleware, $request, new Response(), $this->createNext());

        $this->assertEquals('/test', $response->getHeaderLine('X-PJAX-URL'));
    }

    public function testVersionForPjaxResponse()
    {
        $request = static::createPjaxRequest();
        $response = call_user_func($this->middleware, $request, new Response(), $this->createNext());

        $this->assertEquals('1.0.0', $response->getHeaderLine('X-PJAX-Version'));
    }

    public function testNonVersion()
    {
        $request = static::createPjaxRequest();
        $response = call_user_func($this->middleware, $request, new Response(), $this->createNext('pageWithoutVersionMetaTag'));
        $this->assertEquals(null, $response->getHeaderLine('X-PJAX-Version'));
    }

    /**
     * @param Response $response
     *
     * @return bool
     */
    protected function isPjaxResponse(Response $response)
    {
        return $response->hasHeader('X-PJAX-URL');
    }

    /**
     * @param string $pageName
     *
     * @return \Closure
     */
    protected function createNext($pageName = 'pageWithTitle')
    {
        $html = $this->getHtml($pageName);
        return function (Request $request, Response $response) use ($html) {
            return $response->withStringBody($html);
        };
    }

    /**
     * @param string $pageName
     *
     * @return string
     */
    protected function getHtml($pageName = 'pageWithTitle')
    {
        return file_get_contents(__DIR__."/../Fixtures/{$pageName}.html");
    }
}
