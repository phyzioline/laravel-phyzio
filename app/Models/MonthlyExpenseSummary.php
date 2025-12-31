<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyExpenseSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'year',
        'month',
        'total_expenses',
        'rent',
        'salaries',
        'utilities',
        'medical_supplies',
        'equipment_maintenance',
        'marketing',
        'transportation',
        'miscellaneous',
        'expense_count',
        'average_daily_expense',
        'highest_expense_day',
        'lowest_expense_day',
        'last_calculated_at'
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'total_expenses' => 'decimal:2',
        'rent' => 'decimal:2',
        'salaries' => 'decimal:2',
        'utilities' => 'decimal:2',
        'medical_supplies' => 'decimal:2',
        'equipment_maintenance' => 'decimal:2',
        'marketing' => 'decimal:2',
        'transportation' => 'decimal:2',
        'miscellaneous' => 'decimal:2',
        'expense_count' => 'integer',
        'average_daily_expense' => 'decimal:2',
        'highest_expense_day' => 'decimal:2',
        'lowest_expense_day' => 'decimal:2',
        'last_calculated_at' => 'datetime'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // Calculate and update monthly summary
    public static function calculateForMonth($clinicId, $year, $month)
    {
        $expenses = DailyExpense::where('clinic_id', $clinicId)
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->get();

        $summary = static::firstOrNew([
            'clinic_id' => $clinicId,
            'year' => $year,
            'month' => $month
        ]);

        $summary->total_expenses = $expenses->sum('amount');
        $summary->rent = $expenses->where('category', 'rent')->sum('amount');
        $summary->salaries = $expenses->where('category', 'salaries')->sum('amount');
        $summary->utilities = $expenses->where('category', 'utilities')->sum('amount');
        $summary->medical_supplies = $expenses->where('category', 'medical_supplies')->sum('amount');
        $summary->equipment_maintenance = $expenses->where('category', 'equipment_maintenance')->sum('amount');
        $summary->marketing = $expenses->where('category', 'marketing')->sum('amount');
        $summary->transportation = $expenses->where('category', 'transportation')->sum('amount');
        $summary->miscellaneous = $expenses->where('category', 'miscellaneous')->sum('amount');
        $summary->expense_count = $expenses->count();
        
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $summary->average_daily_expense = $summary->expense_count > 0 
            ? $summary->total_expenses / $daysInMonth 
            : 0;
        
        $dailyTotals = $expenses->groupBy('expense_date')->map->sum('amount');
        $summary->highest_expense_day = $dailyTotals->max() ?? 0;
        $summary->lowest_expense_day = $dailyTotals->min() ?? 0;
        $summary->last_calculated_at = now();
        
        $summary->save();
        
        return $summary;
    }
}

