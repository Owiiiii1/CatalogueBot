<?php

namespace App\Orchid\Layouts\Metrics;

use Orchid\Screen\Layouts\Metric;

class MonthlyMetrics extends Metric
{
    /**
     * @var string
     */
    protected $title = 'Сатистика за текущий месяц';

    /**
     * @var array
     */
    protected $labels = [
        'Всего зашло',
        'Просмотр вакансии',
        'Оставили заявку',
        'Рефералов',
    ];

    /**
     * @var string
     */
    protected $target = 'monthly_metrics';
}
