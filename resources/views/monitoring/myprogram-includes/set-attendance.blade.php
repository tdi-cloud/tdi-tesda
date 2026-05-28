<style>
  
    .file-drop-zone { border: 2px dashed #cbd5e1; transition: all 0.2s; }
    .file-drop-zone:hover, .file-drop-zone.dragover { border-color: #6366f1; background: #eef2ff; }
    .fade-in { animation: fadeIn 0.2s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
  </style>

  

<dialog id="attendanceModal" class="modal">
    <div class="modal-box w-full max-w-md bg-white dark:!bg-slate-800 dark:text-slate-100 rounded-2xl shadow-2xl p-0 overflow-visible"><!-- Modal Header -->
     <div class="flex items-center justify-between px-6 pt-6 pb-2">
      <h3 id="modalTitle" class="font-bold text-xl text-slate-800 dark:text-slate-100">Set Attendance</h3>
      <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-slate-400 hover:text-slate-600"> <i data-lucide="x" style="width:18px;height:18px;"></i> </button>
      </form>
     </div>
     <div class="divider my-0 px-6"></div>
     <!-- Modal Body -->
     <div class="px-6 py-5 space-y-5"><!-- Participant Info -->
      <div class="flex items-center gap-3 p-3 bg-slate-50  dark:bg-slate-600 rounded-xl">
       <div class="avatar placeholder">
        <div class="bg-indigo-100 text-indigo-600 rounded-full w-10 h-10 flex items-center justify-center">
          <i data-lucide="user" style="width:20px;height:20px;"></i>
        </div>
       </div>
       <div>
        <p class="font-semibold text-slate-700 text-sm dark:text-white" id="part_name">...</p>
        <p class="text-xs text-slate-400 dark:text-slate-200" id="part_empcode">...</p>
       </div>
      </div>
      <!-- Status Select -->
      <div class="form-control w-full"><label class="label" for="statusSelect"> <span class="label-text font-medium text-slate-600 dark:text-slate-200">Attendance Status</span> </label> 
        <select id="statusSelect" class="select dark:bg-slate-600 dark:text-white select-bordered w-full bg-white focus:outline-indigo-400"> 
            <option disabled selected value="">Select status…</option> 
            <option value="Complete">✅ Complete</option> 
            <option value="Absent">❌ Absent</option> 
            <option value="Pending">⏳ Pending</option> </select>
      </div><!-- Conditional: Complete → Hours Input -->

      <input type="hidden" id="participant_id">
      <input type="hidden" id="batch_id" >
      
      
      
      <div id="hoursSection" class="hidden fade-in">
       <div class="form-control w-full"><label class="label" for="hoursInput"> <span class="label-text font-medium text-slate-600 dark:text-slate-200">Total Hours Attended</span> </label> <input id="hoursInput" type="number" min="0" max="24" step="0.5" placeholder="e.g. 8" class="input dark:bg-slate-600 input-bordered w-full bg-white focus:outline-indigo-400"> 
        {{-- <label class="label"> <span class="label-text-alt text-slate-400">Enter hours between 0 and 24</span> </label> --}}
       </div>
       
       <button id="setAllBtn" type="button" class="mt-4 btn btn-outline btn-sm w-full gap-2 text-indigo-600 border-indigo-200 hover:bg-indigo-50">
         <i data-lucide="users-check" style="width:16px;height:16px;"></i> Set for All Participants
         </button>
      </div><!-- Conditional: Absent → File Upload -->
      <div id="fileSection" class="hidden fade-in">
       <div class="form-control w-full"><label class="label"> <span class="label-text font-medium text-slate-600">Justification Document</span> </label>
        <div id="fileDropZone" class="file-drop-zone rounded-xl p-5 text-center cursor-pointer">
         <div id="filePrompt" class="space-y-2">
          <div class="flex justify-center">
           <div class="bg-indigo-50 rounded-full p-3"><i data-lucide="upload-cloud" style="width:28px;height:28px;color:#6366f1;"></i>
           </div>
          </div>
          <p class="text-sm font-medium text-slate-600">Click or drag &amp; drop PDF here</p>
          <p class="text-xs text-slate-400">PDF files only, max 10MB</p>
         </div>
         <div id="fileInfo" class="hidden items-center gap-3 justify-center">
          <div class="bg-red-50 rounded-lg p-2"><i data-lucide="file-text" style="width:22px;height:22px;color:#ef4444;"></i>
          </div>
          <div class="text-left">
           <p id="fileName" class="text-sm font-medium text-slate-700 truncate max-w-[200px]"></p>
           <p id="fileSize" class="text-xs text-slate-400"></p>
          </div><button id="removeFileBtn" type="button" class="btn btn-ghost btn-xs btn-circle text-slate-400 hover:text-red-500"> <i data-lucide="x" style="width:14px;height:14px;"></i> </button>
         </div>
        </div><input id="fileInput" type="file" accept=".pdf" class="hidden">
       </div>
      </div><!-- Conditional: Pending → Info banner -->
      <div id="pendingSection" class="hidden fade-in">
       <div class="alert bg-amber-50 border border-amber-200 text-amber-700 text-sm"><i data-lucide="info" style="width:16px;height:16px;"></i> <span>No additional information needed for pending status.</span>
       </div>
      </div>
     </div><!-- Modal Footer -->
     <div class="px-6 pb-6 pt-2 flex gap-3 justify-end">
      <form method="dialog" class="inline"><button class="btn btn-ghost text-slate-500">Cancel</button>
      </form>
      <button id="saveBtn" disabled class="btn btn-primary text-white shadow-none gap-2 shadow-md shadow-indigo-200 disabled:opacity-50"> <i data-lucide="save" style="width:16px;height:16px;"></i> Save </button>
     </div>
    </div>
    <form method="dialog" class="modal-backdrop">
     <button>close</button>
    </form>
   </dialog>



   <div id="successToast" class="toast toast-end toast-bottom z-50 hidden">
    <div class="alert bg-emerald-500 text-white shadow-lg border-0"><i data-lucide="check-circle" style="width:20px;height:20px;"></i> <span id="toastMsg">Attendance saved successfully!</span>
    </div>
   </div>
  </div>
  <script>
   // === DOM refs ===
const modal = document.getElementById('attendanceModal');
const statusSelect = document.getElementById('statusSelect');
const hoursSection = document.getElementById('hoursSection');
const fileSection = document.getElementById('fileSection');
const pendingSection = document.getElementById('pendingSection');
const hoursInput = document.getElementById('hoursInput');
const fileInput = document.getElementById('fileInput');
const fileDropZone = document.getElementById('fileDropZone');
const filePrompt = document.getElementById('filePrompt');
const fileInfo = document.getElementById('fileInfo');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const removeFileBtn = document.getElementById('removeFileBtn');
const saveBtn = document.getElementById('saveBtn');
const setAllBtn = document.getElementById('setAllBtn');

let selectedAbsentFile = null;

// ======================
// STATUS CHANGE
// ======================
statusSelect.addEventListener('change', () => {
  const v = statusSelect.value;

  hoursSection.classList.toggle('hidden', v !== 'Complete');
  fileSection.classList.toggle('hidden', v !== 'Absent');
  pendingSection.classList.toggle('hidden', v !== 'Pending');

  validateForm();
});

// ======================
// HOURS INPUT
// ======================
hoursInput.addEventListener('input', validateForm);

// ======================
// FILE HANDLING
// ======================
fileDropZone.addEventListener('click', () => fileInput.click());

fileDropZone.addEventListener('dragover', (e) => {
  e.preventDefault();
  fileDropZone.classList.add('dragover');
});

fileDropZone.addEventListener('dragleave', () => {
  fileDropZone.classList.remove('dragover');
});

fileDropZone.addEventListener('drop', (e) => {
  e.preventDefault();
  fileDropZone.classList.remove('dragover');

  const f = e.dataTransfer.files[0];

  if (f) {
    if (f.type === 'application/pdf') handleFile(f);
    else showToast('Only PDF files are allowed', 'error');
  }
});

fileInput.addEventListener('change', () => {
  if (fileInput.files[0]) {
    const f = fileInput.files[0];

    if (f.type === 'application/pdf') handleFile(f);
    else {
      showToast('Only PDF files are allowed', 'error');
      fileInput.value = '';
    }
  }
});

removeFileBtn.addEventListener('click', (e) => {
  e.stopPropagation();

  selectedAbsentFile = null;
  fileInput.value = '';

  filePrompt.classList.remove('hidden');
  fileInfo.classList.add('hidden');
  fileInfo.classList.remove('flex');

  validateForm();
});

// ======================
// HANDLE FILE
// ======================
function handleFile(f) {
  selectedAbsentFile = f;

  fileName.textContent = f.name;
  fileSize.textContent = (f.size / 1024).toFixed(1) + ' KB';

  filePrompt.classList.add('hidden');
  fileInfo.classList.remove('hidden');
  fileInfo.classList.add('flex');

  validateForm();
}

// ======================
// VALIDATION (FIXED)
// ======================
function validateForm() {
  const v = statusSelect.value;
  let valid = false;

  if (v === 'Complete') {
    valid = hoursInput.value !== '' && Number(hoursInput.value) >= 0;
  } 
  else if (v === 'Absent') {
    valid = selectedAbsentFile !== null; // ✅ FIXED
  } 
  else if (v === 'Pending') {
    valid = true;
  }

  saveBtn.disabled = !valid;
}

// ======================
// SAVE (CONNECTED TO LARAVEL)
// ======================
saveBtn.addEventListener('click', (e) => {
  e.preventDefault();

  let formData = new FormData();

  formData.append('participant_id', document.getElementById('participant_id').value);
  formData.append('status', statusSelect.value);
  formData.append('hours', hoursInput.value);

  if (selectedAbsentFile) {
    formData.append('file', selectedAbsentFile);
  }

  saveBtn.disabled = true;
  saveBtn.innerHTML = 'Saving...';

  $.ajax({
    url: '/participant/save-attendance',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(res) {
      console.log(res);

      modal.close();
      showToast('Attendance saved successfully!');
      resetModal();
      fetchBatches();
    },
    error: function(err) {
      console.log(err);

      showToast('Error saving attendance');

      saveBtn.disabled = false;
      saveBtn.innerHTML = 'Save';
    }
  });
});

// ======================
// SET ALL (OPTIONAL)
// ======================
setAllBtn.addEventListener('click', (e) => {
  e.preventDefault();

  const hours = hoursInput.value;

  if (hours === '' || Number(hours) < 0) {
    showToast('Please enter valid hours first');
    return;
  }
  const originalText = setAllBtn.innerHTML;

  setAllBtn.disabled = true;
  setAllBtn.innerHTML = 'Updating...';

  const batchId = document.getElementById('batch_id').value;

  $.ajax({
    url: '/participant/set-all-hours', // 👈 create this route
    type: 'POST',
    data: {
      hours: hours,
      batch_id: batchId
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(res) {
      console.log(res);

      showToast('All participants updated successfully!');
      modal.close();
      resetModal();
      fetchBatches();
      setAllBtn.disabled = false;
      setAllBtn.innerHTML = originalText;
    },
    error: function(err) {
      console.log(err);

      showToast('Error updating all participants');

      setAllBtn.disabled = false;
      setAllBtn.innerHTML = 'Set All';
    }
  });
});

// ======================
// RESET MODAL
// ======================
function resetModal() {
  statusSelect.value = '';
  hoursInput.value = '';

  // ✅ Clear file state completely
  selectedAbsentFile = null;
  fileInput.value = '';
  fileName.textContent = '';
  fileSize.textContent = '';

  hoursSection.classList.add('hidden');
  fileSection.classList.add('hidden');
  pendingSection.classList.add('hidden');

  // ✅ Reset file drop zone display
  filePrompt.classList.remove('hidden');
  fileInfo.classList.add('hidden');
  fileInfo.classList.remove('flex');

  saveBtn.disabled = true;
  saveBtn.innerHTML = '<i data-lucide="save" style="width:16px;height:16px;"></i> Save';
  lucide.createIcons();
}

modal.addEventListener('close', () => {
  resetModal();
});

// ======================
// TOAST
// ======================
function showToast(msg) {
  const toast = document.getElementById('successToast');

  document.getElementById('toastMsg').textContent = msg;

  toast.classList.remove('hidden');

  setTimeout(() => {
    toast.classList.add('hidden');
  }, 3000);
}

// ======================
lucide.createIcons();
  </script>