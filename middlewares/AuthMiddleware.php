<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

class AuthMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response {
        $user = null;

        if (isset($_SESSION['account']['id']) && isset($_SESSION['account']['tipologia'])) {
            $id = $_SESSION['account']['id'];
            $tipo = $_SESSION['account']['tipologia'];

            switch ($tipo) {
                case 'giocatore':       $user = new Accountgiocatori(); break;
                case 'circolo':         $user = new Accountcircoli(); break;
                case 'amministratore':  $user = new Accountamministratori(); break;
            }

            if ($user) {
                $user->select($id);
                $user->setpassword(null);
            }
        }else{
            $user = new Anonimo();
        }

        return $handler->handle($request->withAttribute('user', $user));
    }
}