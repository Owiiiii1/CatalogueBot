<?php

namespace App\Orchid\Layouts\Metrics;

use Orchid\Screen\Layouts\Metric;

class OverallMetrics extends Metric
{
    /**
     * @var string
     */
    protected $title = 'Статистика за все время';

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
    protected $target = 'overall_metrics';
}
