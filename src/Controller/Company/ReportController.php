<?php
namespace App\Controller\Company;

use App\Repository\InvoiceDetailRepository;
use App\Repository\InvoiceRepository;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/report')]
class ReportController extends AbstractController
{
    #[Route('/', name: 'report_index')]
    public function index(ChartBuilderInterface $chartBuilder, InvoiceRepository $invoiceRepository, InvoiceDetailRepository $invoiceDetailRepository): Response
    {
        $userCompanyId = $this->getUser()->getCompany()->getId();

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
        return $this->render('company/report/index.html.twig', [
            'doughnutChart' => $doughnutChart,
            'barChartProduct' => $barChartProduct,
            'barChartSale' => $barChartSale,
        ]);
    }

    #[Route('/export-sales-data-pdf', name: 'export_sales_data_pdf')]
    public function exportSalesDataPDF(DompdfWrapperInterface $dompdfWrapper, InvoiceDetailRepository $invoiceDetailRepository): Response
    {
        $userCompanyId = $this->getUser()->getCompany()->getId();
        $salesData = $invoiceDetailRepository->getSalesData($userCompanyId);

        $html = $this->renderView('company/report/export_sales_data.html.twig', [
            'salesData' => $salesData,
        ]);

        return $dompdfWrapper->getStreamResponse($html, "sales_data.pdf");
    }

    #[Route('/export-invoice-status-pdf', name: 'export_invoice_status_pdf')]
    public function exportInvoiceStatusPDF(DompdfWrapperInterface $dompdfWrapper, InvoiceRepository $invoiceRepository): Response
    {
        $userCompanyId = $this->getUser()->getCompany()->getId();
        $invoiceStatusData = $invoiceRepository->getInvoiceStatusCounts($userCompanyId);

        $html = $this->renderView('company/report/export_invoice_status.html.twig', [
            'invoiceStatusData' => $invoiceStatusData,
        ]);

        return $dompdfWrapper->getStreamResponse($html, "invoice_status.pdf");
    }

    #[Route('/export-sales-by-month-pdf', name: 'export_sales_by_month_pdf')]
    public function exportSalesByMonthPDF(DompdfWrapperInterface $dompdfWrapper, InvoiceRepository $invoiceRepository): Response
    {
        $userCompanyId = $this->getUser()->getCompany()->getId();
        $salesByMonthData = $invoiceRepository->findTotalSalesByMonth($userCompanyId);

        $html = $this->renderView('company/report/export_sales_by_month.html.twig', [
            'salesByMonth' => $salesByMonthData,
        ]);

        return $dompdfWrapper->getStreamResponse($html, "sales_by_month.pdf");
    }


}
