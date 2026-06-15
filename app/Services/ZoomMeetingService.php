<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;

class ZoomMeetingService
{
    protected $clientKey;
    protected $clientSecret;
    protected $accountId;
    
    public function __construct(string $clientKey, string $clientSecret, string $accountId)
    {
        $this->clientKey    = $clientKey;
        $this->clientSecret = $clientSecret;
        $this->accountId    = $accountId;
    }

    public function createMeeting(array $meetingData): array
    {
        try {
            $validated = $this->validateMeetingData($meetingData);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->generateToken(),
                'Content-Type' => 'application/json',
            ])->post("https://api.zoom.us/v2/users/me/meetings", [
                'topic'      => $validated['title'],
                'type'       => 2,
                'start_time' => Carbon::parse($validated['start_date_time'])->toIso8601String(),
                'duration'   => $validated['duration_in_minute'],
                'settings' => [
                    'auto_recording' => 'cloud',
                    'password' => '',
                    'use_pmi' => false,
                    'waiting_room' => true,
                    // 'join_before_host' => true, 
                ]
            ]);

            $response->throw();
            return $response->json();

        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function createDemoSessionMeeting(array $meetingData, $userId): array
    {
        try {
            $validated = $this->validateMeetingData($meetingData);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->generateToken(),
                'Content-Type' => 'application/json',
            ])->post("https://api.zoom.us/v2/users/{$userId}/meetings", [
                'topic'      => $validated['title'],
                'type'       => 1, // Instant meeting
                'start_time' => Carbon::parse($validated['start_date_time'])->toIso8601String(),
                'duration'   => $validated['duration_in_minute'],
                'settings' => [
                    'auto_recording' => 'cloud',
                    'password' => '',
                    'use_pmi' => false,
                    'waiting_room' => true
                ]
            ]);

