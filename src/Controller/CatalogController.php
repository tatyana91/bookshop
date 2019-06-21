<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CatalogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(
                        SessionInterface $session,
                        BookRepository $book_repository)
    {
        $cart_count = (is_array($session->get('basket'))) ? count($session->get('basket')) : 0;
        return $this->render('catalog/catalog.html.twig', [
            'controller_name' => 'CatalogController',
            'books' => $book_repository->findAll(),
            'cart_count' => $cart_count
        ]);
    }

    /**
     * @Route("/buy/{idbook}", name="book_buy")
     */
    public function buy(
                        SessionInterface $session,
                        $idbook)
    {
        $basket = (is_array($session->get('basket'))) ? $session->get('basket') : array();
        $count_current_book = 0;
        if (isset($basket[$idbook])) {
            $count_current_book = $basket[$idbook];
        }
        $count_current_book++;
        $basket[$idbook] = $count_current_book;
        $session->set('basket', $basket);

        return $this->redirectToRoute('index');
    }
}
