<dialog id="generate_tpmr_modal" class="modal">
   <div class="modal-box bg-white max-w-lg border border-slate-200 shadow-xl rounded-2xl p-0"><!-- Header -->
    <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-slate-100">
     <h3 class="text-lg font-bold text-slate-800">Generate TPMR</h3>
     <form method="dialog">
      
      <button class="btn btn-sm btn-ghost btn-circle text-slate-400 hover:text-slate-600"> <i data-lucide="x" class="w-5 h-5"></i> </button>
     </form>
    </div>
    
    <!-- Body -->
    <div class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto">
      
      
      <!-- Region -->
     <div class="form-control w-full"><label class="label pb-1"><span class="label-text font-medium text-slate-700">Region</span></label> <select id="region" class="select select-bordered w-full bg-white border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700"> 
        <option value="">All Regions</option> 
        <option value="CO">Central Office</option>
        <option value="NCR">NCR</option> 
        <option value="R1">Region I</option>
        <option value="R2">Region II</option>
        <option value="R3">Region III</option>
        <option value="R4A">Region IV-A</option>
        <option value="R4B">Region IV-B</option>
        <option value="R5">Region V</option>
        <option value="R6">Region VI</option>
        <option value="NIR">NIR</option>
        <option value="R7">Region VII</option>
        <option value="R8">Region VIII</option>
        <option value="R9">Region IV</option>
        <option value="R10">Region X</option>
        <option value="R11">Region XI</option>
        <option value="R12">Region XII</option>
        <option value="CAR">CAR</option>
        <option value="CARAGA">CARAGA</option>
     </select>
     </div>
     
     
     <!-- Filter -->
     <div class="form-control w-full"><label class="label pb-1"><span class="label-text font-medium text-slate-700">Filter</span></label> <select id="filter" class="select select-bordered w-full bg-white border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700" onchange="handleFilterChange()"> <option value="">All</option> <option value="monthly">Monthly</option> <option value="annual">Annual</option> </select>
     </div>
     
     
     <!-- Month (conditional) -->
     <div id="month_group" class="form-control w-full hidden"><label class="label pb-1"><span class="label-text font-medium text-slate-700">Month</span></label> 
        <select id="month" class="select select-bordered w-full bg-white border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700"> <option value="">Select month</option> <option value="1">January</option> <option value="2">February</option> <option value="3">March</option> <option value="4">April</option> <option value="5">May</option> <option value="6">June</option> <option value="7">July</option> <option value="8">August</option> <option value="9">September</option> <option value="10">October</option> <option value="11">November</option> <option value="12">December</option> </select>
     </div>
     
     <!-- Year (conditional) -->
     <div id="year_group" class="form-control w-full "><label class="label pb-1"><span class="label-text font-medium text-slate-700">Year</span></label> <input type="number" id="year" class="input input-bordered w-full bg-white border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700" placeholder="e.g. 2025">
     </div>
     <div class="divider my-1 before:bg-slate-200 after:bg-slate-200"></div>
     
     <!-- Prepared By Group -->
     <div>
      <div class="flex items-center justify-between mb-3"><span class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Prepared By</span> <button type="button" onclick="openEmployeeSelector('prepared')" class="btn btn-xs btn-outline border-blue-500 text-blue-600 hover:bg-blue-50 hover:border-blue-500 gap-1"> 
        <i class="fa-solid fa-magnifying-glass"></i> Select Employee </button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

       <div class="form-control w-full"><label class="label pb-1">
        <span class="label-text text-sm text-slate-600">Name</span>
      </label> 
      
      <input type="text" id="prepared_name" class="input input-bordered w-full bg-slate-50 border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700" placeholder="Full name" >
       </div>

       <div class="form-control w-full"><label class="label pb-1"><span class="label-text text-sm text-slate-600">Position</span></label> <input type="text" id="prepared_position" class="input input-bordered w-full bg-slate-50 border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700" placeholder="Position/title" >
       </div>

      </div>

      <div class="form-control w-full">
        <label class="label pb-1">
          <span class="label-text text-sm text-slate-600">Date</span>
        </label>
        <input type="date" id="prepared_date"
          class="input input-bordered w-full bg-slate-50 border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700">
      </div>
     </div>
     <div class="divider my-1 before:bg-slate-200 after:bg-slate-200"></div>
     
     <!-- Noted By Group -->
     <div>
      <div class="flex items-center justify-between mb-3"><span class="text-sm font-semibold text-slate-800 uppercase tracking-wide">Noted By</span> <button type="button" onclick="openEmployeeSelector('noted')" class="btn btn-xs btn-outline border-blue-500 text-blue-600 hover:bg-blue-50 hover:border-blue-500 gap-1"> 
        <i class="fa-solid fa-magnifying-glass"></i> Select Employee </button>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
       <div class="form-control w-full"><label class="label pb-1"><span class="label-text text-sm text-slate-600">Name</span></label> <input type="text" id="noted_name" class="input input-bordered w-full bg-slate-50 border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700" placeholder="Full name" >
       </div>
       <div class="form-control w-full"><label class="label pb-1"><span class="label-text text-sm text-slate-600">Position</span></label> <input type="text" id="noted_position" class="input input-bordered w-full bg-slate-50 border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700" placeholder="Position/title" >
       </div>
      </div>

      <div class="form-control w-full">
        <label class="label pb-1">
          <span class="label-text text-sm text-slate-600">Date</span>
        </label>
        <input type="date" id="noted_date"
          class="input input-bordered w-full bg-slate-50 border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700">
      </div>


     </div>
    </div>
    
    <!-- Footer -->
    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
     <div id="loading" style="display:none;" class="flex items-center gap-2 text-sm text-blue-600"><span class="loading loading-spinner loading-sm"></span> Generating PDF...
     </div>
     <div class="flex-1"></div>
     <div class="flex gap-2">
      <form method="dialog"><button class="btn btn-ghost text-slate-600">Cancel</button>
      </form><button id="generateTPMR" class="btn bg-blue-600 hover:bg-blue-700 text-white border-none gap-2"> <i data-lucide="file-text" class="w-4 h-4"></i> Generate TPMR PDF </button>
     </div>
    </div>
   </div>
   <form method="dialog" class="modal-backdrop"><button>close</button>
   </form>
  </dialog> <!-- Employee Selector Modal --> 
  
  
  <dialog id="employee_selector_modal" class="modal">
   <div class="modal-box bg-white max-w-md border border-slate-200 shadow-xl rounded-2xl p-0">
    <div class="flex items-center justify-between px-6 pt-5 pb-3 border-b border-slate-100">

     <h3 class="text-base font-bold text-slate-800">Select Employee</h3>

     <form method="dialog"><button class="btn btn-sm btn-ghost btn-circle text-slate-400 hover:text-slate-600"> <i data-lucide="x" class="w-5 h-5"></i> </button>
     </form>
    </div>
    
    <!-- Search -->
    <div class="px-6 pt-4 pb-2">
     <div class="relative"> <i class="fa-solid fa-magnifying-glass  absolute left-3 top-1/2 -translate-y-1/2 text-slate-800"></i>
      
      <input type="text" id="employee_search" class="input input-bordered w-full pl-10 bg-white border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700 text-sm" placeholder="Search employees..." oninput="filterEmployees()">
     </div>

    </div><!-- Employee List -->
    <div id="employee_list" class="px-4 pb-4 max-h-64 overflow-y-auto space-y-1 mt-2"><!-- Populated dynamically -->
    </div>
    <div class="px-6 py-3 border-t border-slate-100 text-xs text-slate-400 text-center">
     Data loaded from employees
    </div>
   </div>
   <form method="dialog" class="modal-backdrop"><button>close</button>
   </form>



  </dialog>


