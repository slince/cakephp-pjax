<?php
/*
 * This file is part of the slince/cakephp-pjax package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Pjax\Helper;

use Cake\Network\Request;

final class PjaxHelper
{
    /**
     * @var static
     */
    private static $instance;

    /**
     * Checks whether the request is pjax.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isPjaxRequest(Request $request)
    {
        return (bool) $request->getHeaderLine('X-PJAX');
    }

    /**
     * Get container.
     *
     * @param Request $request
     *
     * @return string
     */
    public function getContainer(Request $request)
    {
        return $request->getHeaderLine('X-PJAX-Container');
    }

    /**
     * Creates the instance.
     *
     * @return PjaxHelper
     */
    public static function instance()
    {
        if (static::$instance) {
            return static::$instance;
        }

        return static::$instance = new static();
    }
}
