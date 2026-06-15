<?php

namespace App\Exports;
use Carbon\Carbon;
use App\Models\Paymentlevel;
use App\Models\NewEnrollment;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles; // Use the WithStyles concern
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Correctly import the Worksheet class

class NewEnrollmentExport implements FromCollection, WithHeadings, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $new_enrollments_array = [];

        $new_enrollments = NewEnrollment::with('student.paymentlevel')->get();

        foreach ($new_enrollments as $new_enrollment) {
            $new_enrollments_array[] = [
                'portal_id' => isset($new_enrollment->student) ? $new_enrollment->student->student_id : '',
                'name' => isset($new_enrollment->student) ? $new_enrollment->student->first_name . ' ' . $new_enrollment->student->last_name : '',
                'email' => isset($new_enrollment->student) ? $new_enrollment->student->email : '',
                'mobile' => isset($new_enrollment->student) ? $new_enrollment->student->mobile : '',
                'country' => isset($new_enrollment->student) ? $new_enrollment->student->country : '',
                'payment_level' => isset($new_enrollment->student->paymentlevel->name) ? $new_enrollment->student->paymentlevel->name : '',
                'batch' => isset($new_enrollment->batch) ? $new_enrollment->batch->name : '',
                'employee' => isset($new_enrollment->employee->user) ? $new_enrollment->employee->user->first_name . ' ' .  $new_enrollment->employee->user->last_name : ' ',
                'start_date' => isset($new_enrollment) ? $new_enrollment->start_date : '',
                'end_date' => isset($new_enrollment) ? $new_enrollment->end_date : '',
                'currency' => isset($new_enrollment) ? $new_enrollment->currency : '',
                'fees' => isset($new_enrollment) ? $new_enrollment->fees : '',
                'received_fees' => isset($new_enrollment) ? $new_enrollment->received_fees : '',
                'remark' => isset($new_enrollment) ? $new_enrollment->remark : '',
            ];
        }

        return collect($new_enrollments_array);
    }

    public function headings(): array
    {
        return [
            'Portal ID',
            'Name',
            'Email',
            'Mobile',
            'Country',
            'Payment Level',
            'Batch',
            'Employee',
            'Start Date',
            'End Date',
            'Currency',
            'Fees',
            'Received Fees',
            'Remark',
        ];
    }

    // Add styles method to apply styling to the header
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'D3D3D3'], // Light grey background
                ],
            ],
        ];
    }
}


