<?php

namespace User\TweetsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EntryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntryRepository extends EntityRepository {

    public function getLatestQuery($timestamp, $user_id = null) {

        $date = new \DateTime();
        $date->setTimestamp($timestamp);

        $qb = $this->createQueryBuilder('e')
                ->select('e')
                ->where('e.updated > :date')
                ->addOrderBy('e.updated', 'DESC')
                ->setParameter('date', $date, \Doctrine\DBAL\Types\Type::DATETIME);

        if (!is_null($user_id)) {
            $qb->andWhere('e.user_link = :user_id')->setParameter('user_id', $user_id);
        }

        return $qb->getQuery();
    }

    public function getLatest($timestamp, $user_id = null) {
        return $this->getLatestQuery($timestamp, $user_id)->getResult();
    }

    public function getEntry($entry_id) {
        return $this->getEntryQuery($entry_id)->getResult();
    }

    public function getEntryQuery($entry_id) {

        $qb = $this->createQueryBuilder('e')
                ->select('e')
                ->where('e.id = :entry_id')
                ->addOrderBy('e.updated', 'DESC')
                ->setParameter('entry_id', $entry_id);

        return $qb->getQuery();
    }

    public function getEntriesQuery($user_id = null) {
        $qb = $this->createQueryBuilder('e')
                ->select('e')
                ->addOrderBy('e.updated', 'DESC');

        if (!is_null($user_id)) {
            $qb->where('e.user_link = :user_id')->setParameter('user_id', $user_id);
        }

        return $qb->getQuery();
    }

    public function getEntries($user_id = null) {
        return $this->getEntriesQuery($user_id)->getResult();
    }

}
