<?php

namespace App\Service;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\JsonWebTokenService;

class UserService {

    private $userRepository;
    private $em;
    private $jwtService;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, JsonWebTokenService $jwtService)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->jwtService = $jwtService;
    }

    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }

    public function deleteALlUsersWithDeprecatedToken(){
        $data = $this->userRepository->findUserWithDepreacedActivationToken()->getResult();
        $entityManager = $this->em;
        $count = 0;
        foreach($data as $user) {
            if($this->jwtService->tokenActivationHasExpired($user->getActivationToken())){
                $entityManager->remove($user);
                $entityManager->flush();
                $count++;
            }
        }
        return $count;
    }

    public function findUserByid($id)
    {
        return $this->userRepository->find($id);
    }

    public function  findUserByUsername($username){
        return $this->userRepository->findByUserName($username);
    }

    public function passwordIsCorrect(String $password){
        if(strlen($password) < 8 ) return false;
        if(!preg_match('~[0-9]+~', $password)) return false;
        if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) return false;
        return true;
    }
    public function emailIsCorrect(String $email){
       if(filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
       return false;
    }
    public function emailIsAlreadyTaken(String $email){
        $emailCheck = $this->userRepository->findOneBy(['email' => $email ]);
        if(!empty($emailCheck)){
            return true;
        }
        return false;
    }
    public function accountIsNotEnabledYet(String $email){
        $emailCheck = $this->userRepository->findOneBy(['email' => $email ]);
        if($emailCheck){
            if($emailCheck->getEnabled()===false) return true;
        }
        return false;
    }
    public function usernameIsAlreadyTaken(String $username){
        $usernameCheck =$this->userRepository->findOneBy(['username' => $username ]);
        if(!empty($usernameCheck)){
            return true;
        }
        return false;
    }
}