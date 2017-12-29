<?php
namespace Slince\Pjax\Test;

use Cake\Network\Request;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @param string|null $container
     * @return Request
     */
    final static function createPjaxRequest($container = null)
    {
        return (new Request())->withHeader('X-PJAX', true)
            ->withHeader('X-PJAX-Container', $container ?: '#pjax-container');
    }
}
