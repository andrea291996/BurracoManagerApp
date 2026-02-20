<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserAccountController extends Controller {

    function login(Request $request, Response $response, $args) { 
        $page = PageConfigurator::instance()->getPage();
        $page->setTitle("Login");
        $page->add("content", new AccountLoginView());
        return $response;
    }

    function doLogin(Request $request, Response $response, $args) { 
        $data = (array)$request->getParsedBody();
        $email = isset($data['email']) ? $data['email'] : null;
        $password = isset($data['password']) ? $data['password'] : null;
        $handler = new UserAccessHandler();
        if(!$handler->login($email, $password)){
            $errorMsg = $handler->getLastError();
            UIMessage::setError($errorMsg);
            return $response->withHeader("Location", "./login")->withStatus(301);
        }
        
        UIMessage::setSuccess(LOGIN_SUCCESS);
        return $response->withHeader("Location", "./")->withStatus(301);
    }

    function logout(Request $request, Response $response, $args) { 
        $handler = new UserAccessHandler();
        $handler->logout();
        UIMessage::setSuccess(LOGOUT_MESSAGE);
        return $response->withHeader("Location", "./")->withStatus(301);
    }
}