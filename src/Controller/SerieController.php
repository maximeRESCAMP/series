<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//attribut de la classe qui permet de mutualiser des informations
#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //TODO
        //on recupere toutes les series en passant par le reportirie
        //$series = $serieRepository->findAll();
        //utilisation de findBy avec un tableau de clause WHERE
        //$series = $serieRepository->findBy(["status" => "ended","genres"=>"Comedy"],["popularity"=> "DESC"],10,2 );
        //recuperation des 50  series les mieux note
        $series = $serieRepository->findBy([],["vote"=> "DESC"], 50);

        dump($series);
        return $this->render('serie/list.html.twig',[
            //eon envoie les donnes a la vue
            'series'=> $series ]);
    }


    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
//
        if(!$serie){
            //lance une erreur 404 si la serie n'existe pas
            throw  $this->createNotFoundException('oo bouletto Serie not found!');
        }
        dump($serie);
        //TODO Récuperation de l'info de la serie
        return $this->render('serie/show.html.twig',[
            'serie'=> $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serie->setName('Le magicien')
            ->setBackdrop("backadrop.png")
            ->setDateCreated(new \DateTime())
            ->setGenres('Comedy')
            ->setFirstAirDate(new \DateTime('2022-02-02'))
            ->setLastAirDate(new \DateTime("-6 month"))
            ->setPopularity(850.52)
            ->setPoster("poster.png")
            ->setVote(8.5)
            ->setTmdbId(123456)
            ->setStatus("Ended");


//        dump($serie);
//        //enregistrement en BDD
//        $serieRepository->save($serie, true);

//        $serie->setName("last of us");
//        $serieRepository->save($serie, true);
//        dump($serie);
//utilisation directement de l'entite manager
        $entityManager-> persist(($serie));
        $entityManager->flush();
        //pour suprimer une instance
        $serieRepository->remove($serie, true);

        //TODO Crée un formulaire d'ajout de série
        return $this->render('serie/add.html.twig',);
    }

}
