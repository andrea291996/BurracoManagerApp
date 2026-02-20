<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

class MenuFooterMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response {
        if ($request->getMethod() == 'GET') {
            $page = PageConfigurator::instance()->getPage(); 
            $user = $request->getAttribute('user'); // Preso dall'AuthMiddleware

            // Costruiamo il menu in base all'utente
            $menuData = $this->buildMenu($user);

            $page->add('header', new Menu("ui/menu", $menuData));
            $page->add('footer', new Footer());
        }
        return $handler->handle($request);
    }

    private function buildMenu($user) {
        // Base del menu (sempre presente)
        $items = [
            ['href' => "./", 'title' => "HOME"],
            ['href' => "tornei", 'title' => "TORNEI"]
        ];

        if ($user) {
            // Se è AMMINISTRATORE
            if ($user->isAmministratore()) {
                $items[] = ['href' => "utenti", 'title' => "UTENTI"];
                $items[] = ['href' => "circoli", 'title' => "CIRCOLI"];
                
                $items[] = [
                    'title' => "PROFILO",
                    'isRight' => true,
                    'hasChilds' => true,
                    'childs' => [
                        ['href' => "logout", 'title' => "Esci"]
                    ]
                ];
            } 
            // Se è GIOCATORE o CIRCOLO
            elseif ($user->isCircolo() || $user->isGiocatore()) {
                $items[] = [
                    'title' => "PROFILO",
                    'isRight' => true,
                    'hasChilds' => true,
                    'childs' => [
                        ['href' => "mieitornei", 'title' => "Miei Tornei"],
                        ['isDivider' => true],
                        ['href' => "logout", 'title' => "Esci"]
                    ]
                ];
            }
        elseif($user->isAnonimo()) {
            // Se ANONIMO
            $items[] = ['href' => "login", 'title' => "Accedi", 'isRight' => true];
            $items[] = ['href' => "registrazione", 'title' => "Registrati", 'isRight' => true];
        }
    }

        return [
            'brand' => ['href' => "./", 'title' => "Mio sito"],
            'items' => $items
        ];
    }
}