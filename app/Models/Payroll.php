<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'tenant_id',
        'payroll_date',
        'status', // e.g. 'draft', 'finalized', 'paid'
    ];

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function presenceRecap()
    {
        return $this->belongsTo(PresenceRecap::class);
    }

    public function generatePayroll($presenceRecap, $payrollDate)
    {
        foreach ($presenceRecap->presenceRecapDetails as $detail) {
            $salary = Salary::where('tenant_id', $this->tenant_id)
                ->where('employee_id', $detail->employee_id)
                ->where('start_date', '<=', $payrollDate)
                ->where('is_active', true)
                ->first();

            if ($salary) {
                $payroll = new Payroll();
                $payroll->tenant_id = $this->tenant_id;
                $payroll->presence_recap_id = $presenceRecap->id;
                $payroll->location_id = $presenceRecap->location_id;
                $payroll->division_id = $presenceRecap->division_id;
                $payroll->employee_id = $detail->employee_id;
                $payroll->type = 'regular';
                $payroll->status = 'draft';
                $payroll->location_name = $presenceRecap->location_name;
                $payroll->division_name = $presenceRecap->division_name;
                $payroll->employee_name = $detail->employee_name;
                $payroll->payroll_date = $payrollDate;
                $payroll->base_salary = $salary->basic_salary;
                $payroll->total_allowances = array_sum($salary->allowances);
                $payroll->total_deductions = array_sum($salary->deductions);
                $payroll->net_salary = $payroll->base_salary + $payroll->total_allowances - $payroll->total_deductions;
                $payroll->save();

                if ($payroll->total_allowances > 0) {
                    foreach ($salary->allowances as $parameterName => $amount) {
                        $payrollDetail = new PayrollDetail();
                        $payrollDetail->tenant_id = $this->tenant_id;
                        $payrollDetail->payroll_id = $payroll->id;
                        $payrollDetail->parameter_name = $parameterName;
                        $payrollDetail->type = 'allowance';
                        $payrollDetail->amount = $amount;
                        $payrollDetail->save();
                    }
                }

                if ($payroll->total_deductions > 0) {
                    foreach ($salary->deductions as $parameterName => $amount) {
                        $payrollDetail = new PayrollDetail();
                        $payrollDetail->tenant_id = $this->tenant_id;
                        $payrollDetail->payroll_id = $payroll->id;
                        $payrollDetail->parameter_name = $parameterName;
                        $payrollDetail->type = 'deduction';
                        $payrollDetail->amount = $amount;
                        $payrollDetail->save();
                    }
                }
            }
        }
    }
}
