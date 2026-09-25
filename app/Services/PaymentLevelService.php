<?php

namespace App\Services;

use App\Models\Paymentlevel;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\StudentFee;
use Illuminate\Support\Collection;

class PaymentLevelService
{
    private const THREE_LEVEL_DISCOUNT_PERCENT = 10;

    public function nextPlan(Student $student, int $levelCount): array
    {
        $levelCount = max(1, $levelCount);
        $startSequence = $this->nextDueSequence($student);
        $levels = Paymentlevel::where('status', 'ACTIVE')
            ->whereRaw('CAST(sequence AS UNSIGNED) >= ?', [$startSequence])
            ->orderByRaw('CAST(sequence AS UNSIGNED) ASC')
            ->limit($levelCount)
            ->get();

        if ($levels->count() !== $levelCount) {
            return ['ok' => false, 'message' => "Next {$levelCount} payment levels are not configured."];
        }

        return $this->buildPlan($student, $levels);
    }

    public function planForTarget(Student $student, ?int $targetPaymentLevelId): array
    {
        $target = $targetPaymentLevelId ? Paymentlevel::where('status', 'ACTIVE')->find($targetPaymentLevelId) : null;

        if (! $target) {
            return ['ok' => false, 'message' => 'Selected payment level is not available.'];
        }

        $startSequence = $this->nextDueSequence($student);
        if ((int) $target->sequence < $startSequence) {
            return ['ok' => false, 'message' => 'Selected payment level has already been paid.'];
        }

        $levels = Paymentlevel::where('status', 'ACTIVE')
            ->whereRaw('CAST(sequence AS UNSIGNED) BETWEEN ? AND ?', [$startSequence, (int) $target->sequence])
            ->orderByRaw('CAST(sequence AS UNSIGNED) ASC')
            ->get();

        if (! in_array($levels->count(), [1, 3], true)) {
            return ['ok' => false, 'message' => 'Please select either the next 1 payment level or next 3 payment levels.'];
        }

        return $this->buildPlan($student, $levels);
    }

    public function countryPaymentConfig(Student $student): array
    {
        $country = normalizeCountryValue($student->country);
        return countryPaymentConfigs()[$country] ?? ['column' => null, 'currency' => ''];
    }

    public function dueAmountSummary(Student $student): array
    {
        $plan = $this->nextPlan($student, 1);

        if ($plan['ok']) {
            return [
                'amount' => $plan['amount'],
                'currency' => $plan['currency'],
                'payment_level' => $plan['target_level']->name,
            ];
        }

        $latestFee = StudentFee::where('student_id', $student->id)
            ->orderByDesc('id')
            ->first();

        return [
            'amount' => $latestFee ? $latestFee->monthly_fees : $student->monthly_fees,
            'currency' => $latestFee ? $latestFee->currency : $student->currency,
            'payment_level' => null,
        ];
    }

    public function lastPaidPaymentLevel(Student $student): ?Paymentlevel
    {
        $latestFee = StudentFee::where('student_id', $student->id)
            ->whereNotNull('payment_level_id')
            ->orderByDesc('id')
            ->first();

        if ($latestFee && $latestFee->paymentLevel) {
            return $latestFee->paymentLevel;
        }

        return $student->paymentlevel;
    }

    private function nextDueSequence(Student $student): int
    {
        $lastPaid = $this->lastPaidPaymentLevel($student);

        if ($lastPaid) {
            return ((int) $lastPaid->sequence) + 1;
        }

        $fallbackNextLevel = $this->fallbackNextLevelFromBatch($student);
        return $fallbackNextLevel ? (int) $fallbackNextLevel->sequence : 1;
    }

    private function fallbackNextLevelFromBatch(Student $student): ?Paymentlevel
    {
        $studentLastBatch = StudentBatch::where('student_id', $student->id)
            ->orderByDesc('id')
            ->first();

        if (! $studentLastBatch || ! $studentLastBatch->batch) {
            return Paymentlevel::where('status', 'ACTIVE')->orderByRaw('CAST(sequence AS UNSIGNED) ASC')->first();
        }

        $paymentLevel = Paymentlevel::where('level_id', $studentLastBatch->batch->level_id)
            ->where('status', 'ACTIVE')
            ->orderByRaw('CAST(sequence AS UNSIGNED) ASC')
            ->first();

        if ($studentLastBatch->batch->status !== 'ACTIVE' && $paymentLevel) {
            return Paymentlevel::where('sequence', ((int) $paymentLevel->sequence) + 1)
                ->where('status', 'ACTIVE')
                ->first();
        }

        return $paymentLevel;
    }

    private function buildPlan(Student $student, Collection $levels): array
    {
        if ($levels->isEmpty()) {
            return ['ok' => false, 'message' => 'No upcoming payment level is configured.'];
        }

        $paymentCountry = $this->countryPaymentConfig($student);
        $feeColumn = $paymentCountry['column'];
        $currency = $paymentCountry['currency'];

        if (! $feeColumn || ! $currency) {
            return ['ok' => false, 'message' => "Payment amount is not configured for {$student->country}."];
        }

        $originalAmount = (float) $levels->sum($feeColumn);
        $discountPercent = $levels->count() === 3 ? self::THREE_LEVEL_DISCOUNT_PERCENT : 0;
        $discountAmount = $discountPercent > 0 ? round(($originalAmount * $discountPercent) / 100, 2) : 0.0;
        $amount = round($originalAmount - $discountAmount, 2);

        if ($amount <= 0) {
            return ['ok' => false, 'message' => "Payment amount is not configured for {$student->country}."];
        }

        return [
            'ok' => true,
            'levels' => $levels,
            'level_ids' => $levels->pluck('id')->values()->all(),
            'target_level' => $levels->last(),
            'original_amount' => $originalAmount,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'amount' => $amount,
            'currency' => $currency,
            'fee_column' => $feeColumn,
        ];
    }
}
