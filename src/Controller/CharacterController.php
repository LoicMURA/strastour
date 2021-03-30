<?php

namespace App\Controller;

use App\Entity\{Character, Inventory};
use App\Repository\{CharacterRepository, InventoryRepository, ItemRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/profile")
 */
class CharacterController extends AbstractController
{
    /**
     * @Route("/stuck-update", name="stuck_edit", methods={"POST"})
     */
    public function updateStucks(
        Request $request,
        EntityManagerInterface $manager,
        Security $security,
        CharacterRepository $characterRepository
    ): Response
    {
        $character = $characterRepository->findOneBy(['user' => $security->getUser()]);
        $stucks = $request->request->get('stucks');
        $character->setStuck($stucks);
        $manager->flush();
        return $this->json('Votre compte à bien été crédité de '.$stucks.' Stucks');
    }

    /**
     * @Route("/xp-update", name="xp_edit", methods={"POST"})
     */
    public function updateXp(
        Request $request,
        EntityManagerInterface $manager,
        Security $security,
        CharacterRepository $characterRepository
    ): Response
    {
        $character = $characterRepository->findOneBy(['user' => $security->getUser()]);
        $xp = $request->request->get('xp');
        $character->setXp($xp);
        $manager->flush();
        return $this->json('Le joueur a maintenant '.$xp.' points d\'XP');
    }

    /**
     * @Route("/inventory-update", name="inventory_edit", methods={"POST"})
     */
    public function updateInventory(
        Request $request,
        EntityManagerInterface $manager,
        Security $security,
        CharacterRepository $characterRepository,
        InventoryRepository $inventoryRepository,
        ItemRepository $itemRepository
    ): Response
    {
        $character = $characterRepository->findOneBy(['user' => $security->getUser()]);
        $message = [];
        $i = 1;
        while($inventory = json_decode($request->request->get("inventory-$i"))) {
            $quantity = $inventory->quantity;
            if ($quantity > 0) {
                if ($item = $inventoryRepository->findOneBy([
                    'player' => $character,
                    'item' => $inventory->id
                    ])
                ) {
                    $item->setQuantity($quantity)
                         ->setQuality($inventory->quality);

                    $manager->flush();
                } else {
                    $item = (new Inventory())
                        ->setPlayer($character)
                        ->setItem($itemRepository->find($inventory->id))
                        ->setQuantity($quantity)
                        ->setQuality($inventory->quality);

                    $manager->persist($item);
                    $manager->flush();
                }

                $message[] = "Le joueur à maintenant $quantity items $inventory->id de qualité $inventory->quality";
            } else if ($quantity == 0) {
                $message[] = $this->deleteInventory(
                    $inventory->id,
                    $manager,
                    $character,
                    $inventoryRepository);
            }
            $i++;
        }
        return $this->json($message);
    }

    private function deleteInventory(
        int $id,
        EntityManagerInterface $manager,
        Character $character,
        InventoryRepository $inventoryRepository
    )
    {
        $message = '';
        $inventory = $inventoryRepository->findOneBy([
            'player' => $character,
            'item' => $id
        ]);
        if ($inventory) {
            $manager->remove($inventory);
            $manager->flush();
            $message = 'Le joueur n\'as maintenant plus d\'item '.$id;
        }
        return $message;
    }
}
