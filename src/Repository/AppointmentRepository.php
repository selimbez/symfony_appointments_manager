<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;

/**
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    function findCustomers($assignee) {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select("a.customer AS name")
            ->distinct()
            ->from("App:Appointment", "a")
            ->where("a.assignee = :assignee")
            ->setParameter("assignee", $assignee)
            ->getQuery()
            ->getResult();
    }

    function findByFilter($assignee, $customer, $fromDate, $toDate, $status) {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select("a")
            ->from("App:Appointment", "a")
            ->where("a.assignee = :assignee")
            ->setParameter("assignee", $assignee);

        if (!empty($customer)) {
            $queryBuilder = $queryBuilder->andWhere("a.customer = :customer")->setParameter("customer", $customer);
        }
        if (!empty($fromDate)) {
            $queryBuilder = $queryBuilder->andWhere("a.date >= :fromDate")->setParameter("fromDate", $fromDate);
        }
        if (!empty($toDate)) {
            $queryBuilder = $queryBuilder->andWhere("a.date <= :toDate")->setParameter("toDate", $toDate);
        }
        if (!empty($status) && in_array($status, ["1", "2"])) {
            $queryBuilder = $queryBuilder->andWhere("a.complete = :complete")->setParameter("complete", $status == "2");
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
