<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Financial_statement extends Model
{
    use HasFactory;

    public function scopeFilter($query, $request)
    {
        // سال و ماه از تاریخ ورودی
        $fromYear  = substr($request->from_date, 0, 4);
        $fromMonth = substr($request->from_date, 5, 2);

        $toYear    = substr($request->to_date, 0, 4);
        $toMonth   = substr($request->to_date, 5, 2);

        return $query
            ->when($request->company_id, fn($q) => $q->where('project_id', $request->company_id))
            ->when($request->from_date, function($q) use ($fromYear, $fromMonth) {
                $q->where(function($q2) use ($fromYear, $fromMonth){
                    $q2->where('year', '>', $fromYear)
                        ->orWhere(function($q3) use ($fromYear, $fromMonth){
                            $q3->where('year', $fromYear)
                                ->where('month', '>=', $fromMonth);
                        });
                });
            })
            ->when($request->to_date, function($q) use ($toYear, $toMonth) {
                $q->where(function($q2) use ($toYear, $toMonth){
                    $q2->where('year', '<', $toYear)
                        ->orWhere(function($q3) use ($toYear, $toMonth){
                            $q3->where('year', $toYear)
                                ->where('month', '<=', $toMonth);
                        });
                });
            });
    }
}
