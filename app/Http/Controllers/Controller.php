<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

abstract class Controller
{
    private static function generateDefaultFormatCode($table_name, $prefix_name, $tenantId, $is_inquiry = null)
    {
        $total_data = DB::table($table_name)->where('tenant_id', $tenantId);

        if ($is_inquiry != null) {
            $total_data->where('is_inquiry', $is_inquiry);
        }

        $total_data = $total_data->count();

        $sequence = $total_data + 1;

        $check_unique_code = false;

        while (!$check_unique_code) {
            $code = $prefix_name . '-' . $sequence;

            $get_detail_data = DB::table($table_name)
                ->where('code', $code)
                ->where('tenant_id', $tenantId)
                ->first();

            if ($get_detail_data == null) {
                $check_unique_code = true;
            } else {
                $sequence++;
                $check_unique_code = false;
            }
        }

        return $code;
    }

    private static function generateVariableFormatCode($var, $table_name, $tenantId, $prefix_name = null, $plusOnSeq = 0, $is_inquiry = null, $request = null)
    {
        switch ($var) {
            case 'date':
                return date('d');
                break;
            case 'employee_code':
                $code = '';

                if ($request) {
                    $code = $request->employee_code;
                }

                return $code;

                break;
            case 'is_renewal':
                $code = '';

                if ($request) {
                    $code = ($request->is_renewal) ? 'RN-' : 'N-';
                }

                return $code;

                break;
            case 'location_code':
                $code = '';

                if ($request) {
                    $code = $request->location_code;
                }

                return $code;

                break;
            case 'month':
                return date('m');
                break;
            case 'month_in_romawi':
                switch (intval(date('m'))) {
                    case 1:
                        return 'I';
                        break;
                    case 2:
                        return 'II';
                        break;
                    case 3:
                        return 'III';
                        break;
                    case 4:
                        return 'IV';
                        break;
                    case 5:
                        return 'V';
                        break;
                    case 6:
                        return 'VI';
                        break;
                    case 7:
                        return 'VII';
                        break;
                    case 8:
                        return 'VIII';
                        break;
                    case 9:
                        return 'IX';
                        break;
                    case 10:
                        return 'X';
                        break;
                    case 11:
                        return 'XI';
                        break;
                    case 12:
                        return 'XII';
                        break;
                }

                break;
            case 'prefix':
                return ($prefix_name != null) ? $prefix_name : '';
                break;
            case 'product_category_code':
                $code = '';

                if ($request) {
                    $code = $request->product_category_code;
                }

                return $code;

                break;
            case 'product_code':
                $code = '';

                if ($request) {
                    $code = $request->product_code;
                }

                return $code;

                break;
            case 'seq_rep_in_year_with_4_digits':
                $lastNumber = self::countLastNumberOfTable($table_name, $tenantId, $is_inquiry, $request);

                $seq = 1 + $lastNumber + $plusOnSeq;

                return self::getDigitsInNumber($seq);

                break;
            case 'seq_rep_in_year_with_5_digits':
                $lastNumber = self::countLastNumberOfTable($table_name, $tenantId, $is_inquiry, $request);

                $seq = 1 + $lastNumber + $plusOnSeq;

                return self::getDigitsInNumber($seq, 5);

                break;
            case 'seq_rep_in_year_with_6_digits':
                $lastNumber = self::countLastNumberOfTable($table_name, $tenantId, $is_inquiry, $request);

                $seq = 1 + $lastNumber + $plusOnSeq;

                return self::getDigitsInNumber($seq, 6);

                break;
            case 'seq_rep_in_year':
                $lastNumber = self::countLastNumberOfTable($table_name, $tenantId, $is_inquiry, $request);

                $seq = 1 + $lastNumber + $plusOnSeq;

                return $seq;

                break;
            case 'tenant_code':
                return "TENANT-CODE";
                break;
            case 'year':
                return date('Y');
                break;
            case 'year_2_digits':
                return date('y');
                break;
            default:
                return '';
                break;
        }
    }

    public static function getMasterCode($table_name, $prefix_name, $tenantId, $is_inquiry = null, $request = null)
    {
        $code = '';

        $insideLoop = true;

        $plusOnSeq = 0;

        $checkCustomFormat = DB::table('custom_code_generators')->where('tenant_id', $tenantId)
            ->where('table_name', $table_name)
            ->first();

        $customLastNumber = DB::table('custom_last_numbers')->where('tenant_id', $tenantId)
            ->where('table_name', $table_name)
            ->where('year', date('Y'));

        if ($request) {
            if ($request->location_id) {
                $customLastNumber = $customLastNumber->where('location_id', $request->location_id);
            }
        }

        $customLastNumber = $customLastNumber->first();

        if ($customLastNumber) {
            $plusOnSeq = $customLastNumber->last_number;
        }

        if ($checkCustomFormat) {
            $arrayOfCodeFormat = explode(' ', $checkCustomFormat->code_format);

            if (sizeof($arrayOfCodeFormat) > 0) {
                while ($insideLoop) {
                    for ($i = 0; $i < sizeof($arrayOfCodeFormat); $i++) {
                        $code .= (substr($arrayOfCodeFormat[$i], 0, 1) == '#') ? self::generateVariableFormatCode(ltrim($arrayOfCodeFormat[$i], $arrayOfCodeFormat[$i][0]), $table_name, $tenantId, $prefix_name, $plusOnSeq, $is_inquiry, $request) : $arrayOfCodeFormat[$i];
                    }

                    $checkExisting = DB::table($table_name)
                        ->where('tenant_id', $tenantId)
                        ->where('code', $code);

                    if ($request) {
                        if ($request->location_id) {
                            $checkExisting = $checkExisting->where('location_id', $request->location_id);
                        }
                    }

                    $checkExisting = $checkExisting->first();

                    if ($checkExisting) {
                        $plusOnSeq++;
                        $code = '';
                    } else {
                        $insideLoop = false;
                    }
                }
            } else {
                $code = self::generateDefaultFormatCode($table_name, $prefix_name, $tenantId, $is_inquiry);
            }
        } else {
            $code = self::generateDefaultFormatCode($table_name, $prefix_name, $tenantId, $is_inquiry);
        }

        return $code;
    }
}
