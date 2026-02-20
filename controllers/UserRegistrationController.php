<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserRegistrationController extends Controller {
    function registration(Request $request, Response $response, $args) { 
        $page = PageConfigurator::instance()->getPage();
        $page->setTitle("Registrazione Utente");
        $page->add("content", new UserRegistrationView());
        return $response;
    }

    function doRegistration (Request $request, Response $response, $args) {
        $data = (array)$request->getParsedBody();        
        $handler = new UserRegistrationHandler();
        
        if($handler->registration($data)){
            UIMessage::setSuccess(REGISTRATION_SUCCESS);
            return $response->withHeader("Location", "./login")->withStatus(302);
        }else{
            $erroreSpecifico = $handler->getLastError();
            $messaggioDaMostrare = !empty($erroreSpecifico) ? $erroreSpecifico : REGISTRATION_FAILED;
            UIMessage::setError($messaggioDaMostrare);
        }
        return $response->withHeader("Location", "./registrazione")->withStatus(302);
    }

}