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

    public CONST CURRENCY_SYMBOL = '$';

    public function index(Request $request)
    {
      
        $daysOfWeek = $this->getDaysOfWeek($request);
        $daysOfMonth = $this->getDaysOfMonth($request);
        $chartData = $this->getChartData($daysOfWeek);
        $chartData2 = $this->getChartData2($daysOfWeek);
        $monthsOfYear = $this->getMonthsOfYear($request);
        $currentDate = now();

        return view('admintheme::dashboard.index', compact('chartData', 'chartData2', 'daysOfWeek', 'daysOfMonth', 'monthsOfYear', 'currentDate'));
    }

    public function getChartDataAjax(Request $request)
    {
        $dateRange = $request->input('dateRange');
        $method = $request->input('paymentMethod');

        $days = [];

        switch ($dateRange) {
            case 'week':
                $days = $this->getDaysOfWeek($request);
                break;
            case 'month':
                $days = $this->getDaysOfMonth($request);
                break;
            case 'year':
                $days = $this->getMonthsOfYear($request);
                break;
        }

        $chartData1 = $this->getChartData($days, $method, $dateRange);
        $chartData2 = $this->getChartData2($days, $method, $dateRange);

        return response()->json(['chartData1' => $chartData1, 'chartData2' => $chartData2]);
    }

    private function getChartData($timePeriod, $paymentMethod = null, $dateRange = 'week')
    {   
        $dataAmount = $this->getSumAmountData($timePeriod, $paymentMethod, $dateRange);

        $dataCounter = $this->getCountRecordsData($timePeriod, $paymentMethod, $dateRange);

        $chartData = [
            'labels' => $timePeriod,
            'datasets' => [
                [
                    'label' => 'Sum Amount',
                    'data' => $dataAmount,
                    'borderWidth' => 1,
                    'hoverBorderWidth' => 3,
                    'hoverBorderColor' => '#000',
                    'yAxisID' => 'y-axis-1',
                    'color' => 'red'
                ],
                [
                    'label' => 'Count Orders',
                    'data' => $dataCounter,
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

    private function getChartData2($days, $method = null, $dateRange = 'week')
    {
        $data = $this->getSellData($days, $method, $dateRange);
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

    private function getSellData($days, $method, $dateRange)
    {
        $dateRange = $dateRange ?? 'week';
        $data = [];
        if ($dateRange !== 'year') {
            foreach ($days as $day) {
                $data[] = PayPalMoney::whereDate('created_at', $day)->sum('money') +
                    StripeMoney::whereDate('created_at', $day)->where('status','1')->sum('money') +
                    AirwalletMoney::whereDate('created_at', $day)->where('status','1')->sum('money');
            }
        }   else {
            $months = $days;
            foreach ($months as $month) {
                $ppMoney = PayPalMoney::whereDate('created_at', '>=', $month.'-01')
                                ->whereDate('created_at', '<=', $month.'-31')
                                ->sum('money');
                $awMoney = AirwalletMoney::whereDate('created_at', '>=', $month.'-01')
                                ->whereDate('created_at', '<=', $month.'-31')
                                ->where('status','1')
                                ->sum('money');
                $stripeMoney = StripeMoney::whereDate('created_at', '>=', $month.'-01')
                                ->whereDate('created_at', '<=', $month.'-31')
                                ->where('status','1')
                                ->sum('money');

                $data[] = $ppMoney + $awMoney + $stripeMoney;
                
            }
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


    private function getSumAmountData($timePeriod, $paymentMethod, $dateRange)
    {
        $data = [];
        if ($dateRange == 'year') {
            $months = $timePeriod;

            foreach ($months as $month) {
                if ($paymentMethod === null) {
                    $data[] = Order::whereDate('created_at', '>=', $month.'-01')
                        ->whereDate('created_at', '<=', $month.'-31')
                        ->where('status', 'complete')->sum('amount');

                } else {
                    $data[] = Order::where('method', $paymentMethod)
                        ->where('status', 'complete')
                        ->whereDate('created_at', '>=', $month.'-01')
                        ->whereDate('created_at', '<=', $month.'-31')
                        ->sum('amount');
                }
            }
        }   else {
            $days = $timePeriod;
            
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
        }
        
        return $data;
    }

    private function getCountRecordsData($timePeriod, $paymentMethod, $dateRange)
    {
        $data = [];
        if ($dateRange != 'year') {
            $days = $timePeriod;
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
        }   else {
            $months = $timePeriod;

            foreach ($months as $month) {
                if ($paymentMethod === null) {
                    $data[] = Order::whereDate('created_at', '>=', $month.'-01')
                        ->whereDate('created_at', '<=', $month.'-31')
                        ->where('status', 'complete')->count();
                } else {
                    $data[] = Order::whereDate('created_at', '>=', $month.'-01')
                        ->whereDate('created_at', '<=', $month.'-31')
                        ->where('method', $paymentMethod)
                        ->where('status', 'complete')
                        ->count();
                }
            }
        }
        return $data;
    }

    private function getDaysOfWeek($request = null)
    {
        if (empty($request->input('startDate'))) {
            $startDate = now();
        }   else {
            $startDate = \date_create_from_format("d-m-Y", $request->input('startDate'));
        }

        $daysOfWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $daysOfWeek[] = $startDate->format('Y-m-d');
            $startDate->modify('+1 day');
        }
        return $daysOfWeek;
    }

    private function getDaysOfMonth($request = null)
    {
        if (empty($request->input('month'))) {
            $date = now();
        }   else {
            $date = \date_create_from_format("m-Y", $request->input('month'));
        }

        $daysOfMonth = [];
        $lastDayOfMonth = $date->modify('last day of this month');
        for ($i = 1; $i <= $lastDayOfMonth->format('d'); $i++) {
            $daysOfMonth[] = $date->format('Y-m-') . $i;
        }
        return $daysOfMonth;
    }

    private function getMonthsOfYear($request = null)
    {
        if (empty($request)) {
            $year = date("Y");
        }   else {
            $year =  $request->input('year');
        }
        
        $months = [];
        for ($i=1; $i<=12 ; $i++) { 
            // as an year always has 12 months
            $months[] =  $year . '-' . $i ;
        }

        return $months;
    }
}