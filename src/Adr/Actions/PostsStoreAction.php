<?php

namespace Adr\Actions;

use Adr\Domain\Services\PostStoreService;
use Adr\Responders\PostStoreResponder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;

class PostsStoreAction extends AbstractAction
{
    public function __invoke(RequestInterface $request, Response $response)
    {
        return $this->container->get(PostStoreResponder::class)->withResponse(
            $response
        )->send(
            $this->container->get(PostStoreService::class)->handle($request->getParams())
        );
    }
}
