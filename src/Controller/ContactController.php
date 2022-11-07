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

class ContactController extends AbstractController
{


    private ContactRepository $contactRepository;
    private SaleRepository $saleRepository;
    private ProductRepository $productRepository;
    private RegionRepository $regionRepository;
    private SellerRepository $sellerRepository;

    function __construct(ContactRepository $contactRepository, SaleRepository $saleRepository, ProductRepository $productRepository,
        RegionRepository $regionRepository, SellerRepository $sellerRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->saleRepository = $saleRepository;
        $this->productRepository = $productRepository;
        $this->regionRepository = $regionRepository;
        $this->sellerRepository = $sellerRepository;
    }

    //, condition: "request.headers.get('Content-Type') === 'text/csv'

    #[Route('/load', methods: ["POST"])]
    public function load(Request $request): JsonResponse
    {
        // ...
        $base64Csv = $request->request->get('base64file');

        $strCsv = base64_decode($base64Csv);

        $strCsvArr =  explode("\n", $strCsv);

        if(count($strCsvArr) <= 2){
            throw new HttpException(403, "Empty Csv File");
        }


        $arr = array();
        $keys = str_getcsv($strCsvArr[0], ";");

        for($i = 1; $i < count($strCsvArr); $i++){
            $lineArr = str_getcsv($strCsvArr[$i], ";");
            for($j = 0; $j < count($lineArr); $j++){
                $arr[$i - 1][$keys[$j]] = $lineArr[$j];
            }
        }

        print_r($arr);

        foreach($arr as $row){
            
            $contact = $this->contactRepository->find(Uuid::fromString($row['uuid']));
            
            if(!$contact){
                $contact = new Contact();
            }

            if(!empty($row['uuid'])){
                $contact->setId(Uuid::fromString($row['uuid']));
            }
            else{
                $contact->setId(Uuid::v4());
            }
        
            $contact->setDate(DateTime::createFromFormat(format:"Y-m-d", datetime: $row["contact_date"]));
            $contact->setType($row['contact_type']);
            $contact->setCustomerFullName($row['contact_customer_fullname']);

            $seller = $this->sellerRepository->find($row['seller_id']);

            if (!$seller){
                $seller = new Seller();
                $seller->setId($row['seller_id']);
                $seller->setFirstName($row['seller_firstname']);
                $seller->setLastName($row['seller_lastname']);
                $seller->setDateJoined(DateTime::createFromFormat(format:"Y-m-d", datetime: $row['date_joined']));
                $this->sellerRepository->add($seller, false);
            }

            $product = $this->productRepository->find($row['contact_product_type_offered_id']);

            if(!$product){
                $product = new Product();
                $product->setId($row['contact_product_type_offered_id']);
                $product->setName($row['contact_product_type_offered']);
                $this->productRepository->add($product, false);
            }

            $region = $this->regionRepository->findByNameAndCountryCode(name: $row['contact_region'], country_code: $row['country']);
            if(!$region){
                $region = new Region();
                $region->setName($row['contact_region']);
                $region->setCountryCode($row['country']);
                $this->regionRepository->add($region, false);
            }

            $sale = $contact->getSaleId();

            if(!empty($row['sale_net_amount'])){
                $sale = !$sale ? new Sale() : $sale;
                $sale->setGrossAmount($row['sale_gross_amount']);
                $sale->setNetAmount($row['sale_net_amount']);
                $sale->setTaxRate($row['sale_tax_rate']);
                $sale->setCost($row['sale_product_total_cost']);
                $this->saleRepository->add($sale, false);
            }

            $contact->setSellerId($seller);
            $contact->setProductId($product);
            $contact->setRegionId($region);
            
            if($sale){
                $contact->setSaleId($sale);
            }

            $this->contactRepository->add($contact, true);


        }

        return new JsonResponse(array(
            'result' => "success"
        ));
    }

    #[Route('/sellers/sales/{year}', methods: ['GET'])]
    public function getSales(int $year): JsonResponse
    {
        $contacts = $this->contactRepository->findSalesByYear($year);

        $arr = array();

        $gross_amount = "0.0";
        $net_amount = "0.0";
        $tax_amount = "0.0";
        $profit = "0.0";
        $profit_percent = "0.0";


        foreach($contacts as $contact){
            $sale = $contact->getSaleId();
            if($sale){
                $arr['sales'][] = json_decode(json_encode($sale), true);
                $gross_amount = bcadd($sale->getGrossAmount(), $gross_amount, 4);
                $net_amount = bcadd($sale->getNetAmount(), $net_amount, 4);
                $current_tax = bcsub($sale->getGrossAmount(), $sale->getNetAmount(), 4);
                $tax_amount = bcadd($current_tax, $tax_amount, 4);
                $profit = bcadd($sale->getProfit(), $profit, 4);
            }
        }

        $profit_percent = bcdiv($profit, $gross_amount, 4);

        $arr['gross_amount'] = $gross_amount;
        $arr['net_amount'] = $net_amount;
        $arr['tax_amount'] = $tax_amount;
        $arr['profit'] = $profit;
        $arr['profit_percent'] = $profit_percent;

        return new JsonResponse($arr);
    }
}
