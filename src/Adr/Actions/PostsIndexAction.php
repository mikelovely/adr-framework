<?php

namespace Adr\Actions;

use Adr\Domain\Services\PostIndexService;
use Adr\Responders\PostIndexResponder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;

class PostsIndexAction extends AbstractAction
{
    public function __invoke(RequestInterface $request, Response $response)
    {
        return $this->container->get(PostIndexResponder::class)->withResponse(
            $response
        )->send(
            $this->container->get(PostIndexService::class)->handle()
        );
    }
}
