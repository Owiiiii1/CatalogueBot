<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\BotUser;
use App\Orchid\Layouts\Metrics\MonthlyMetrics;
use App\Orchid\Layouts\Metrics\OverallMetrics;
use Carbon\Carbon;
use Orchid\Screen\Screen;

class StatisticsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Статистика';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Статистика по использованию бота';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $botUsers = BotUser::all();

        $monthlyUsersCount = count($botUsers->where('created_at', '>', Carbon::now()->startOfMonth())->all());
        $lastMonthUsersCount = count($botUsers->where('created_at', '<', Carbon::now()->startOfMonth())->all());

        $referralsCount = count($botUsers->where('referrer', '!=', 'start')->all());
        $monthlyReferralsCount = count($botUsers->where('referrer', '!=', 'start')->where('created_at', '<', Carbon::now()->startOfMonth())->all());

        return [
            'monthly_metrics' => [
                ['keyValue' => number_format($monthlyUsersCount, 0), 'keyDiff' => 0],
                ['keyValue' => number_format(456, 0), 'keyDiff' => -30.76],
                ['keyValue' => number_format(789, 2), 'keyDiff' => 3.84],
                ['keyValue' => number_format($monthlyReferralsCount, 0), 'keyDiff' => -169.54],

            ],
            'overall_metrics' => [
                ['keyValue' => number_format(count($botUsers), 0), 'keyDiff' => 10.08],
                ['keyValue' => number_format(24668, 0), 'keyDiff' => -30.76],
                ['keyValue' => number_format(65661, 2), 'keyDiff' => 3.84],
                ['keyValue' => number_format($referralsCount, 0), 'keyDiff' => -169.54],
            ],
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            MonthlyMetrics::class,
            OverallMetrics::class,
        ];
    }
}
