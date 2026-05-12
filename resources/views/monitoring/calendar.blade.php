<x-layout>
<x-slot:title>Dashboard</x-slot:title>
<x-monitoring-layout>
  <style>
    .my-event {
        background-color: #001ea1 !important;
        color: rgb(3, 0, 209) !important;
        border: 0px;
        border-radius: 0px;
        border-left: 5px solid rgb(153, 155, 252);
        padding: 2px;
        
    }


    /* COMPLETED */
    .event-completed {
        background-color: #16a34a !important;
        border-left: 5px solid #22c55e;
        color: white !important;
    }

    /* ONGOING */
    .event-upcoming {
        background-color: #2563eb !important;
        border-left: 5px solid #60a5fa;
        color: white !important;
    }

    /* PENDING */
    .event-pending {
        background-color: #f59e0b !important;
        border-left: 5px solid #fbbf24;
        color: white !important;
    }

    .event-active {
        background-color: #30b9f8 !important;
        border-left: 5px solid #006b7e;
        color: white !important;
    }

    /* CANCELLED */
    .event-cancelled {
        background-color: #dc2626 !important;
        border-left: 5px solid #f87171;
        color: white !important;
    }
  </style>

  <section class="hidden px-5 pb-5">
    <h2 class="poppins-bold text-lg mb-3">Programs per Month</h2>
    <div id="monthly_counts" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <!-- filled by JS -->
    </div>
</section>
  
  <dialog id="event_modal" class="modal">
    <div class="modal-box p-0 rounded-2xl">

        <img id="event_cover" class="w-full h-50 object-cover rounded " style="display:none;">

        <div class="p-5 poppins-regular space-y-2">
          <span >
            <div class="badge badge-primary poppins-medium " id="event_batch">Primary</div> 
            <div class="badge badge-soft badge-info" id="event_status1">Info</div>
          </span>


          <h3 id="event_title" class="poppins-bold leading-5 mt-4"></h3>

          <div>

            <p class="text-sm"><strong><i class="fa-solid fa-map-pin"></i></strong> <span id="event_venue"></span></p>
            <p class="text-sm"><strong><i class="fa-regular fa-calendar"></i></strong> <span id="event_start"></span> - <span id="event_end"></span></p>

          </div>

          

        </div>

        <div class="flex justify-end gap-2 p-4 border-t border-slate-300 dark:border-slate-600">
          <button class="btn btn-sm btn-soft btn-info" id="viewProg">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> View Details
          </button>

          <div class="modal-action m-0">
            <button class="btn btn-sm btn-soft btn-info" onclick="document.getElementById('event_modal').close()">
                <i class="fa-solid fa-check"></i>Confirm
            </button>
        </div>

        </div>

        

        

        

    </div>
</dialog>


<script>

      document.addEventListener('DOMContentLoaded', function () {
          var calendarEl = document.getElementById('calendar');

          var calendar = new FullCalendar.Calendar(calendarEl, {
              initialView: 'dayGridMonth',

              events: {
                  url: '/batches/events',
                  method: 'GET',
                  failure: function () {
                      alert('Error fetching events!');
                  }
              },
              eventSourceSuccess: function(rawEvents) {
                    renderMonthlyCounts(rawEvents);
                },
              eventClick: function (info) {
                  showEventModal(info.event);
              },
              eventClassNames: function(info) {

                  let status = (info.event.extendedProps.status || '').toLowerCase();

                  switch (status) {
                      case 'completed':
                          return ['event-completed'];

                      case 'upcoming':
                          return ['event-upcoming'];

                      case 'pending':
                          return ['event-pending'];

                      case 'cancelled':
                          return ['event-cancelled'];

                          case 'active':
                          return ['event-active'];

                      default:
                          return ['event-pending'];
                  }
              }
          });

          calendar.render();
      });

      function renderMonthlyCounts(events) {
    const monthNames = [
        'January','February','March','April','May','June',
        'July','August','September','October','November','December'
    ];

    // Count programs per month
    const counts = {};
    events.forEach(event => {
        if (!event.start) return;
        const date = new Date(event.start);
        const key = `${date.getFullYear()}-${date.getMonth()}`; // e.g. "2025-4"
        counts[key] = (counts[key] || { count: 0, month: date.getMonth(), year: date.getFullYear() });
        counts[key].count++;
    });

    // Sort by year & month
    const sorted = Object.values(counts).sort((a, b) =>
        a.year !== b.year ? a.year - b.year : a.month - b.month
    );

    const container = document.getElementById('monthly_counts');
    container.innerHTML = '';

    if (sorted.length === 0) {
        container.innerHTML = `<p class="text-sm text-gray-400">No programs found.</p>`;
        return;
    }

    sorted.forEach(({ month, year, count }) => {
        container.innerHTML += `
            <div class="bg-base-200 rounded-xl p-4 flex flex-col items-center shadow-sm">
                <span class="poppins-bold text-3xl text-primary">${count}</span>
                <span class="poppins-medium text-sm mt-1">${monthNames[month]}</span>
                <span class="text-xs text-gray-400">${year}</span>
            </div>
        `;
    });
}

      function showEventModal(event) {

        let props = event.extendedProps; // ✅ FIX: define props

        document.getElementById('event_batch').innerText = props.batch;
        document.getElementById('event_status1').innerText = props.status;
        document.getElementById('event_title').innerText = event.title;
        document.getElementById('event_venue').innerText = props.venue;
        const date = new Date(event.start);
        const date_end = new Date(event.end);

        const options = { 
          month: 'short', 
          day: '2-digit', 
          year: 'numeric',
          weekday: 'short'
        };

        const formatted_start = date.toLocaleDateString('en-US', options)
          .replace(',', '');

        const formatted_end = date_end.toLocaleDateString('en-US', options)
          .replace(',', '');

       

        document.getElementById('event_start').innerText = formatted_start;
        document.getElementById('event_end').innerText = formatted_end;

        let img = document.getElementById('event_cover');

        if (props.cover_image) {
            img.src = props.cover_image;
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
        }

        $('#viewProg').on('click', ()=>{
          window.location.href = `/programs/${props.id}`;
        })

        document.getElementById('event_modal').showModal();
    }

    </script>

<section class="p-5  flex-1 h-full ">
<div id='calendar' class="flex-1 h-full"></div>
</section>







</x-monitoring-layout>
</x-layout>