<?php

namespace App\Filament\Resources\SectionResource\Widgets;

use Filament\Widgets\Widget;

class WidgetHelperInfo extends Widget
{
    protected static string $view = 'filament.resources.section-resource.widgets.widget-helper-info';
    protected int|string|array $columnSpan = 'full';
}
