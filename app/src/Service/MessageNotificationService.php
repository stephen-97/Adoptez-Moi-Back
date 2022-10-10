<?php

namespace App\Service;

use App\Entity\User;

class MessageNotificationService {

    public function __construct()
    {
    }

    public function haveNewCommentOrNot(User $user)
    {
        $userHaveNewMessages = false;
        for($i = 0; $i < count($user->getCommentsReceived()->toArray()); $i++){
            if(!$user->getCommentsReceived()->toArray()[$i]->getIsRead()) {
                $userHaveNewMessages = true;
                break;
            }
        }
        return $userHaveNewMessages;
    }
}