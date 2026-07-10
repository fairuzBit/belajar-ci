<?php

if (!function_exists('get_today_discount')) {
    function get_today_discount()
    {
        $model = new \App\Models\DiscountModel();
        return $model->where('tanggal', date('Y-m-d'))->first();
    }
}
