<?php
namespace Modules\AdminTheme\App\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Modules\OrderManager\App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;
use Modules\AirwalletManager\App\Models\AirwalletMoney;
use Modules\PayPalManager\App\Models\PaypalMoney;
use Modules\StripeManager\App\Models\StripeMoney;

class DashboardController extends Controller
{
    public function index()
    {
        $daysOfWeek = $this->getDaysOfWeek();
        $daysOfMonth = $this->getDaysOfMonth();
        $daysOfYear = $this->getDaysOfYear();
        $chartData = $this->getChartData($daysOfWeek);
        $chartData2 = $this->getChartData2($daysOfWeek);
        return view('admintheme::dashboard.index', compact('chartData', 'chartData2', 'daysOfWeek', 'daysOfMonth', 'daysOfYear'));
    }

    public function getChartDataAjax(Request $request)
    {
        $dateRange = $request->input('dateRange');
        $method = $request->input('paymentMethod');
        $days = [];

        switch ($dateRange) {
            case 'week':
                $days = $this->getDaysOfWeek();
                break;
            case 'month':
                $days = $this->getDaysOfMonth();
                break;
            case 'year':
                $days = $this->getDaysOfYear();
                break;
        }

        $chartData1 = $this->getChartData($days, $method);
        $chartData2 = $this->getChartData2($days, $method);

        return response()->json(['chartData1' => $chartData1, 'chartData2' => $chartData2]);
    }
    private function getChartData($days, $paymentMethod = null)
    {
        $chartData = [
            'labels' => $days,
            'datasets' => [
                [
                    'label' => 'Sum Amount',
                    'data' => $this->getSumAmountData($days, $paymentMethod),
                    'borderWidth' => 1,
                    'hoverBorderWidth' => 3,
                    'hoverBorderColor' => '#000',
                    'yAxisID' => 'y-axis-1'
                ],
                [
                    'label' => 'Count Orders',
                    'data' => $this->getCountRecordsData($days, $paymentMethod),
                    'type' => 'line',
                    'fill' => false,
                    'backgroundColor' => '#0099cc',
                    'borderColor' => '#0099cc',
                    'borderWidth' => 2,
                    'yAxisID' => 'y-axis-2'
                ]
            ]
        ];
        return $chartData;
    }
    private function getChartData2($days, $method = null)
    {
        $data = $this->getSellData($days, $method);
        switch ($method) {
            case 'PAYPAL':
                $data = $this->getPayPalData($days, $method);
                break;
            case 'CREDIT_CARD':
                $data = $this->getStripeData($days, $method);
                break;
            case 'CREDIT_CARD_2':
                $data = $this->getAirWalletData($days, $method);
                break;
        }
        $chartData = [
            'labels' => $days,
            'datasets' => [
                [
                    'label' => 'AirWallet',
                    'data' => $data,
                    'borderWidth' => 1,
                    'hoverBorderWidth' => 3,
                    'hoverBorderColor' => '#000',
                    'yAxisID' => 'y-axis-1'
                ]
            ]
        ];
        return $chartData;
    }

    private function getSellData($days, $method)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = PayPalMoney::whereDate('created_at', $day)->sum('money') +
                StripeMoney::whereDate('created_at', $day)->where('status','1')->sum('money') +
                AirwalletMoney::whereDate('created_at', $day)->where('status','1')->sum('money');
        }
        return $data;
    }
    private function getAirWalletData($days, $method)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = AirwalletMoney::whereDate('created_at', $day)
            ->where('status','1')->sum('money');
        }
        return $data;
    }

    private function getStripeData($days, $method)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = StripeMoney::whereDate('created_at', $day)
            ->where('status','1')->sum('money');
        }
        return $data;
    }

    private function getPayPalData($days, $method)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = PayPalMoney::whereDate('created_at', $day)->sum('money');
        }
        return $data;
    }


    private function getSumAmountData($days, $paymentMethod)
    {
        $data = [];
        foreach ($days as $day) {
            if ($paymentMethod === null) {
                $data[] = Order::whereDate('created_at', $day)
                    ->where('status', 'complete')->sum('amount');
            } else {
                $data[] = Order::whereDate('created_at', $day)
                    ->where('method', $paymentMethod)
                    ->where('status', 'complete')
                    ->sum('amount');
            }
        }
        return $data;
    }

    private function getCountRecordsData($days, $paymentMethod)
    {
        $data = [];
        foreach ($days as $day) {
            if ($paymentMethod === null) {
                $data[] = Order::whereDate('created_at', $day)
                    ->where('status', 'complete')->count();
            } else {
                $data[] = Order::whereDate('created_at', $day)
                    ->where('method', $paymentMethod)
                    ->where('status', 'complete')
                    ->count();
            }
        }
        return $data;
    }

    private function getDaysOfWeek()
    {
        $currentDate = new DateTime();
        $daysOfWeek = [];
        $currentDate = now()->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $daysOfWeek[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }
        return $daysOfWeek;
    }

    private function getDaysOfMonth()
    {
        $currentDate = new DateTime();
        $daysOfMonth = [];
        $currentDate = now();
        $lastDayOfMonth = $currentDate->endOfMonth()->day;
        for ($i = 1; $i <= $lastDayOfMonth; $i++) {
            $daysOfMonth[] = $currentDate->format('Y-m-') . $i;
        }
        return $daysOfMonth;
    }

    private function getDaysOfYear()
    {
        $currentDate = new DateTime();
        $daysOfYear = [];
        $currentDate = Carbon::now();
        $startOfYear = $currentDate->copy()->startOfYear();
        $endOfYear = $currentDate->copy()->endOfYear();
        while ($startOfYear->lte($endOfYear)) {
            $daysOfYear[] = $startOfYear->format('Y-m-d');
            $startOfYear->addDay();
        }
        return $daysOfYear;
    }

}