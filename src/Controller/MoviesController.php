<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Movie;
use App\Form\MovieFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MoviesController extends AbstractController
{
    private $em;
    /*public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }*/

    private $movieRepository;
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em) {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    //wyświetlenie wszystkich wpisó
    #[Route('/movies', methods: ['GET'], name: 'movies')]
    public function index(): Response {
        //$repository = $this->em->getRepository(Movie::class);
        //$movies = $repository->findOneBy(['id' => 5], ['id' => 'DESC']);
        //$movies = $repository->findIdGreaterThen(1);
        //$movies = $repository->getClassName();
        //dd($movies);

        $movies = $this->movieRepository->findAll();
        return $this -> render('movies/index.html.twig', array(
            'movies' => $movies));
    }

    //utworzenie nowego wpisu
    #[Route('movies/create', name: 'create_movie')]
    public function create(Request $request): Response {
        $movie = new Movie;
        $form = $this->createForm(MovieFormType::class, $movie); //utworzenie obiektu formy
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->get('imagePath')->getData() !== null) {
            $newMovie = $form->getData();
  
            $imagePath = $form->get('imagePath')->getData(); //wyciągnięcie wartości nazwy klucza ze zmiennej

            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension(); //zmiana nazwa pliku gdy już taki istnieje

                try {
                    $imagePath->move( //przeniesienie pliku do innego folderu
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } 
                catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                
                $newMovie->setImagePath('/uploads/' . $newFileName); //nowa ścieżka do pliku
            }

            //wykonanie zapytania
            $this->em->persist($newMovie); 
            $this->em->flush();

            return $this->redirectToRoute('movies'); //przekierowanie do strony (podaje się name z Route)

            //Do testowania
            //dd($newMovie);
            //exit;
        }

        return $this -> render('movies/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //edycja wpisu
    #[Route('movies/edit/{id}', name: 'edit_movie')]
    public function edit($id, Request $request): Response {
        $movie = $this->movieRepository->find($id);
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) { //czy obrazek istnieje
                //jak istnieje to wstawi go na serwer
                if ($movie->getImagePath() !== null) {
                    if (file_exists($this->getParameter('kernel.project_dir') . '/public' . $movie->getImagePath())) {
                  
                        $this->getParameter('kernel.project_dir') . $movie->getImagePath();
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                        try {
                            $imagePath->move( //przeniesienie pliku do innego folderu
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } 
                        catch (FileException $e) {
                            return new Response($e->getMessage());
                        }

                        //zmiana kolumn w bazie danych
                        $movie->setTitle($form->get('title')->getData());
                        $movie->setReleaseYear($form->get('releaseYear')->getData());
                        $movie->setDescription($form->get('description')->getData());
                        $movie->setImagePath('/uploads/' . $newFileName);
                        $this->em->flush();
                        return $this->redirectToRoute('movies');
                    }
                }
            }
            else {
                $movie->setTitle($form->get('title')->getData());
                $movie->setReleaseYear($form->get('releaseYear')->getData());
                $movie->setDescription($form->get('description')->getData());

                $this->em->flush();
                return $this->redirectToRoute('movies');
            }
        }

        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView()
        ]);
    }

    //usuwanie wpisu
    #[Route('movies/delete/{id}', name: 'delete_movie', methods: ['GET', 'DELETE'])]
    public function delete($id): Response {
        $movie = $this->movieRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();

        return $this->redirectToRoute('movies');
    }

    //wyświetlenie wybranego wpisu
    #[Route('/movies/{id}', methods: ['GET'], name: 'movie')]
    public function show($id): Response {
        $movie = $this->movieRepository->find($id);
        return $this -> render('movies/show.html.twig', [
            'movie' => $movie,
        ]);
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
