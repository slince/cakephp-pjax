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
use Closure;
use Symfony\Component\DomCrawler\Crawler;
use Cake\Network\Request;

class PjaxMiddleware
{
    /**
     * The DomCrawler instance.
     *
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function __invoke(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$request->pjax() || $response->isRedirection()) {
            return $response;
        }

        $this->filterResponse($response, $request->header('X-PJAX-Container'))
            ->setUriHeader($response, $request)
            ->setVersionHeader($response, $request);

        return $response;
    }

    /**
     * @param Response $response
     * @param string                    $container
     *
     * @return $this
     */
    protected function filterResponse(Response $response, $container)
    {
        $crawler = $this->getCrawler($response);

        $response->setContent(
            $this->makeTitle($crawler).
            $this->fetchContainer($crawler, $container)
        );

        return $this;
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
            return;
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
            abort(422);
        }

        return $content->html();
    }

    /**
     * @param Response $response
     * @param Request  $request
     *
     * @return $this
     */
    protected function setUriHeader(Response $response, Request $request)
    {
        $response->header('X-PJAX-URL', $request->getRequestUri());

        return $this;
    }

    /**
     * @param Response $response
     * @param Request  $request
     *
     * @return $this
     */
    protected function setVersionHeader(Response $response, Request $request)
    {
        $crawler = $this->getCrawler($this->createResponseWithLowerCaseContent($response));
        $node = $crawler->filter('head > meta[http-equiv="x-pjax-version"]');

        if ($node->count()) {
            $response->header('x-pjax-version', $node->attr('content'));
        }

        return $this;
    }

    /**
     * Get the DomCrawler instance.
     *
     * @param Response $response
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getCrawler(Response $response)
    {
        if ($this->crawler) {
            return $this->crawler;
        }

        return $this->crawler = new Crawler($response->getContent());
    }

    /**
     * Make the content of the given response lowercase.
     *
     * @param Response $response
     *
     * @return Response
     */
    protected function createResponseWithLowerCaseContent(Response $response)
    {
        $lowercaseContent = strtolower($response->getContent());

        return Response::create($lowercaseContent);
    }
}
