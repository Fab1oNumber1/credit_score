<?php

namespace App\Service;


use App\Entity\Notification;
use App\Entity\Transaction;
use App\Entity\TransactionComment;
use App\Repository\NotificationRepository;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NotificationRepository $notificationRepository,
    )
    {
    }

    public function notify(Transaction|TransactionComment $obj)
    {
        $notification = new Notification();

        switch (ClassUtils::getRealClass($obj::class)) {
            case Transaction::class:
                $notification->setType('transaction');
                $notification->setTransaction($obj);
                $notification->setMessage("{$obj->getAuthor()} hat einen Eintrag erstellt.");
                break;
            case TransactionComment::class:
                $notification->setType('transaction_comment');
                $notification->setTransactionComment($obj);
                $notification->setMessage("{$obj->getAuthor()} hat ein Kommentar geschrieben.");
                break;
            default:
                $notification->setType('');
        }
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification;
    }

    public function getLatest()
    {
        return $this->notificationRepository->findBy([], ['created' => 'DESC'], 5);
    }
}