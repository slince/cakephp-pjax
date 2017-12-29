<?php

namespace Slince\Pjax\Test\Helper;

use Cake\Network\Request;
use Slince\Pjax\Helper\PjaxHelper;
use Slince\Pjax\Test\TestCase;

class PjaxHelperTest extends TestCase
{
    /**
     * @var PjaxHelper
     */
    protected $helper;

    public function setUp()
    {
        $this->helper = PjaxHelper::instance();
    }

    public function testCheckPjax()
    {
        $this->assertFalse($this->helper->isPjaxRequest(new Request()));
        $this->assertTrue($this->helper->isPjaxRequest(static::createPjaxRequest()));
    }

    public function testGetContainer()
    {
        $this->assertEquals('#test-container', $this->helper->getContainer(
            static::createPjaxRequest('#test-container')
        ));
    }
}