            $response->throw();
            return $response->json();

        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function createNewUserMeeting(array $meetingData, $userId): array
    {
        try {
            $validated = $this->validateMeetingData($meetingData);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->generateToken(),
                'Content-Type' => 'application/json',
            ])->post("https://api.zoom.us/v2/users/{$userId}/meetings", [
                'topic'      => $validated['title'],
                'type'       => 1, // Instant meeting
                'start_time' => Carbon::parse($validated['start_date_time'])->toIso8601String(),
                'duration'   => $validated['duration_in_minute'],
                'settings' => [
                    'auto_recording' => 'cloud',
                    'password' => '',
                    'use_pmi' => false,
                    'waiting_room' => true
                ]
            ]);
            $response->throw();
            return $response->json();

        } catch (RequestException $e) {
            throw $e;
        }
    }

    
    public function createCoverUpClassMeeting(array $meetingData, $userId): array
    {
        try {
            $validated = $this->validateMeetingData($meetingData);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->generateToken(),
                'Content-Type' => 'application/json',
            ])->post("https://api.zoom.us/v2/users/{$userId}/meetings", [
                'topic'      => $validated['title'],
                'type'       => 1, // Instant meeting
                'start_time' => Carbon::parse($validated['start_date_time'])->toIso8601String(),
                'duration'   => $validated['duration_in_minute'],
                'settings' => [
                    'auto_recording' => 'cloud',
                    'password' => '',
                    'use_pmi' => false,
                    'waiting_room' => true
                ]
            ]);
            $response->throw();
            return $response->json();

        } catch (RequestException $e) {
            throw $e;
        }
    }

    // public function createNewUserMeeting(array $meetingData, $userId): array
    // {
    //     try {
    //         $validated = $this->validateMeetingData($meetingData);

    //         $token = $this->generateToken();

    //         $response = Http::withHeaders([
    //             'Authorization' => 'Bearer ' . $token,
    //             'Content-Type'  => 'application/json',
    //         ])->post("https://api.zoom.us/v2/users/{$userId}/meetings", [
    //             'topic'      => $validated['title'],
    //             'type'       => 2,
    //             'start_time' => Carbon::parse($validated['start_date_time'])->toIso8601String(),
    //             'duration'   => $validated['duration_in_minute'],
    //             'settings'   => [
    //                 'auto_recording' => 'cloud',
    //                 'use_pmi'        => false,
    //                 'waiting_room'   => true,
    //             ],
    //         ]);

    //         // Retry once if 404 or 401
    //         if ($response->status() === 404 || $response->status() === 401) {
    //             Cache::forget('zoom_access_token_' . md5($this->clientKey . $this->accountId)); // clear cache
    //             sleep(1); // give Zoom time to catch up (especially if user was just created)

    //             $newToken = $this->generateToken();
    //             $response = Http::withHeaders([
    //                 'Authorization' => 'Bearer ' . $newToken,
    //                 'Content-Type'  => 'application/json',
    //             ])->post("https://api.zoom.us/v2/users/{$userId}/meetings", [
    //                 'topic'      => $validated['title'],
    //                 'type'       => 2,
    //                 'start_time' => Carbon::parse($validated['start_date_time'])->toIso8601String(),
    //                 'duration'   => $validated['duration_in_minute'],
    //                 'settings'   => [
    //                     'auto_recording' => 'cloud',
    //                     'use_pmi'        => false,
    //                     'waiting_room'   => true,
    //                 ],
    //             ]);
    //         }

    //         $response->throw();

    //         return $response->json();

    //     } catch (RequestException $e) {
    //         throw $e;
    //     }
    // }


    public function getUsers(): array
    {
        $token = $this->generateToken();
        $allUsers = [];
        $nextPageToken = '';

        do {
            $url = "https://api.zoom.us/v2/users";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])->get($url, [
                'page_size' => 300,
                'next_page_token' => $nextPageToken,
            ]);

            if ($response->failed()) {
                Log::error("Failed to fetch Zoom users", [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
                break;
            }

            $data = $response->json();

            // Merge users
            if (!empty($data['users'])) {
                foreach ($data['users'] as $u) {
                    $allUsers[] = [
                        'id'    => $u['id'],
                        'email' => $u['email'],
                        'name'  => $u['first_name'] . ' ' . $u['last_name'],
                    ];
                }
            }

            // Update next page token
            $nextPageToken = $data['next_page_token'] ?? '';

        } while (!empty($nextPageToken));

        return $allUsers;
    }


    // public function getRecordingLinks($meetingId)
    // {
        
    //     $token = $this->generateToken();

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //         'Content-Type'  => 'application/json',
    //     ])->get("https://api.zoom.us/v2/meetings/{$meetingId}/recordings");

    //     if ($response->failed()) {
    //         return null;
    //     }

    //     $data = $response->json();

    //     $startTime = $data['start_time'] ?? null;

    //     $mp4File = collect($data['recording_files'] ?? [])->first(function ($file) {
    //         return $file['file_type'] === 'MP4' && $file['file_extension'] === 'MP4';
    //     });

    //     return [
    //         'start_time' => $startTime,
    //         'play_url'   => $mp4File['play_url'] ?? null,
    //     ];
    // }

    public function getRecordingLinks($userId)
    {
        $token = $this->generateToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->get("https://api.zoom.us/v2/users/{$userId}/recordings", [
            'from' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'to'   => Carbon::now()->format('Y-m-d'),
        ]);
       
        if ($response->failed()) {
            return null;
        }

        $data = $response->json();
        $recordings = [];

        foreach ($data['meetings'] ?? [] as $meeting) {
            foreach ($meeting['recording_files'] ?? [] as $file) {
                if (
                    isset($file['play_url']) &&
                    strtolower($file['file_type']) === 'mp4' &&
                    strtolower($file['file_extension']) === 'mp4' 
                ) {
                    $recordings[] = [
                        'meeting_id'     => $meeting['id'] ?? null,
                        'topic'          => $meeting['topic'] ?? null,
                        'timezone'       => $meeting['timezone'] ?? null,
                        'duration'       => $meeting['duration'] ?? null,
                        'start_time'     => $file['recording_start'] ?? $meeting['start_time'] ?? null,
                        'end_time'       => $file['recording_end'] ?? null,
                        'play_url'       => $file['play_url'],
                    ];
                }
            }
        }

        return $recordings;
    }


    protected function generateToken(): string
    {
        $cacheKey = 'zoom_access_token_' . md5($this->clientKey . $this->accountId);
        return Cache::remember($cacheKey, 3500, function () {
            $base64String = base64_encode($this->clientKey . ':' . $this->clientSecret);
            $response = Http::withHeaders([ 
                "Content-Type"  => "application/x-www-form-urlencoded",
                "Authorization" => "Basic {$base64String}",
            ])->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$this->accountId}");
            $response->throw();

            return $response->json()['access_token'];
        });
    }

    protected function validateMeetingData(array $meetingData): array
    {
        // Your validation logic here (or use Laravel Validator)
        if (empty($meetingData['title']) || empty($meetingData['duration_in_minute']) || empty($meetingData['start_date_time'])) {
            throw new \InvalidArgumentException("Missing required meeting data");
        }
        return $meetingData;
    }

    
}
