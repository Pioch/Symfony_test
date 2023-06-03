<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    //[Route('/movies', name: 'app_movies')]
    //#[Route('/movies/{name}', name: 'app_movies')] //{name} przy ścieżce pozwala na wyświetlenie strony z funkcji niżej z dowolnej ścieżki /nazwa_folderu/name, gdzie name jest dowolne
    #[Route('/movies', name: 'app_movies')]  //methods definiuje jakie metody są dostępne przy zdefiniowanej ścieżce
    public function index(): Response {
        $movies = ["Film1", "Film2", "Film3"];
        return $this -> render('index.html.twig', array(
            'movies' => $movies
        ));
    }

    #[Route('/old', name: 'old')] //Ustawienie ścieżki
    public function oldMethod(): Response {
        return $this->render('movies/index.html.twig', [
            'controller_name' => 'MoviesController',
        ]);
    }
}
