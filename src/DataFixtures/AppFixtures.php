<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder =$passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $admin = new User();
        $admin->setEmail('admin@test.fr');
        $admin->setUsername('admin');
        $encodedPassword = $this->passwordEncoder->encodePassword($admin, 'admin');
        $admin->setPassword($encodedPassword);
        $admin->setRoles('{"name": "Administrateur", "code": "ROLE_ADMIN"}');
        $admin->setIsActive(true);

        $modo = new User();
        $modo->setEmail('modo@test.fr');
        $modo->setUsername('modo');
        $encodedPassword = $this->passwordEncoder->encodePassword($modo, 'modo');
        $modo->setPassword($encodedPassword);
        $modo->setRoles('{"name": "Modérateur", "code": "ROLE_MODO"}');
        $modo->setIsActive(true);

        $user = new User();
        $user->setEmail('user@test.fr');
        $user->setUsername('user');
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'user');
        $user->setPassword($encodedPassword);
        $user->setRoles('{"name": "Membre","code": "ROLE_USER"}');
        $user->setIsActive(true);

        $user2 = new User();
        $user2->setEmail('user2@test.fr');
        $user2->setUsername('user2');
        $encodedPassword = $this->passwordEncoder->encodePassword($user2, 'user2');
        $user2->setPassword($encodedPassword);
        $user2->setRoles('{"name": "Membre","code": "ROLE_USER"}');
        $user2->setIsActive(true);

        $manager->persist($admin);
        $manager->persist($modo);
        $manager->persist($user);
        $manager->persist($user2);

        $generator = Factory::create('fr_FR');

        $populator = new \Faker\ORM\Doctrine\Populator($generator, $manager);

        $populator->addEntity('App\Entity\Question', 10, array(
            'title' => function() use ($generator) { return $generator->sentence(3, true); },
            'content' => function() use ($generator) { return $generator->realText(200, 2); },
            'isActive' => true,
            'author' => $user,
            'createdAt' => function() use ($generator) { return $generator->dateTimeThisMonth(); },
        ));

        $populator->addEntity('App\Entity\Tag', 6, array(
            'name' => function() use ($generator) { return $generator->word(); },
        ));

        $inserted = $populator->execute();

        $questions = $inserted['App\Entity\Question'];
        $tags = $inserted['App\Entity\Tag'];

        foreach ($questions as $question) {

            shuffle($tags);
            $question->addTag($tags[0]);
            $question->addTag($tags[1]);
            $question->addTag($tags[2]);

            $manager->persist($question);
        }

        $manager->flush();

        foreach ($questions as $question) {

            for ($i = 0; $i < 8; $i++) {
                $answer = new Answer();
                $answer->setContent($generator->realText(200, 2))
                    ->setIsActive(true)
                    ->setAuthor($user2)
                    ->setQuestion($question)
                    ->setCreatedAt($generator->dateTimeThisMonth());

                $manager->persist($answer);
                $question->addAnswer($answer);
            }
            
            $manager->persist($question);
        }

        $manager->flush();
    }
}
