<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Movie;

class MoviesController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/movies', name: 'app_movies')]
    public function index(): Response {
        $repository = $this->em->getRepository(Movie::class);
        //$movies = $repository->findOneBy(['id' => 5], ['id' => 'DESC']);
        $movies = $repository->findIdGreaterThen(1);
        //$movies = $repository->getClassName();
        dd($movies);

        return $this -> render('index.html.twig');
    }
    
    
    //[Route('/movies', name: 'app_movies')]
    //#[Route('/movies/{name}', name: 'app_movies')] //{name} przy ścieżce pozwala na wyświetlenie strony z funkcji niżej z dowolnej ścieżki /nazwa_folderu/name, gdzie name jest dowolne
    #[Route('/', name: 'app_movies_old')]  //methods definiuje jakie metody są dostępne przy zdefiniowanej ścieżce
    public function indexOld(): Response {
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
