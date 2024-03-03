<?php

namespace App\Controller\Back;

use App\Repository\InvoiceDetailRepository;
use App\Repository\InvoiceRepository;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/global/report')]
class GlobalReportController extends AbstractController
{
    #[Route('/', name: 'global_report_index', methods: ['GET'])]
    public function index(ChartBuilderInterface $chartBuilder, InvoiceRepository $invoiceRepository, InvoiceDetailRepository $invoiceDetailRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Doughnut Chart for Invoices
        $invoiceData = $invoiceRepository->getInvoiceStatusCountsGlobal();
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
        $salesData = $invoiceDetailRepository->getSalesDataGlobal();
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
        $salesData = $invoiceRepository->findTotalSalesByMonthGlobal();
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


        return $this->render('back/global_report/index.html.twig', [
            'controller_name' => 'GlobalReportController',
            'doughnutChart' => $doughnutChart,
            'barChartProduct' => $barChartProduct,
            'barChartSale' => $barChartSale,
        ]);
    }

    #[Route('/export-global-sales-data-pdf', name: 'export_global_sales_data_pdf', methods: ['GET'])]
    public function exportSalesDataPDF(DompdfWrapperInterface $dompdfWrapper, InvoiceDetailRepository $invoiceDetailRepository): Response
    {
        $salesData = $invoiceDetailRepository->getSalesDataGlobal();

        $html = $this->renderView('back/global_report/global_export_sales_data.html.twig', [
            'salesData' => $salesData,
        ]);

        return $dompdfWrapper->getStreamResponse($html, "global_sales_data.pdf");
    }

    #[Route('/export-global-invoice-status-pdf', name: 'export_global_invoice_status_pdf', methods: ['GET'])]
    public function exportInvoiceStatusPDF(DompdfWrapperInterface $dompdfWrapper, InvoiceRepository $invoiceRepository): Response
    {
        $invoiceStatusData = $invoiceRepository->getInvoiceStatusCountsGlobal();

        $html = $this->renderView('back/global_report/global_export_invoice_status.html.twig', [
            'invoiceStatusData' => $invoiceStatusData,
        ]);

        return $dompdfWrapper->getStreamResponse($html, "global_invoice_status.pdf");
    }

    #[Route('/export-global-sales-by-month-pdf', name: 'export_global_sales_by_month_pdf', methods: ['GET'])]
    public function exportSalesByMonthPDF(DompdfWrapperInterface $dompdfWrapper, InvoiceRepository $invoiceRepository): Response
    {
        $salesByMonthData = $invoiceRepository->findTotalSalesByMonthGlobal();

        $html = $this->renderView('back/global_report/global_export_sales_by_month.html.twig', [
            'salesByMonth' => $salesByMonthData,
        ]);

        return $dompdfWrapper->getStreamResponse($html, "global_sales_by_month.pdf");
    }
}
