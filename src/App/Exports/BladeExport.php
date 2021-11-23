<?php

namespace BetterFly\Skeleton\App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BladeExport implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('betterfly::admin.common.excel', [
            'data' => $this->data
        ]);
    }
}