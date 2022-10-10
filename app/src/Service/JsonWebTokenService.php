<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Firebase\JWT\JWT;
use App\Entity\User;

class JsonWebTokenService {

    private $params;
    private $em;

    public function __construct(ContainerBagInterface $params, EntityManagerInterface $em)
    {
        $this->params = $params;
        $this->em = $em;
    }

    public function getUser($credentials)
    {
        try {
            $jwt = (array) JWT::decode(
                $credentials,
                $this->params->get('jwt_secret'),
                ['HS256']
            );
            return $this->em->getRepository("App\Entity\User")
                ->findOneBy([
                    'username' => $jwt['user'],
                ]);
        } catch (\Exception $exception) {
            if($exception->getMessage() == "Expired token"){
                return new JsonResponse(["message" => "Connexion expiré", "status" => 401]);
            }
            return new JsonResponse(["message" => "Problème serveur", "status" => 500]);
        }
    }

    public function newToken (User $user, $parameter){
        $userHaveNewMessages = false;
        for($i = 0; $i < count($user->getCommentsReceived()->toArray()); $i++){
            if(!$user->getCommentsReceived()->toArray()[$i]->getIsRead()) {
                $userHaveNewMessages = true;
                break;
            }
        }
        $payload = [
            "user" => $user->getUsername(),
            "email" => $user->getEmail(),
            "avatar" => $user->getAvatar(),
            "role" => $user->getRoles(),
            "createdAt" => $user->getCreatedAt(),
            "newMessages" => $userHaveNewMessages,
            "exp"  => (new \DateTime())->modify("+5 minutes")->getTimestamp(),
        ];
        $jwt = JWT::encode($payload, $parameter, 'HS256');
        return ['token' => $jwt];
    }

    public function tokenActivationHasExpired($credentials){
        try {
            JWT::decode($credentials,$this->params->get('jwt_secret'),['HS256']);
            return false;
        } catch (\Exception $exception) {
            if($exception->getMessage() == "Expired token"){
                return true;
            }
            return true;
        }
    }

}