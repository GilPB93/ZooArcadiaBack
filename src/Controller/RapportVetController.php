<?php

namespace App\Controller;

use App\Entity\RapportVet;
use App\Repository\RapportVetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/rapportvet', name: 'app_api_rapportvet_')]
class RapportVetController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RapportVetRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    ){
    }

    #[Route(name: 'new', methods: ['POST'])]
    public function new(Request $request) : JsonResponse
    {
        $rapportvet = $this->serializer->deserialize($request->getContent(), RapportVet::class, 'json');
        $rapportvet->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($rapportvet);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($rapportvet, 'json');
        $location = $this->urlGenerator->generate('app_api_rapportvet_show',
            ['id' => $rapportvet->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);

    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id) : JsonResponse
    {
        $rapportvet = $this->repository->findOneBy(['id' => $id]);

        if ($rapportvet){
            $responseData = $this->serializer->serialize($rapportvet, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request) : JsonResponse
    {
        $rapportvet = $this->repository->findOneBy(['id' => $id]);
        if ($rapportvet) {
            $this->serializer->deserialize(
                $request->getContent(),
                RapportVet::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $rapportvet]
            );

            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);

    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id) : JsonResponse
    {
        $rapportvet = $this->repository->findOneBy(['id' => $id]);
        if ($rapportvet){
            $this->manager->remove($rapportvet);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
