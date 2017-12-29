<?php
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Slince\Pjax\Middleware\PjaxMiddleware;
use Slince\Pjax\Helper\PjaxHelper;

/**
 * Detector
 */
ServerRequest::addDetector('pjax', function ($request) {
    return PjaxHelper::instance()->isPjaxRequest($request);
});

/**
 * Middleware
 */
EventManager::instance()->on('Server.buildMiddleware', function ($event, MiddlewareQueue $middleware) {
        $middleware->add(new PjaxMiddleware());
});
