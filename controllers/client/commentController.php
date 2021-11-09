<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Jwt;
use Over_Code\Models\UserModel;
use Over_Code\Models\CommentModel;
use Over_Code\Controllers\MainController;

/**
 * Manage comment form
 */
class CommentController extends MainController
{
    use \Over_Code\Libraries\Helpers;

    public function post()
    {
        $articleParam = preg_replace(
            '~' . SINGLE_ARTICLE . '~',
            '',
            $this->getSERVER('HTTP_REFERER')
        );
        $url = SINGLE_ARTICLE . $articleParam . "/#comment";

        $jwt = new Jwt();
        $token = '';

        if (!empty($this->getCOOKIE('token'))) {
            $token =  $this->getCOOKIE('token');
        }

        if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
            $user = new UserModel();

            $payload = $jwt->decodeDatas($token, 1);
            $ipLog = $user->readIpLog($payload['email']);
            $remoteIp = $this->getSERVER('REMOTE_ADDR');

            if ($jwt->isNotExpired($payload) && ($ipLog === $remoteIp)) {
                $user->hydrate('renewal', $payload['email'], 900); // exp 15 min

                $this->setCOOKIE('token', $user->getToken());
                $this->setCOOKIE('token_obj', 'renewal');

                //$user = array(
                    //$user->userInArray($payload['email'])
                //);
            }

            $content = $this->getPOST('comment');
            $serial = $user->getSerial();
            $article = explode('/', $articleParam)[0];

            $comment = new commentModel();
            $comment->create($content, $serial, $article);

            $this->redirect($url);
        }
        
        $this->redirect(SITE_ADRESS);
    }
}