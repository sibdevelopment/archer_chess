{{-- resources/views/partials/camera-check.blade.php (DEBUG only) --}}
@auth
    <div
        style="position:fixed;left:12px;top:12px;z-index:99999;background:#fff;padding:8px;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,.12);font-size:13px;">
        <strong>DEBUG</strong><br>
        {{-- show what Laravel sees --}}
        camera_consented: {{ var_export((bool) optional(auth()->user())->camera_consented, true) }}<br>
        camera_available: {{ var_export((bool) optional(auth()->user())->camera_available, true) }}<br>
        camera_snapshot_path: {{ optional(auth()->user())->camera_snapshot_path ?? 'NULL' }}
    </div>

    <div id="camera-consent-modal"
        style="position:fixed;left:0;right:0;top:0;bottom:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);z-index:99998;">
        <div style="background:#fff;padding:20px;border-radius:8px;max-width:520px;width:100%;">
            <h3 style="margin-top:0">Camera permission — DEBUG MODE</h3>
            <p>Testing camera capture. This modal is forced visible for debugging — remove debug partial after testing.</p>
            <div style="text-align:right;margin-top:12px">
                <button id="camera-decline" style="margin-right:8px;padding:8px 12px;">Decline</button>
                <button id="camera-accept" style="padding:8px 12px;">I Consent (test)</button>
            </div>
        </div>
    </div>
@endauth

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('[camera-check] DOMContentLoaded');

        const modal = document.getElementById('camera-consent-modal');
        const btnAccept = document.getElementById('camera-accept');
        const btnDecline = document.getElementById('camera-decline');

        if (!modal) {
            console.warn('[camera-check] modal element not found');
            return;
        }
        if (!btnAccept || !btnDecline) {
            console.warn('[camera-check] buttons not found', {
                btnAccept,
                btnDecline
            });
            return;
        }

        btnDecline.addEventListener('click', async () => {
            console.log('[camera-check] Declined — sending status false,false');
            await sendCameraStatus(false, false, null);
            alert('Sent decline. Check network and laravel.log');
            modal.style.display = 'none';
        });

        btnAccept.addEventListener('click', async () => {
            console.log('[camera-check] Accept clicked — requesting camera permission');
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                const video = document.createElement('video');
                video.srcObject = stream;
                await video.play();

                // capture frame
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                const dataUrl = canvas.toDataURL('image/png');

                // stop camera
                stream.getTracks().forEach(t => t.stop());

                console.log('[camera-check] captured snapshot, sending to server');
                await sendCameraStatus(true, true, dataUrl);
                alert('Snapshot sent. Check phpMyAdmin and storage/app/public for file.');
            } catch (err) {
                console.warn('[camera-check] camera error or denied', err);
                await sendCameraStatus(true, false, null);
                alert('Camera denied or not available. Sent available=false.');
            } finally {
                modal.style.display = 'none';
            }
        });

        async function sendCameraStatus(consented, available, dataUrl) {
    try {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : null;
        console.log('[camera-check] page origin:', window.location.origin);
        console.log('[camera-check] fetch url:', "{{ route('admin.employee.camera.check') }}");
        console.log('[camera-check] csrf token present?', !!token);

        const res = await fetch("{{ route('admin.employee.camera.check') }}", {
            method: 'POST',
            credentials: 'same-origin', // ensure cookies (session) are sent
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest', // helpful for Laravel detection
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                consented: consented,
                available: available,
                snapshot: dataUrl
            })
        });

        console.log('[camera-check] fetch completed status', res.status);
        const json = await res.json().catch(()=>null);
        console.log('[camera-check] response json', json);
        return json;
    } catch (err) {
        console.error('[camera-check] fetch error', err);
        throw err;
    }
}


    });
</script>
