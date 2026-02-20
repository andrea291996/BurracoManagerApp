<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

class PageMiddleware{
    public function __invoke(Request $request, RequestHandler $handler): Response{
        $response = $handler->handle($request);
        if($request->getMethod() == 'GET'){
            $page = PageConfigurator::instance()->getPage(); 
            $response->getBody()->write($page->render());
        }
        return $response;
    }
}