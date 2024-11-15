<?php

namespace App\Controller;

use App\Entity\Zoo;
use App\Repository\ZooRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/zoo', name: 'app_api_zoo_')]
class ZooController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private ZooRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    ){
    }

    #[Route(name: 'new', methods: ['POST'])]
    public function new(Request $request) : JsonResponse
    {
        $zoo = $this->serializer->deserialize($request->getContent(), Zoo::class, 'json');

        $this->manager->persist($zoo);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($zoo, 'json');
        $location = $this->urlGenerator->generate('app_api_zoo_show',
            ['id' => $zoo->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);

    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id) : JsonResponse
    {
        $zoo = $this->repository->findOneBy(['id' => $id]);

        if ($zoo){
            $responseData = $this->serializer->serialize($zoo, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request) : JsonResponse
    {
        $zoo = $this->repository->findOneBy(['id' => $id]);
        if ($zoo) {
            $this->serializer->deserialize(
                $request->getContent(),
                Zoo::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $zoo]
            );

            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);

    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id) : JsonResponse
    {
        $zoo = $this->repository->findOneBy(['id' => $id]);
        if ($zoo){
            $this->manager->remove($zoo);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}