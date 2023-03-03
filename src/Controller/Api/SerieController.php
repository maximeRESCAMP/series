<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/serie', name: 'api_serie')]
class SerieController extends AbstractController
{
    #[Route('', name: 'retrieve_all', methods: "GET")]
    public function retrieveAll(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();
        //renvoie des donnée au format json en utilistant le groups
       return $this->json($series, 200,[],["groups"=>"serie-api"]);
    }
    #[Route('', name: 'retrieve_one', methods: "GET")]
    public function retrieveOne(int $id, SerieRepository $serieRepository): Response
    {
           $series = $serieRepository->find($id);
        //renvoie des donnée au format json en utilistant le groups
       return $this->json($series, 200,[],["groups"=>"serie-api"]);

    }

    #[Route('', name: 'add', methods: "POST")]
    public function add(Request $request, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        //serialiser ppermet de transformer en la donne JSON en instance de serie
        $serie = $serializer->deserialize($data,Serie::class, 'json');
        dd($serie);

        return $this->json('ok');
        //TODO return all series

    }
    #[Route('/{id}', name: 'remove', methods: "DELETE")]
    public function remove(): Response
    {
        //TODO return all series

    }
    #[Route('/{id}', name: 'update', methods: "PUT")]
    public function update(): Response
    {
        //TODO return all series

    }
}
