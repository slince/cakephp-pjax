<?php
/*
 * This file is part of the slince/cakephp-pjax package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Pjax\Middleware;

use Cake\Network\Response;
use Slince\Pjax\Helper\PjaxHelper;
use Symfony\Component\DomCrawler\Crawler;
use Cake\Network\Request;

class PjaxMiddleware
{
    /**
     * @var PjaxHelper
     */
    protected $helper;

    /**
     * The DomCrawler instance.
     *
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    public function __construct()
    {
        $this->helper = PjaxHelper::instance();
    }

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Response $response
     * @param \Closure $next
     *
     * @return Response
     */
    public function __invoke($request, $response, $next)
    {
        if ($isPjax = $this->helper->isPjaxRequest($request)) {
            $request = $this->setRequestAttribute($request);
        }

        $response = $next($request, $response);

        if (!$isPjax || $this->isRedirectionResponse($response)) {
            return $response;
        }

        $response = $this->filterResponse($response, $this->helper->getContainer($request));
        $response = $this->setUriHeader($response, $request);
        $response = $this->setVersionHeader($response);

        return $response;
    }

    /**
     * @param Response $response
     * @param string   $container
     *
     * @return Response
     */
    protected function filterResponse(Response $response, $container)
    {
        $crawler = $this->getCrawler($response->getBody());

        return $response->withStringBody(
            $this->makeTitle($crawler)
            .$this->fetchContainer($crawler, $container)
        );
    }

    /**
     * @param Request $request
     *
     * @return Request
     */
    protected function setRequestAttribute(Request $request)
    {
        return $request->withAttribute('IS_PJAX', true);
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     *
     * @return null|string
     */
    protected function makeTitle(Crawler $crawler)
    {
        $pageTitle = $crawler->filter('head > title');

        if (!$pageTitle->count()) {
            return null;
        }

        return "<title>{$pageTitle->html()}</title>";
    }

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     * @param string                                $container
     *
     * @return string
     */
    protected function fetchContainer(Crawler $crawler, $container)
    {
        $content = $crawler->filter($container);

        if (!$content->count()) {
            return null;
        }

        return $content->html();
    }

    /**
     * @param Response $response
     * @param Request  $request
     *
     * @return Response
     */
    protected function setUriHeader(Response $response, Request $request)
    {
        return $response->withHeader('X-PJAX-URL', (string) $request->getUri());
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    protected function setVersionHeader(Response $response)
    {
        $crawler = $this->getCrawler(strtolower($response->getBody()));
        $node = $crawler->filter('head > meta[http-equiv="x-pjax-version"]');

        if ($node->count()) {
            $response = $response->withHeader('x-pjax-version', $node->attr('content'));
        }

        return $response;
    }

    /**
     * @param Response $response
     *
     * @return bool
     */
    protected function isRedirectionResponse(Response $response)
    {
        return $response->getStatusCode() >= 300 && $response->getStatusCode() < 400;
    }

    /**
     * Get the DomCrawler instance.
     *
     * @param string $body
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getCrawler($body)
    {
        if ($this->crawler) {
            return $this->crawler;
        }

        return $this->crawler = new Crawler((string) $body);
    }
}
