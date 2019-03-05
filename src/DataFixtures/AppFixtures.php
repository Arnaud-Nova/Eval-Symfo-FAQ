<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Role;
use App\Entity\User;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder =$passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $roleAdmin = new Role();
        $roleAdmin->setCode('ROLE_ADMIN');
        $roleAdmin->setName('Administrateur');

        $roleModo = new Role();
        $roleModo->setCode('ROLE_MODO');
        $roleModo->setName('Modérateur');

        $roleUser = new Role();
        $roleUser->setCode('ROLE_USER');
        $roleUser->setName('Membre');

        $manager->persist($roleAdmin);
        $manager->persist($roleModo);
        $manager->persist($roleUser);

        $admin = new User();
        $admin->setEmail('admin@test.fr');
        $admin->setUsername('admin');
        $encodedPassword = $this->passwordEncoder->encodePassword($admin, 'admin');
        $admin->setPassword($encodedPassword);
        $admin->setRole($roleAdmin);
        $admin->setIsActive(true);

        $modo = new User();
        $modo->setEmail('modo@test.fr');
        $modo->setUsername('modo');
        $encodedPassword = $this->passwordEncoder->encodePassword($modo, 'modo');
        $modo->setPassword($encodedPassword);
        $modo->setRole($roleModo);
        $modo->setIsActive(true);

        $user = new User();
        $user->setEmail('user@test.fr');
        $user->setUsername('user');
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'user');
        $user->setPassword($encodedPassword);
        $user->setRole($roleUser);
        $user->setIsActive(true);

        $manager->persist($admin);
        $manager->persist($modo);
        $manager->persist($user);

        $manager->flush();
    }
}
