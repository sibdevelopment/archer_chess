<?php

namespace App\Exports;

use App\Models\StudentFee;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $students;    

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students->map(function ($student) {
            $latestBatch = $student->latestBatch;
            $coachName   = $latestBatch && $latestBatch->coach
                ? $latestBatch->coach->user->first_name . ' ' . $latestBatch->coach->user->last_name
                : 'N/A';

            $studentFee = StudentFee::where('student_id', $student->id)->orderBy('created_at', 'desc')->first();

            return [
                'Student Name'  => $student->first_name . ' ' . $student->last_name,
                'Timezone'      => $student->timezone,
                'Student ID'    => $student->student_id,
                'Batch'         => $latestBatch ? $latestBatch->batch->name : 'N/A',
                'Coach Name'    => $coachName,
                'Mobile'        => $student->mobile,
                'Status'        => $student->status,
                'Country'       => $student->country,
                'Fees Country'  => $student->fees_country,
                'Fees Start Date' => $studentFee ? toIndianDate($studentFee->start_date) : 'N/A',
                'Fees End Date'   => $studentFee ? toIndianDate($studentFee->end_date) : 'N/A',
                'Currency'      => $studentFee ? $studentFee->currency : 'N/A',
                'Monthly Fees'  => $studentFee ? $studentFee->monthly_fees : 'N/A',
                'Fees Amount'     => $studentFee ? $studentFee->total_amount_paid : 'N/A',
                'Created At'    => $student->created_at,
                'Updated At'    => $student->updated_at
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Timezone',
            'Student ID',
            'Batch',
            'Coach Name',
            'Mobile',
            'Status',
            'Country',
            'Fees Country',
            'Fees Start Date',
            'Fees End Date',
            'Currency',
            'Monthly Fees',
            'Fees Amount',
            'Created At',
            'Updated At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold the header row
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
