<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateDashboardMethods(function (array $methods): array {
            if (! in_array('startCoverupClass', $methods, true)) {
                $methods[] = 'startCoverupClass';
            }

            return $methods;
        });
    }

    public function down(): void
    {
        $this->updateDashboardMethods(function (array $methods): array {
            return array_values(array_filter(
                $methods,
                fn (string $method): bool => $method !== 'startCoverupClass'
            ));
        });
    }

    private function updateDashboardMethods(callable $update): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        $permission = DB::table('permissions')
            ->where('name', 'dashboard-view')
            ->first();

        if (! $permission) {
            return;
        }

        $methods = json_decode($permission->methods ?: '[]', true);
        $methods = is_array($methods) ? $methods : [];

        DB::table('permissions')
            ->where('id', $permission->id)
            ->update(['methods' => json_encode(array_values($update($methods)))]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
