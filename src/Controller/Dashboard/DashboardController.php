<?php
namespace App\Controller\Dashboard;

use App\Repository\InvoiceDetailRepository;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(ChartBuilderInterface $chartBuilder, InvoiceRepository $invoiceRepository, InvoiceDetailRepository $invoiceDetailRepository): Response
    {
        $userCompanyId = $this->getUser()->getCompany()->getId();

        // Line Chart
        $lineChart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $lineChart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);
        $lineChart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        // Doughnut Chart for Invoices
        $invoiceData = $invoiceRepository->getInvoiceStatusCounts($userCompanyId);
        $labels = [];
        $data = [];
        foreach ($invoiceData as $status) {
            $labels[] = $status['paymentStatus'];
            $data[] = $status['statusCount'];
        }
        $doughnutChart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $doughnutChart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => ['#56CCF2', '#2F80ED', '#9B51E0'],
                    'data' => $data,
                ],
            ],
        ]);

        // Bar Chart for Product Sales
        $salesData = $invoiceDetailRepository->getSalesData($userCompanyId);
        $labels = [];
        $data = [];
        foreach ($salesData as $sale) {
            $labels[] = $sale['productName'];
            $data[] = $sale['totalSales'];
        }
        $barChartProduct = $chartBuilder->createChart(Chart::TYPE_BAR);
        $barChartProduct->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Ventes par produit',
                    'backgroundColor' => 'rgb(75, 192, 192)',
                    'borderColor' => 'rgb(75, 192, 192)',
                    'data' => $data,
                ],
            ],
        ]);
        $barChartProduct->setOptions([
            'indexAxis' => 'y',
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
        ]);

        // Bar Chart for Sales
        $salesData = $invoiceRepository->findTotalSalesByMonth($userCompanyId);
        $labels = [];
        $data = [];
        foreach ($salesData as $monthData) {
            $labels[] = $monthData['month'];
            $data[] = $monthData['total'];
        }
        $barChartSale = $chartBuilder->createChart(Chart::TYPE_BAR);
        $barChartSale->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total TTC des ventes',
                    'backgroundColor' => 'rgb(75, 192, 192)',
                    'data' => $data,
                ],
            ],
        ]);
        $barChartSale->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['beginAtZero' => true]],
                ],
            ],
        ]);

        // Pass all charts to the Twig template
        return $this->render('dashboard/index.html.twig', [
            'lineChart' => $lineChart,
            'doughnutChart' => $doughnutChart,
            'barChartProduct' => $barChartProduct,
            'barChartSale' => $barChartSale,
        ]);
    }
}
