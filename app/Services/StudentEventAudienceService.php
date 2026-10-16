<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Level;
use App\Models\MasterclassData;
use App\Models\Student;
use App\Models\TournamentData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentEventAudienceService
{
    public function validateTargets(Request $request): void
    {
        $studentIds = $this->ids($request->input('student_ids', []));
        $batchIds = $this->ids($request->input('batch_ids', []));
        $levelIds = $this->ids($request->input('level_ids', []));

        if (empty($studentIds) && empty($batchIds) && empty($levelIds)) {
            throw ValidationException::withMessages([
                'student_ids' => 'Select at least one student, batch, or level.',
            ]);
        }

        $countries = $this->countryComparisonValues($request->input('country', []));

        $validStudentIds = Student::query()
            ->whereIn('id', $studentIds)
            ->where('status', 'ACTIVE')
            ->whereIn('country', $countries)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        if (array_diff($studentIds, $validStudentIds)) {
            throw ValidationException::withMessages([
                'student_ids' => 'Only active students from the selected countries can be selected.',
            ]);
        }

        $validBatchIds = Batch::query()
            ->whereIn('id', $batchIds)
            ->where('status', 'ACTIVE')
            ->where(function (Builder $query) use ($countries) {
                foreach ($countries as $country) {
                    $query->orWhereJsonContains('country', $country);
                }
            })
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        if (array_diff($batchIds, $validBatchIds)) {
            throw ValidationException::withMessages([
                'batch_ids' => 'Only active batches from the selected countries can be selected.',
            ]);
        }

        $validLevelIds = Level::query()
            ->whereIn('id', $levelIds)
            ->where('status', 'ACTIVE')
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        if (array_diff($levelIds, $validLevelIds)) {
            throw ValidationException::withMessages([
                'level_ids' => 'Only active levels can be selected.',
            ]);
        }
    }

    public function fillTargets(Model $event, Request $request): void
    {
        $event->batch_ids = $this->ids($request->input('batch_ids', []));
        $event->student_ids = $this->ids($request->input('student_ids', []));
        $event->level_ids = $this->ids($request->input('level_ids', []));
    }

    public function visibleToStudent(Builder $query, Student $student): Builder
    {
        $assignments = $student->studentBatches()
            ->where('status', 'ACTIVE')
            ->whereHas('batch', fn (Builder $batch) => $batch->where('status', 'ACTIVE'))
            ->with('batch:id,level_id')
            ->get();

        $batchIds = $assignments->pluck('batch_id')->filter()->map(fn ($id) => (string) $id)->unique();
        $levelIds = $assignments->pluck('batch.level_id')->filter()->map(fn ($id) => (string) $id)->unique();
        $countries = $this->countryComparisonValues([$student->country]);
        $studentId = (string) $student->id;

        return $query
            ->where(function (Builder $countryQuery) use ($countries) {
                foreach ($countries as $country) {
                    $countryQuery->orWhereJsonContains('country', $country);
                }
            })
            ->where(function (Builder $targetQuery) use ($studentId, $batchIds, $levelIds) {
                $targetQuery->whereJsonContains('student_ids', $studentId);

                foreach ($batchIds as $batchId) {
                    $targetQuery->orWhereJsonContains('batch_ids', $batchId);
                }

                foreach ($levelIds as $levelId) {
                    $targetQuery->orWhereJsonContains('level_ids', $levelId);
                }
            });
    }

    public function studentsForEvent(Model $event, array $statuses = ['ACTIVE']): Collection
    {
        $studentIds = $this->ids($event->student_ids ?? []);
        $batchIds = $this->ids($event->batch_ids ?? []);
        $levelIds = $this->ids($event->level_ids ?? []);
        $countries = $this->countryComparisonValues($event->country ?? []);

        if (empty($studentIds) && empty($batchIds) && empty($levelIds)) {
            return collect();
        }

        return Student::query()
            ->whereIn('status', $statuses)
            ->whereIn('country', $countries)
            ->where(function (Builder $targetQuery) use ($studentIds, $batchIds, $levelIds) {
                if (! empty($studentIds)) {
                    $targetQuery->orWhereIn('id', $studentIds);
                }

                if (! empty($batchIds)) {
                    $targetQuery->orWhereHas('studentBatches', function (Builder $studentBatch) use ($batchIds) {
                        $studentBatch->where('status', 'ACTIVE')
                            ->whereIn('batch_id', $batchIds)
                            ->whereHas('batch', fn (Builder $batch) => $batch->where('status', 'ACTIVE'));
                    });
                }

                if (! empty($levelIds)) {
                    $targetQuery->orWhereHas('studentBatches', function (Builder $studentBatch) use ($levelIds) {
                        $studentBatch->where('status', 'ACTIVE')
                            ->whereHas('batch', function (Builder $batch) use ($levelIds) {
                                $batch->where('status', 'ACTIVE')->whereIn('level_id', $levelIds);
                            });
                    });
                }
            })
            ->get();
    }

    public function syncMasterclassData(Model $masterclass): void
    {
        $studentIds = $this->studentsForEvent($masterclass)->pluck('id');

        DB::transaction(function () use ($masterclass, $studentIds) {
            MasterclassData::where('masterclass_id', $masterclass->id)->delete();

            foreach ($studentIds as $studentId) {
                MasterclassData::create([
                    'masterclass_id' => $masterclass->id,
                    'student_id' => $studentId,
                ]);
            }
        });
    }

    public function syncTournamentData(Model $tournament): void
    {
        $studentIds = $this->studentsForEvent($tournament)->pluck('id');

        DB::transaction(function () use ($tournament, $studentIds) {
            TournamentData::where('tournament_id', $tournament->id)->delete();

            foreach ($studentIds as $studentId) {
                TournamentData::create([
                    'tournament_id' => $tournament->id,
                    'student_id' => $studentId,
                ]);
            }
        });
    }

    private function ids($values): array
    {
        return collect((array) $values)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values()
            ->all();
    }

    private function countryComparisonValues($countries): array
    {
        return collect(normalizeCountryValues((array) $countries))
            ->flatMap(fn ($country) => countryComparisonValues($country))
            ->unique()
            ->values()
            ->all();
    }
}
