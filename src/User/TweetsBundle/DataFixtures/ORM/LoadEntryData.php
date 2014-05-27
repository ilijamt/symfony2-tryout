<?php

namespace User\TweetsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use User\TweetsBundle\Entity\User;
use User\TweetsBundle\Entity\Entry;

class LoremIpsumGenerator {

    protected static $default_options = array(
        'p' => 1, // number of paragraphs
        'l' => 'medium', // paragraph length: short, medium, long, verylong
        'd' => 0, // Add <b> and <i> tags
        'a' => 0, // Add <a>
        'co' => 0, // Add <code> and <pre>
        'ul' => 0, // Add <ul>
        'ol' => 0, // Add <ol>
        'dl' => 0, // Add <dl>
        'bq' => 0, // Add <blockquote>
        'h' => 0, // Add <h1> through <h6>
        'ac' => 0, // Everything in ALL CAPS
        'pr' => 1, // Remove certain words like 'sex' or 'homo'
    );

    const URL = 'http://loripsum.net/generate.php';

    protected static function fetch(array $options) {
        $url = static::URL . '?' . http_build_query($options);
        return file_get_contents($url);
    }

    public static function generate(array $options = array()) {
        $options = array_merge(static::$default_options, $options);
        $lorem = static::fetch($options);
        $lorem = str_replace("href='http://loripsum.net/' target='_blank'", 'href="#"', $lorem); // Changes links
        $lorem = str_replace(" cite='http://loripsum.net'", '', $lorem); // Remove cite attribute
        $lorem = preg_replace('/<(\/?)mark>/', '', $lorem); // Remove mark
        $lorem = str_replace("<p>", "", $lorem);
        $lorem = str_replace("</p>", "", $lorem);
        return $lorem;
    }

    public static function generateRich(array $options = array()) {
        $options = array_merge(array(
            'p' => 7, // number of paragraphs
            'a' => 1, // Add <a>
            'ul' => 1, // Add <ul>
            'ol' => 1, // Add <ol>
            'bq' => 1, // Add <blockquote>
            'h' => 1, // Add <h1> through <h6>
                ), $options);

        $lorem = static::generate($options);

        return $lorem;
    }

    public static function generateWordpressPost(array $options = array()) {
        $lorem = static::generateRich($options);
        $lorem = preg_replace('/<(\/?)h1>/', '<$1b>', $lorem); // Replace h1 with b, not to conflict with page title
        $lorem = preg_replace('/<\/p>/s', "</p>\n<!--more-->", $lorem, 1); // Insert <!--more--> after first p

        return $lorem;
    }

}

class LoadEntryData extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {

        $items = array('user-1', 'user-2');
        
        for ($index = 0; $index < rand(50, 100); $index++) {
            $entry = new Entry();
            $entry->setEntry(LoremIpsumGenerator::generate());
            $usr = $this->getReference($items[\array_rand($items)]);
            $usrMerge = $manager->merge($usr);
            $entry->setUsername($usrMerge->getUsername());
            $entry->setName($usrMerge->getFirstName() . " " . $usrMerge->getLastName());
            $entry->setUserLink($usrMerge);
            $manager->persist($entry);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

}
