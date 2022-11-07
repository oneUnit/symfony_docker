<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Seller;
use App\Entity\Product;
use App\Entity\Region;
use App\Entity\Sale;
use App\Repository\ContactRepository;
use App\Repository\ProductRepository;
use App\Repository\RegionRepository;
use App\Repository\SaleRepository;
use App\Repository\SellerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SellerController extends AbstractController
{


    private ContactRepository $contactRepository;
    private SaleRepository $saleRepository;
    private ProductRepository $productRepository;
    private RegionRepository $regionRepository;
    private SellerRepository $sellerRepository;

    function __construct(SellerRepository $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
    }

    #[Route('/sellers/{id}', methods: ['GET'])]
    public function getSellerData(int $id): JsonResponse
    {
        $seller = $this->sellerRepository->find($id);

        if(!$seller){
            throw new NotFoundHttpException("User with id = $id doesn't exist");
        }

        return new JsonResponse(array(
            'first_name' => $seller->getFirstName(),
            'last_name'=> $seller->getLastName(),
            'date_joined' => $seller->getDateJoined()->format('Y-m-d')

        ));
    }

    #[Route('/sellers/{id}/contacts', methods: ['GET'])]
    public function getSellerContacts(int $id): JsonResponse
    {
        $seller = $this->sellerRepository->find($id);

        if(!$seller){
            throw new NotFoundHttpException("User with id = $id doesn't exist");
        }

        $contacts = $seller->getContacts();

        $arr = array();

        foreach($contacts as $contact){
            $arr[] = json_decode(json_encode($contact), true);
        }

        return new JsonResponse($arr);
    }

    #[Route('/sellers/{id}/sales', methods: ['GET'])]
    public function getSellerSales(int $id): JsonResponse
    {
        $seller = $this->sellerRepository->find($id);

        if(!$seller){
            throw new NotFoundHttpException("User with id = $id doesn't exist");
        }

        $contacts = $seller->getContacts();

        $arr = array();

        foreach($contacts as $contact){
            $arr[] = json_decode(json_encode($contact->getSaleId()), true);
        }

        return new JsonResponse($arr);
    }

}