<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BasketController extends AbstractController
{
    /**
     * @Route("/basket", name="basket")
     */
    public function index(SessionInterface $session, BookRepository $book_repository)
    {
        $basket = $session->get('basket');
        $books = array();
        if (is_array($basket)) {
            $keys = array_keys($basket);
            $keys_string = implode(",", $keys);

            $books = $book_repository->createQueryBuilder('b')
                ->andWhere('b.idbook IN (' . $keys_string . ')')
                ->getQuery()
                ->getResult();

            foreach ($books as &$book){
                $book->quantity = $basket[$book->getIdBook()];
                $book->cost = $book->quantity * $book->getPrice();
            }
        }

        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
            'books' => $books
        ]);
    }

    /**
     * @Route("/basket/delete/{idbook}", name="basket_delete")
     */
    public function delete(SessionInterface $session, BookRepository $book_repository)
    {

    }

    /**
     * @Route("/basket/send", name="basket_send")
     */
    public function send(SessionInterface $session, BookRepository $book_repository)
    {

    }
}
