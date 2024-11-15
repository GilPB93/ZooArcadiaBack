<?php

namespace App\Controller;

use App\Entity\RapportAlimentation;
use App\Repository\RapportAlimentationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/rapportalimentation', name: 'app_api_rapportalimentation_')]
class RapportAlimentationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RapportAlimentationRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    ){
    }

    #[Route(name: 'new', methods: ['POST'])]
    public function new(Request $request) : JsonResponse
    {
        $rapportalimentation = $this->serializer->deserialize($request->getContent(), RapportAlimentation::class, 'json');
        $rapportalimentation->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($rapportalimentation);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($rapportalimentation, 'json');
        $location = $this->urlGenerator->generate('app_api_rapportalimentation_show',
            ['id' => $rapportalimentation->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);

    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id) : JsonResponse
    {
        $rapportalimentation = $this->repository->findOneBy(['id' => $id]);

        if ($rapportalimentation){
            $responseData = $this->serializer->serialize($rapportalimentation, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request) : JsonResponse
    {
        $rapportalimentation = $this->repository->findOneBy(['id' => $id]);
        if ($rapportalimentation) {
            $this->serializer->deserialize(
                $request->getContent(),
                RapportAlimentation::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $rapportalimentation]
            );

            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);

    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id) : JsonResponse
    {
        $rapportalimentation = $this->repository->findOneBy(['id' => $id]);
        if ($rapportalimentation){
            $this->manager->remove($rapportalimentation);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
