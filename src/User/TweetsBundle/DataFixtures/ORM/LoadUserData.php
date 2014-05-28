<?php

namespace User\TweetsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use User\TweetsBundle\Entity\User;

//class LoadUserData extends AbstractFixture implements ContainerAwareInterface{
class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {

        $user = new User();
        $user->setUsername("admin");
        $user->setSalt(md5(uniqid()));
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('admin', $user->getSalt()));
        $user->setEmail("admin@admin.com");
        $user->setFirstName('Admin');
        $user->setLastName('Administrator');

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user-1', $user);

        $user = new User();
        $user->setUsername("demo");
        $user->setSalt(md5(uniqid()));
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('demo', $user->getSalt()));
        $user->setEmail("demo@demo.com");
        $user->setFirstName('Demo');
        $user->setLastName('Demonstration');

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user-2', $user);
    
        $user = new User();
        $user->setUsername("newuser");
        $user->setSalt(md5(uniqid()));
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('newuser', $user->getSalt()));
        $user->setEmail("newuser@newuser.com");
        $user->setFirstName('New');
        $user->setLastName('User');

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user-3', $user);
    }

    public function getOrder() {
        return 1;
    }

}
