<?php
namespace User\TweetsBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{

    public function mainNLMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'index'));
        $menu->addChild('Login', array('route' => 'login'));
        return $menu;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'index'));
	$menu->addChild('My tweets', array( 'route' => 'my_tweets' ));
        $menu->addChild('Profile', array( 'route' => 'profile' ));        
        $menu->addChild('Logout', array( 'route' => 'logout' ));
        return $menu;
    }
}

