<?php

namespace App\Controller;

use App\Entity\TransactionComment;
use App\Entity\User;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/transactions', name: 'transactions', methods: ['GET'])]
    public function transactions(
        Request $request,
        TransactionRepository $transactionRepository,
    ): Response
    {
        $result = [];

        $filters = $request->get('filters', []);


        foreach ($transactionRepository->findBy($filters) as $transaction) {
            $result[] = [
                'id' => $transaction->getId(),
                'created' => $transaction->getCreated()->format('Y-m-d H:i:s'),
                'updated' => $transaction->getUpdated()->format('Y-m-d H:i:s'),
                'author' =>$this->userToArray($transaction->getAuthor()),
                'user' =>$this->userToArray($transaction->getUser()),
                'value' => $transaction->getValue(),
                'status' => $transaction->getStatus(),
                'approvers' => array_map(fn($approver) => $this->userToArray($approver), $transaction->getApprovers()->toArray()),
                'description' => $transaction->getDescription(),
                'comments' => array_map(fn($c) => $this->commentToArray($c), $transaction->getTransactionComments()->toArray()),
            ];
        }
        return $this->json($result);
    }

    private function commentToArray(?TransactionComment $comment) {
        if(!$comment) {
            return null;
        }
        return [
            'id' => $comment->getId(),
            'text' => $comment->getText(),
            'author' => $this->userToArray($comment->getAuthor()),
            'created' => $comment->getCreated()->format('Y-m-d H:i:s'),
            'updated' => $comment->getUpdated()->format('Y-m-d H:i:s'),
        ];

    }
    private function userToArray(?User $user)
    {
        if(!$user) {
            return null;
        }
        return [
            'id' => $user->getId(),
            'firstName' => $user->getFirstname(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
        ];
    }
}