<script>
    function showtpmrgenerate(){
        generate_tpmr_modal.showModal();
    }



    $('#generateTPMR').on('click', function () {

       const filter = $('#filter').val();
        const month = $('#month').val();

        if (filter === 'monthly') {
            if (!month || !$('#year').val()) {
                alert("Please select both month and year.");
                return;
            }
        }

        $('#loading').show();
        $('#generateTPMR').prop('disabled', true);

        let data = {
            _token: '{{ csrf_token() }}',
            region: $('#region').val(),
            filter: $('#filter').val(),
            year: $('#year').val(),

            prepared_name: $('#prepared_name').val(),
            prepared_position: $('#prepared_position').val(),
            prepared_date: $('#prepared_date').val(),

            noted_name: $('#noted_name').val(),
            noted_position: $('#noted_position').val(),
            noted_date: $('#noted_date').val(),
        };

        if ($('#filter').val() === 'monthly') {
            data.month = $('#month').val();
        }

        $.ajax({
            url: '/report/tpmr-pdf',
            method: 'POST',
            data: data, // ✅ FIXED: comma added here

            xhrFields: {
                responseType: 'blob'
            },

            success: function (response) {

                $('#loading').hide();
                $('#generateTPMR').prop('disabled', false);

                const blob = new Blob([response], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);

                const a = document.createElement('a');
                a.href = url;
                a.download = "TPMR-Participants-Report.pdf";
                document.body.appendChild(a);
                a.click();
                a.remove();

                window.URL.revokeObjectURL(url);
                
                generate_tpmr_modal.close();
                showToast('TPMR generated successfully.', 'success');
            },

            error: function (xhr) {

              $('#loading').hide();
              $('#generateTPMR').prop('disabled', false);

              if (xhr.response instanceof Blob) {

                  const reader = new FileReader();

                  reader.onload = function () {

                      console.log("🔥 RAW SERVER RESPONSE START 🔥");
                      console.log(reader.result);
                      console.log("🔥 RAW SERVER RESPONSE END 🔥");

                      try {
                          const json = JSON.parse(reader.result);
                          alert(json.message || 'Server error occurred');
                      } catch (e) {
                          alert("Check console. Non-JSON error returned.");
                      }
                  };

                  reader.readAsText(xhr.response);

              } else {
                  console.log(xhr);
                  alert('Unknown error');
              }
          }
        });

    });

    let currentTarget = '';

    const currentYear = new Date().getFullYear();

    document.getElementById('year').value = currentYear;

 
    function handleFilterChange() {
      const val = document.getElementById('filter').value;

      document.getElementById('month_group')
        .classList.toggle('hidden', val !== 'monthly');

      if (val !== 'monthly') {
        document.getElementById('month').value = '';
      }

      if (val === 'monthly') {
        document.getElementById('month').focus();
      }
    }

    function openEmployeeSelector(target) {
      currentTarget = target;
      lucide.createIcons();

    document.getElementById('employee_search').value = '';
    document.getElementById('employee_list').innerHTML = '<div class="text-center py-4"><span class="loading loading-spinner loading-sm"></span> Loading...</div>';

    document.getElementById('employee_selector_modal').showModal();

    $.ajax({
        url: '/employees',
        method: 'GET',
        success: function (data) {

            // format data
            employees = data.map(emp => ({
                id: emp.id,
                name: `${emp.FIRSTNAME} ${emp.MI ?? ''} ${emp.LASTNAME}`.trim(),
                position: emp.POSITION
            }));

            renderEmployeeList(employees);
        },
        error: function () {
            document.getElementById('employee_list').innerHTML =
                '<div class="text-center text-red-500 py-4">Failed to load employees</div>';
        }
    });
    }


     function filterEmployees() {
      const q = document.getElementById('employee_search').value.toLowerCase();
      const filtered = employees.filter(e =>
        e.name.toLowerCase().includes(q) || e.position.toLowerCase().includes(q)
      );
      renderEmployeeList(filtered);
    }

    function renderEmployeeList(list) {
      const container = document.getElementById('employee_list');
      if (list.length === 0) {
        container.innerHTML = '<div class="text-center text-slate-400 text-sm py-6">No employees found</div>';
        return;
      }
      container.innerHTML = list.map(emp => `
        <button type="button" onclick="selectEmployee(${emp.id})"
          class="w-full text-left px-4 py-3 rounded-xl hover:bg-blue-50 transition-colors flex items-center gap-3 group">
          <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-semibold flex-shrink-0">
            ${emp.name.split(' ').map(w => w[0]).join('').slice(0,2)}
          </div>
          <div class="min-w-0">
            <div class="text-sm font-medium text-slate-800 truncate group-hover:text-blue-700">${emp.name}</div>
            <div class="text-xs text-slate-500 truncate">${emp.position}</div>
          </div>
          <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 ml-auto flex-shrink-0 group-hover:text-blue-400"></i>
        </button>
      `).join('');
      lucide.createIcons();
    }

    function selectEmployee(id) {
      const emp = employees.find(e => e.id === id);
      if (!emp) return;
      document.getElementById(currentTarget + '_name').value = emp.name;
      document.getElementById(currentTarget + '_position').value = emp.position;
      document.getElementById('employee_selector_modal').close();
    }

     lucide.createIcons();
</script>