<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (! function_exists('log_activity')) {
    function log_activity(
        string $type,
        string $title,
        ?string $description = null,
        ?string $page = null,
        ?string $refType = null,
        ?int $refId = null,
        ?int $userId = null,
        ?string $actorName = null
    ): void {
        $userId = $userId ?? Auth::id();
        $actorName = $actorName ?? (Auth::user()->name ?? Auth::user()->username ?? Auth::user()->email ?? null);

        ActivityLog::create([
            'user_id' => $userId,
            'actor_name' => $actorName,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'page' => $page,
            'ref_type' => $refType,
            'ref_id' => $refId,
        ]);
    }
}
