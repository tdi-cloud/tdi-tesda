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
  </style>
  
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
            <p class="text-sm"><strong><i class="fa-regular fa-calendar"></i></strong> <span id="event_start"></span></p>

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
              eventClick: function (info) {
                  showEventModal(info.event);
              },
              eventClassNames: function(info) {
                  return ['my-event'];
              }
          });

          calendar.render();
      });

      function showEventModal(event) {

        let props = event.extendedProps; // ✅ FIX: define props

        document.getElementById('event_batch').innerText = props.batch;
        document.getElementById('event_status1').innerText = props.status;
        document.getElementById('event_title').innerText = event.title;
        document.getElementById('event_venue').innerText = props.venue;
        const date = new Date(event.start);

        const options = { 
          month: 'short', 
          day: '2-digit', 
          year: 'numeric',
          weekday: 'short'
        };

        const formatted = date.toLocaleDateString('en-US', options)
          .replace(',', '');

       

        document.getElementById('event_start').innerText = formatted;

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