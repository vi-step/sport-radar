<?php

namespace App\Service\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @template T
 */
abstract class BaseRepository extends EntityRepository
{
    /**
     * @template T
     * @param object $entity
     * @param bool $flush
     * @return T
     */
    final public function save(object $entity, bool $flush = true): object
    {
        $this->_em->persist($entity);
        $flush && $this->_em->flush();
        return $entity;
    }

    final public function delete(object $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        $flush && $this->_em->flush();
    }

    public function deleteAll(): void
    {
        $this->createQueryBuilder('t')
            ->delete()
            ->getQuery()
            ->execute();
    }

}
