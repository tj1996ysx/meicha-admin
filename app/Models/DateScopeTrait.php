<?php

namespace App\Models;

trait DateScopeTrait
{
    public function scopeToday($query)
    {
        return $query->where('created_at', '>=', date('Y-m-d 00:00:00'));
    }

    public function scopeYesterday($query)
    {
        return $query->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-1 day')))
            ->where('created_at', '<', date('Y-m-d 23:59:59', strtotime('-1 day')));
    }

    public function scopeThisMonth($query)
    {
        return $query->where('created_at', '>=', date('Y-m-01 00:00:00'));
    }

    public function scopeLastMonth($query)
    {
        return $query->where('created_at', '>=', date('Y-m-01 00:00:00', strtotime('last month')))
            ->where('created_at', '<', date('Y-m-01 00:00:00'));
    }

    public function scopeFrom($query, $start)
    {
        return $query->where('created_at', '>=', $start);
    }

    public function scopeTo($query, $to)
    {
        return $query->where('created_at', '<=', $to);
    }

    public function scopeBetween($query, $from, $to)
    {
        return $query->from($from)->to($to);
    }
}
