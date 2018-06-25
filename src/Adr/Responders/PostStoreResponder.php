<?php

namespace Adr\Responders;

use Adr\Domain\Messages\ValidationMessage;
use Psr\Http\Message\ResponseInterface;

class PostStoreResponder
{
    private $response;

    public function withResponse(ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    public function send($response)
    {
        if ($response instanceof ValidationMessage) {
            return $this->response->withJson($response->toArray(), 422);
        }

        return $this->response->withJson($response);
    }
}
