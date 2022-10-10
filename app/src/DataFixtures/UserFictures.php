<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFictures extends Fixture
{
    private $hasher;

    public function __construct( UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("test20");
        $user->setEmail("test@hotmail.com");
        $user->setRoles([]);
        $encoded = $this->hasher->hashPassword($user, "test");
        $user->setPassword($encoded);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setActivationToken(null);
        $user->setEnabled(true);
        $user->setAvatar(null);

        $user2 = new User();
        $user2->setUsername("test21");
        $user2->setEmail("test21@hotmail.com");
        $user2->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user2, "test");
        $user2->setPassword($encoded);
        $user2->setCreatedAt(new \DateTimeImmutable());
        $user2->setActivationToken(null);
        $user2->setEnabled(true);
        $user2->setAvatar(null);


        $user3 = new User();
        $user3->setUsername("test22");
        $user3->setEmail("test22@hotmail.com");
        $user3->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user3, "test");
        $user3->setPassword($encoded);
        $user3->setCreatedAt(new \DateTimeImmutable());
        $user3->setActivationToken(null);
        $user3->setEnabled(true);
        $user3->setAvatar(null);


        $user4 = new User();
        $user4->setUsername("test23");
        $user4->setEmail("test23@hotmail.com");
        $user4->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user4, "test");
        $user4->setPassword($encoded);
        $user4->setCreatedAt(new \DateTimeImmutable());
        $user4->setActivationToken(null);
        $user4->setEnabled(true);
        $user4->setAvatar(null);


        $user5 = new User();
        $user5->setUsername("test24");
        $user5->setEmail("test24@hotmail.com");
        $user5->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user5, "test");
        $user5->setPassword($encoded);
        $user5->setCreatedAt(new \DateTimeImmutable());
        $user5->setActivationToken(null);
        $user5->setEnabled(true);
        $user5->setAvatar(null);

        $user6 = new User();
        $user6->setUsername("test225");
        $user6->setEmail("test25@hotmail.com");
        $user6->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user6, "test");
        $user6->setPassword($encoded);
        $user6->setCreatedAt(new \DateTimeImmutable());
        $user6->setActivationToken(null);
        $user6->setEnabled(true);
        $user6->setAvatar(null);

        $user7 = new User();
        $user7->setUsername("test26");
        $user7->setEmail("test26@hotmail.com");
        $user7->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user7, "test");
        $user7->setPassword($encoded);
        $user7->setCreatedAt(new \DateTimeImmutable());
        $user7->setActivationToken(null);
        $user7->setEnabled(true);
        $user7->setAvatar(null);

        $user8 = new User();
        $user8->setUsername("test27");
        $user8->setEmail("test27@hotmail.com");
        $user8->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user8, "test");
        $user8->setPassword($encoded);
        $user8->setCreatedAt(new \DateTimeImmutable());
        $user8->setActivationToken(null);
        $user8->setEnabled(true);
        $user8->setAvatar(null);

        $user9 = new User();
        $user9->setUsername("test28");
        $user9->setEmail("test28@hotmail.com");
        $user9->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user9, "test");
        $user9->setPassword($encoded);
        $user9->setCreatedAt(new \DateTimeImmutable());
        $user9->setActivationToken(null);
        $user9->setEnabled(true);
        $user9->setAvatar(null);

        $user10 = new User();
        $user10->setUsername("test29");
        $user10->setEmail("test29@hotmail.com");
        $user10->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user10, "test");
        $user10->setPassword($encoded);
        $user10->setCreatedAt(new \DateTimeImmutable());
        $user10->setActivationToken(null);
        $user10->setEnabled(true);
        $user10->setAvatar(null);

        $user11 = new User();
        $user11->setUsername("test30");
        $user11->setEmail("test30@hotmail.com");
        $user11->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user11, "test");
        $user11->setPassword($encoded);
        $user11->setCreatedAt(new \DateTimeImmutable());
        $user11->setActivationToken(null);
        $user11->setEnabled(true);
        $user11->setAvatar(null);

        $user12 = new User();
        $user12->setUsername("test31");
        $user12->setEmail("test31@hotmail.com");
        $user12->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user12, "test");
        $user12->setPassword($encoded);
        $user12->setCreatedAt(new \DateTimeImmutable());
        $user12->setActivationToken(null);
        $user12->setEnabled(true);
        $user12->setAvatar(null);

        $user13 = new User();
        $user13->setUsername("test32");
        $user13->setEmail("test32@hotmail.com");
        $user13->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user13, "test");
        $user13->setPassword($encoded);
        $user13->setCreatedAt(new \DateTimeImmutable());
        $user13->setActivationToken(null);
        $user13->setEnabled(true);
        $user13->setAvatar(null);

        $user14 = new User();
        $user14->setUsername("test33");
        $user14->setEmail("test33@hotmail.com");
        $user14->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user14, "test");
        $user14->setPassword($encoded);
        $user14->setCreatedAt(new \DateTimeImmutable());
        $user14->setActivationToken(null);
        $user14->setEnabled(true);
        $user14->setAvatar(null);

        $user15 = new User();
        $user15->setUsername("test34");
        $user15->setEmail("test34@hotmail.com");
        $user15->setRoles(["ROLE_ADMIN"]);
        $encoded = $this->hasher->hashPassword($user15, "test");
        $user15->setPassword($encoded);
        $user15->setCreatedAt(new \DateTimeImmutable());
        $user15->setActivationToken(null);
        $user15->setEnabled(true);
        $user15->setAvatar(null);

                  // $manager->persist($product);

        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($user5);
        $manager->persist($user6);
        $manager->persist($user7);
        $manager->persist($user8);
        $manager->persist($user9);
        $manager->persist($user10);
        $manager->persist($user11);
        $manager->persist($user12);
        $manager->persist($user13);
        $manager->persist($user14);
        $manager->persist($user15);
        $manager->flush();
    }
}
