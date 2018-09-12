<?php
namespace App\Middleware;

use App\Lib\Auth;
use Exception;

class AuthMiddleware
{
    private $app = null;
    private $skip_guard;

    public function __construct($app, $skip_guard = false)
    {
        $this->app = $app;
        $this->skip_guard = $skip_guard;
    }

    public function __invoke($request, $response, $next)
    {
        $c = $this->app->getContainer();
        $app_token_name = $c->settings['app_token_name'];

        $token = $request->getHeader($app_token_name);

        if ($token == null) {                          //
            return $next($request, $response);         //  Remove this three lines when token is working
        }                                              //

        if (isset($token[0])) $token = $token[0];

        try {
            Auth::Check($token);
            $user = Auth::GetData($token);
            if (!$user->isAdmin && !$this->skip_guard) {
                $db = $this->app->getContainer()['db'];
                $tablet_token = $db
                    ->from('tablet_token')
                    ->where('guard_id', $user->id)
                    ->fetch();
                if (is_object($tablet_token)) {
                    if ($tablet_token->session != $token) {
                        return $response
                                        ->withStatus(401)
                                        ->write('Unauthorized');
                    }
                } else {
                    return $response
                                    ->withStatus(401)
                                    ->write('Unauthorized');
                }
            }
        } catch (Exception $e) {
            return $response
                            ->withStatus(401)
                            ->write('Unauthorized');
        }

        return $next($request, $response);
    }
}