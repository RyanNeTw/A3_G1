<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;




class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
        * @Route("/api/orders/{id}/pay", name="api_order_pay", methods={"GET"})
    */
    public function payOrder(int $id): JsonResponse
    {
        $conn = $this->entityManager->getConnection();

        $sql = 'UPDATE "order" SET "order_status" = :status WHERE "id" = :id';

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(['status' => 'Paid', 'id' => $id]);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

        return new JsonResponse(['message' => 'Order has been paid successfully.']);
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

        $conn = $this->entityManager->getConnection();
        $sql = 'INSERT INTO "order_drink" ("order_id", "drink_id") VALUES (:id, :drinkId)';

        foreach ($data as $drink) {
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $id, 'drinkId' => $drink]);
        }

        return new JsonResponse(['message' => 'Added successfuly' ]);
    }


    /**
        * @Route("/api/orders/{orderId}/removeDrink/{drinkId}", name="api_order_remove_drink", methods={"DELETE"})
    */
    public function removeDrinkFromOrder(int $orderId,  int $drinkId): JsonResponse 
    {

        if (empty($orderId) or empty($drinkId)) {
            return new JsonResponse(['error' => 'Error'], 404);
        }

        $conn = $this->entityManager->getConnection();
        $sql = 'DELETE FROM "order_drink" WHERE "drink_id“ = :drink_id AND "order_id" = :order_id';
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(['drink_id' => $drinkId, 'order_id' => $orderId]);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
        return new JsonResponse(['message' => 'Drink has been removed.']);
    }

}
