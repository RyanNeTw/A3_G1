<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderAddDrinksController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
        * @Route("/api/orders/{id}/addDrink", name="api_order_add_drinks", methods={"POST"})
    */
    public function addDrinksToOrder(int $id, Request $request): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);
        if (count($data) === 0) {
            return  new JsonResponse(['message' => 'Error']);
        }

        $order = $this->$entityManager->getRepository(Order::class)->findOneById($id);
        echo $order;
        if (isset($order)) {
            return new JsonResponse(['message' => 'Order Payed' ]);
        }

        $conn = $this->entityManager->getConnection();
        $sql = 'INSERT INTO "order_drink" ("order_id", "drink_id") VALUES (:id, :drinkId)';

        foreach ($data as $drink) {
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $id, 'drinkId' => $drink]);
        }

        return new JsonResponse(['message' => 'Added successfuly' ]);
    }


}
