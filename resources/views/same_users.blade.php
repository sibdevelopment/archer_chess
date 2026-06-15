@foreach ($users as $user)
    @php
       $iscoach = 0;
       $isemployee = 0;

       $coach = App\Models\Coach::where('user_id', $user->id)->first();
        if ($coach) {
            $iscoach = 1;
        }else {
            $employee = App\Models\Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $isemployee = 1;
            }
        }

    @endphp
    @if ($iscoach == 0 && $isemployee == 0)
        <div class="card">
            <div class="card-header">
                <h3>{{ $user->id }})  {{ $user->first_name }} {{ $user->last_name }} {{ $user->mobile }}
                    @if ($iscoach)
                    -  <span class="badge badge-primary">Coach</span>
                    @endif
                    @if ($isemployee)
                    -  <span class="badge badge-success">Employee</span>
                    @endif
                </h3>
            </div>
        </div>
    @endif
@endforeach
