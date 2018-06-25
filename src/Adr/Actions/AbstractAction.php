<?php

namespace Adr\Actions;

use Slim\Container;

abstract class AbstractAction
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * AbstractRoute constructor.
     *
     * All routing groups have access to the container.
     *
     * @param Container $container
     */
    final public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
