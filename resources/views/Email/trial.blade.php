@component('mail::message')

<p style="font-size: 16px; color: #555;"><strong>Dear Parent,</strong></p>

<p style="font-size: 16px; color: #555;">
    Warm greetings from <strong>Archer Chess Academy!</strong> 🌟
</p>

<p style="font-size: 16px; color: #555;">
    We’re excited to welcome your child to our <strong>{{ $studentAttendance->level->name }}</strong> Chess Course — a fun, structured, and interactive way to start their chess journey. Below are the complete course details and schedule for your reference.
</p>

<br>

<p style="font-size: 16px; color: #555;"><strong>🗓 Weekly & Monthly Schedule</strong></p>
<ul style="font-size: 16px; color: #555; line-height: 1.6;">
    <li>✅ 2 Regular Group Classes – Twice a week, from Monday to Saturday (as per your convenience)</li>
    <li>👥 Monthly Meet-Up Class – For group interaction, strategy talk & fun activities</li>
    <li>🧘 Monthly Psychology Class – To build focus, calmness & confidence</li>
    <li>🎯 Weekly Master Class – Every Sunday, led by senior coaches and titled players</li>
    <li>♟ Practice Tournament – Every Sunday</li>
    <li>🏅 Prize Tournament – Once a month, Sunday (Entry Fee Required)</li>
</ul>

<br>

<p style="font-size: 16px; color: #555;"><strong>🌟 Key Features</strong></p>
<ul style="font-size: 16px; color: #555; line-height: 1.6;">
    <li>🎥 Class Recordings – Never miss a lesson</li>
    <li>📊 Monthly Feedback & Leaderboard Performance</li>
    <li>🧩 1000+ Puzzles for Homework</li>
    <li>💻 Interactive Software Classroom for Live Learning</li>
    <li>🎓 E-Certificate upon Course Completion</li>
    <li>🧭 Smart LMS Access – Track recordings, fee details, and class links easily</li>
</ul>

<br>

<p style="font-size: 16px; color: #555;">
    We’re confident your child will enjoy learning chess with our experienced international coaches and well-designed curriculum.
</p>

<p style="font-size: 16px; color: #555;">
    If you have any questions or need assistance, feel free to reach out to us at 
    <a href="mailto:support@archerchessacademy.com">support@archerchessacademy.com</a> or simply reply to this email.
</p>

<br>

<p style="font-size: 16px; color: #555;">
    <strong>Warm Regards,</strong><br>
    Team Archer Chess Academy<br>
    🌐 <a href="https://www.archerchessacademy.com" target="_blank">www.archerchessacademy.com</a><br>
    ♟ <em>Building Thinkers, One Move at a Time</em>
</p>

@endcomponent
