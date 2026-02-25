<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    public function scopeFilter($query, $request)
    {
        return $query
            ->when($request->company_id, function ($q) use ($request) {
                $q->where('project_id', $request->company_id);
            })
            ->when($request->from_date, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            });
    }
}
