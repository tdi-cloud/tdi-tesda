<x-layout>
<x-slot:title>Dashboard</x-slot:title>
<x-monitoring-layout>
<script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });

    </script>

<section class="p-5  flex-1 h-full ">
<div id='calendar' class="flex-1 h-full"></div>
</section>







</x-monitoring-layout>
</x-layout>