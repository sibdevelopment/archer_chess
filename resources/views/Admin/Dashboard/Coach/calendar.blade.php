
<div class="container-fluid" style="    --bs-gutter-x: 0px !important;">
    <div class="card" style=" margin-bottom: 0px !important;">
        <div>
            <div class="row gx-0">
                <div class="col-lg-12">
                    <div class="p-3 calender-sidebar app-calendar">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Pass PHP array to JavaScript
    var calendarData = @json($calendarData);

    $(document).ready(function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: calendarData,
            eventClick: function (clickInfo) {
                // Existing event click handler
            },
            dateClick: function (info) {
                // Dispatch a custom event with the selected date
                var event = new CustomEvent('dateSelected', { detail: { date: info.dateStr } });
                document.dispatchEvent(event);
            }
        });
        calendar.render();
    });
</script>


