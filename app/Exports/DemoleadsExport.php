<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DemoleadsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $demoLeads;

    public function __construct(Collection $demoLeads)
    {
        $this->demoLeads = $demoLeads;
    }

    public function collection()
    {
        return $this->demoLeads->map(function ($lead) {
            $firstSession = $lead->demoSessions->first();

            $levelName = $firstSession?->level?->name ?? '';
            $coachName = ($firstSession?->coach?->user?->first_name && $firstSession?->coach?->user?->last_name)
                ? $firstSession->coach->user->first_name . ' ' . $firstSession->coach->user->last_name
                : '';

            return [
                'Name'        => $lead->first_name ?? 'N/A',
                'Mobile'      => $lead->mobile ?? 'N/A',
                'Country'     => $lead->country ?? 'N/A',
                'Status'      => $lead->status ?? 'N/A',
                'Level'       => $levelName,
                'Coach'       => $coachName,
                'Date'        => $lead->date ? \Carbon\Carbon::parse($lead->date)->format('d-m-Y') : 'N/A',
                'Created By'  => ($lead->createdBy?->first_name && $lead->createdBy?->last_name)
                    ? $lead->createdBy->first_name . ' ' . $lead->createdBy->last_name
                    : 'N/A',
                'Created At'  => $lead->created_at ? \Carbon\Carbon::parse($lead->created_at)->format('d-m-Y H:i') : 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Mobile',
            'Country',
            'Status',
            'Level',
            'Coach',
            'Date',
            'Created By',
            'Created At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Make first row bold
        ];
    }
}
