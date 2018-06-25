<?php

namespace Adr\Responders;

use Psr\Http\Message\ResponseInterface;

class PostIndexResponder
{
    private $response;

    public function withResponse(ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    public function send(array $posts)
    {
        return $this->response->withJson($posts);
    }
}
