<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utils\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//attribut de la class qui permet de mutualiser des informations
#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list/{page}', name: 'list', requirements: ['page' => '\d+'], methods: "GET")]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        //TODO Récupérer la liste des series en BDD
        //on récupère toutes les series en passant par le repository
        //$series = $serieRepository->findAll();

        //utilisation de findBy avec un tableau de clause WHERE, ORDER BY
        //$series = $serieRepository->findBy(["status" => "ended"], ["popularity" => 'DESC'], 10);

        //méthode magique qui est créé dynamiquement en fonction des attributs de l'entité associé
        //$series = $serieRepository->findByStatus("ended");

        //récupération des 50 series les mieux notées
        //$series =$serieRepository->findBy([], ["vote" => "DESC"], 50);

        //nombre de séries dans ma table
        $nbSerieMax = $serieRepository->count([]);
        $maxPage = ceil($nbSerieMax / SerieRepository::SERIE_LIMIT);

        if($page >= 1 && $page <= $maxPage){
            //utilisation d'une requête personnalisée
            $series = $serieRepository->findBestSeries($page);
        }else{
            throw $this->createNotFoundException("Oops ! Page not found !");
        }


        return $this->render('serie/list.html.twig', [
            //on envoie les données à la vue
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        //récupération d'une série par son id
        $serie = $serieRepository->find($id);

        if(!$serie){
            //lance une erreur 404 si la série n'existe pas
            throw $this->createNotFoundException("Oops ! Serie not found !");
        }

        return $this->render('serie/show.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(
        SerieRepository $serieRepository,
        Request $request,
        Uploader $uploader
    ): Response
    {
        //renvoie une 403
        //$this->createAccessDeniedException();

        $serie = new Serie();

        //création d'une intance de form lié à une instance de série
        $serieForm = $this->createForm(SerieType::class, $serie);

        //méthode qui extrait les éléments du formulaire de la requête
        $serieForm->handleRequest($request);

        if($serieForm->isSubmitted() && $serieForm->isValid()){

            //upload photo
            /**
             * @var UploadedFile $file
             */
            $file = $serieForm->get('poster')->getData();
        //appel à l'uploader
           $newFileName = $uploader->upload($file,$this->getParameter('upload_serie_poster'),
            $serie->getName());
            $serie->setPoster($newFileName);

            //sauvegarde en BDD la nouvelle série
            $serieRepository->save($serie, true);

            $this->addFlash("success", "Serie added !");

            //redirige vers la page de détail de la série
            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        dump($serie);

        //TODO Créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(int $id, SerieRepository $serieRepository){
        //récupération de la série
        $serie = $serieRepository->find($id);

        if($serie){
            //je le supprime
            $serieRepository->remove($serie, true);
            $this->addFlash("warning", "Serie deleted !");
        }else{
            throw $this->createNotFoundException("This serie can't be deleted !");
        }

        return $this->redirectToRoute('serie_list');
    }






}