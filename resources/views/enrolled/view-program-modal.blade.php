<style>
                    .tab-panel {
                        display: none;
                    }

                    .tab-panel.active {
                        display: block;
                    }

                    .tab-btn.active {
                        border-bottom: 4px solid rgb(255, 174, 0);
                        font-weight: bold;
                    }
                </style>

<dialog id="view_program_modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl p-0 overflow-hidden rounded-2xl border-none">

        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost bg-black/30 absolute right-6 top-4 text-white">✕</button>
        </form>

        <div id="toast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-medium shadow-lg shadow-emerald-500/25 z-50 flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i> <span>Submitted successfully!</span>
        </div>
        
        
        <div class="w-full min-h-[50vh] max-h-[80vh] overflow-auto pb-4">

            <img src="" id="programCover" class="w-full h-50 rounded-[0px] rounded object-cover"> 

            <div class="p-4  flex gap-2 items-center">
                    <div class="badge badge-primary badge-soft poppins-bold " id="viewProgBarch"></div>
                    <div id="viewProgStatus"></div> 
                    <p class="text-sm text-slate-500 poppins-regular" id="viewProgCode"></p>
            </div>

            <div class="px-5 space-y-2 mb-5">
                <h1 class="poppins-bold leading-5 text-sky-900  dark:text-yellow-500"  id="viewProgTitle"></h1>
                <p class="poppins-regular text-xs text-slate-500 dark:text-slate-200 text-justify" id="viewProgDesc"></p>

                <div class="gap-2 grid grid-cols-3 ">

                    {{-- ATTENDANCE --}}
                    <div class="bg-blue-100 dark:bg-slate-700 rounded-md p-2 text-center">
                        <p class="poppins-regular text-[12px] text-slate-600 dark:text-slate-200">ATTENDANCE:</p>
                        <h1 class="poppins-bold text-sky-900 dark:text-white" id="viewHours">0/16h</h1>
                        <div id="viewAttendanceBadge"></div>

                    </div>

                    {{-- DURATION --}}
                    <div class="bg-blue-100 dark:bg-slate-700 rounded-md p-2 text-center">
                        <p class="poppins-regular text-[12px] text-slate-600 dark:text-slate-200">DURATION:</p>
                        <h1 class="poppins-bold text-sky-900 dark:text-white" id="viewFromHours"></h1>
                        <h1 class="poppins-regular text-slate-500  text-sm" id="viewToHours"></h1>

                    </div>

                    {{-- REQUIREMENTS --}}
                    <div class="bg-blue-100 dark:bg-slate-700 rounded-md p-2 text-center">
                        <p class="poppins-regular text-[12px] text-slate-600 dark:text-slate-200">REQUIREMENTS:</p>
                        <h1 class="poppins-bold text-sky-900 text-lg dark:text-white" >
                            <span id="subCount">0</span>/<span id="reqCount">0</span>
                            <h1 class="poppins-regular text-slate-600  text-sm" >Submissions</h1>
                        </h1>

                    </div>

                </div>

                

                <div class="space-y-2">
                    <div class="view-tabs border-b border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-sm poppins-regular ">
                        <button class="tab-btn p-2 active" data-target="requirements">Requriements</button>
                        <button class="tab-btn p-2 " data-target="certificate">Certificate</button>
                    </div>

                    <div id="requirements" class="tab-panel active">
                        <div id="submissionContent">
                            <div id="submissionLoad" class="flex justify-center flex-1 items-center h-20">
                                <span class="loading loading-bars loading-md texts-late-400"></span>
                            </div>
                            <div id="requirementsList"></div>
                        </div>
                    </div>

                    <div id="resource" class="tab-panel ">
                        <div id="rpContent" >                            
                            <h1>Resource Speakers</h1>
                        </div>
                    </div>

                    <div id="certificate" class="tab-panel ">
                        <div id="certContent" >                            
                            <div class="w-full h-50 bg-sky-100 dark:bg-sky-600 flex items-center justify-center rounded-2xl">
                                <h1 class="text-slate-400">No available certificate to show.</h1>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
      
    </div>
</dialog>


<dialog id="submission_modal" class="modal">
  <div class="modal-box rounded-2xl">

    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>

    <div class="w-full max-w-lg">
    <h1 id="formTitle" class="text-2xl poppins-bold text-slate-900 mb-6 dark:text-white">Submit Your Document</h1>
    <form id="submissionForm" enctype="multipart/form-data" class="space-y-5">
     <input type="hidden" name="participant_id" id="participant_id" > 
     <input type="hidden" name="program_code" id="program_code" > 
     <input type="hidden" name="batch_id"  id="batch_id"> 
     <input type="hidden" name="requirement_id" id="requirement_id"> <!-- Notes -->
     <div>
      <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5 dark:text-white">Notes</label> 

      <textarea name="notes" id="notes" rows="3" class="w-full rounded-xl border border-slate-300 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white px-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition" placeholder="Add any relevant notes..."></textarea>
     </div>
     
     <!-- Drop Zone -->
     <div>
      <label class="block text-sm font-medium text-slate-700 mb-1.5 dark:text-white">Upload PDF</label>
      <div id="dropZone" class="drop-zone relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center cursor-pointer group hover:border-blue-500 hover:bg-blue-50/60 transition">
       <input type="file" name="file" id="fileInput" accept="application/pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"> 
       
       
       <!-- Empty State -->
       <div id="emptyState" class="flex flex-col items-center gap-3">
        <div class="w-14 h-14 rounded-2xl bg-slate-200/80 flex items-center justify-center group-hover:bg-blue-100 transition">
         <i data-lucide="upload-cloud" class="w-7 h-7 text-slate-600 group-hover:text-blue-600 transition"></i>
        </div>
        <div>
         <p class="text-sm font-medium text-slate-700 dark:text-white">Drag &amp; drop your PDF here</p>
         <p class="text-xs text-slate-500 mt-1">or click to browse</p>
        </div>
       </div>
       
       <!-- File State -->
       <div id="fileState" class="hidden file-enter flex items-center gap-4 text-left">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
         <i data-lucide="file-check" class="w-6 h-6 text-emerald-600"></i>
        </div>


        <div class="min-w-0 flex-1">
         <p id="fileName" class="text-sm font-medium text-slate-900 dark:text-white truncate"></p>
         <p id="fileSize" class="text-xs text-slate-500 mt-0.5"></p>
        </div>
        
        
        <button type="button" id="removeFile" class="relative z-20 p-1.5 rounded-lg hover:bg-slate-200 text-slate-500 hover:text-red-600 transition flex-shrink-0"> <i data-lucide="x" class="w-4 h-4"></i> </button>
       </div>
      </div>
      <p id="fileError" class="hidden text-xs text-red-600 mt-1.5 ml-1"></p>
     </div>
     
     <!-- Submit --> 
     
     
     <button type="submit" id="submitBtn" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm transition active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed"> Submit </button> <!-- Toast -->
     
     
     


    </form>
   </div>


  </div>
</dialog>



<script>
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', function () {

            // remove active from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });

            // activate clicked button
            this.classList.add('active');

            // show target panel
            const target = this.getAttribute('data-target');
            document.getElementById(target).classList.add('active');
        });
    });
    $('#submissionLoad').fadeOut();


    function loadRequirements(program_code, participant_id, batch) {
        $('#requirementsList').html('');
        $('#submissionLoad').fadeIn();
        $('#program_code').val(program_code);
        $('#participant_id').val(participant_id);
        $('#batch_id').val(batch);
        $.get(`/requirements/${program_code}/${participant_id}`, function (data) {
        
            $('#submissionLoad').fadeOut();
            let html = '';

            $('#reqCount').text(data.length);

            if (!data || data.length === 0) {
                
                $('#requirementsList').html(`
                    <div class="text-center text-slate-400 py-5">
                        No requirements found for this program.
                    </div>
                `);
                return;
            }

            let submissionCount = 0;

            data.forEach(req => {

                if (req.submissions && req.submissions.length > 0) {
                    submissionCount += req.submissions.length;
                }
               
                let dueDates = req.due_date || [];
                let today = new Date();

                let statusHTML = '';
                let dueHTML = '';

                // check if submitted
                let submission = req.submissions[0] || null;
                let actionButton = '';
                let submissionHTML = ''; 

          

                if (submission) {
                    submissionHTML = `
                        <div class="${setSubState(submission.status)} flex gap-5 relative overflow-hidden dark:text-white mt-2 p-2 rounded-md text-xs poppins-regular">

                            <img src="https://png.pngtree.com/png-vector/20250129/ourmid/pngtree-document-3d-icon-isolated-on-a-transparent-background-symbolizing-files-and-png-image_15359368.png"
                            class="absolute w-50 left-1/2 -top-1/2 opacity-20"
                            >

                           
                                <button class="absolute top-2 right-2" onclick="deleteSubmission(${submission.id},'${program_code}',${participant_id},${batch})"><i class="fa-regular fa-trash-can text-red-500"></i></button>

                            <div>
                                <div class=''><strong>Status:</strong> ${setSubStateBadge(submission.status)}</div>
                                <div><strong>Date:</strong> ${formatDate(submission.submitted_at)}</div>
                                ${submission.file_path ? `
                                    <div>
                                        <a href="/storage/${submission.file_path}" target="_blank"
                                            class="text-blue-600 underline">
                                            View File
                                        </a>
                                    </div>
                                ` : ''}
                                
                            </div>

                            ${submission.notes ? `<div class="border-l border-gray-200 px-2 max-w-50">
                                <p class="text-xs poppins-bold">Note:<p>
                                <p class="text-xs poppins-regular">${submission.notes}<p>        
                            </div>` : ''}

                            ${submission.remarks ? `<div class="border-l border-gray-200 px-2 max-w-50">
                                <p class="text-xs poppins-bold">Remarks:<p>
                                <p class="text-xs poppins-regular">${submission.remarks}<p>        
                            </div>` : ''}

                        </div>
                    `;
                } else {
                    actionButton = `
                        <button onclick="submitRequirement(${req.id})"
                            class="bg-blue-600 text-white poppins-regular text-xs px-4 py-2 rounded hover:bg-blue-700 mt-2">
                            <i class="fa-solid fa-arrow-up-from-bracket"></i> Submit Requirement
                        </button>
                    `;
                }

                if (dueDates.length > 0) {

                    dueHTML = dueDates.map(date => {
                        const due = new Date(date);

                        let status = '';

                        if (submission) {
                            status = `<div class="text-right">
                                <p class="text-green-600 font-semibold">Submitted</p>
                                <p class="text-slate-400 text-xs poppins-regular">${timeAgo(submission.submitted_at)}</p>
                            </div>`;
                        } else if (today > due) {
                            status = `<span class="text-red-600 font-semibold">Overdue</span>`;
                        } else {
                            status = `<span class="text-yellow-500 font-semibold">Pending</span>`;
                        }

                        return `
                            <div class="">
                                ${status}
                            </div>
                        `;
                    }).join('');
                }

                html += `
                    <div class="p-3 border border-slate-300 dark:border-slate-600 rounded-md mb-2">
                        <div class="flex justify-between">
                            <div> 
                                <h1 class="poppins-bold text-sky-900 dark:text-white" ><i class="fa-regular fa-file-lines"></i> ${formatRequirementTitle(req.title)}</h1>
                                <p class="poppins-regular text-sm text-slate-400">Due: ${formatDate(req.due_date)}</p>    
                            </div>
                            <div class="text-sm">
                                ${dueHTML}
                            </div>
                            
                        </div>
                        ${submissionHTML} 
                        ${actionButton}
                    </div>
                `;
            });

            $('#subCount').text(submissionCount);


            $('#requirementsList').html(html);
        });
    }

    function setSubState(status) {
        const states = {
            'Pending':  'bg-yellow-100 border border-yellow-200 dark:bg-yellow-100/20',
            'Approved': 'bg-green-50 border border-green-200 dark:bg-green-100/20',
            'Rejected': 'bg-red-100 dark:bg-red-100/20',
            'Revision': 'bg-orange-50 dark:bg-orange-100/20',
        };
        return states[status] ?? 'bg-gray-50';
    }

    function setSubStateBadge(status) {
        const badges = {
            'Pending':  '<span class="badge badge-sm badge-warning">Pending</span>',
            'Approved': '<span class="badge badge-sm badge-success ">Approved</span>',
            'Rejected': '<span class="badge badge-sm badge-error">Rejected</span>',
            'Revision': '<span class="badge badge-sm badge-secondary">Rejected</span>',
        };
        return badges[status] ?? '<span class="badge">Unknown</span>';
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);

        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    function formatRequirementTitle(title) {
        if (!title) return '';

        const t = title.toUpperCase();

        if (t === 'TREAP') {
            return `Terminal Report`;
        }

        if (t === 'REAP') {
            return `Terminal and Re-entry Action Plan (T${title})`;
        }

        if (t === 'TDOR') {
            return `Training Development Outcome Report (${title})`
        }

        return title; // default (no change)
    }


    function submitRequirement(data){
        $('#requirement_id').val(data);
        submission_modal.showModal();
    }


    // SUBMISSION 

   
    // File handling
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const emptyState = document.getElementById('emptyState');
    const fileState = document.getElementById('fileState');
    const fileError = document.getElementById('fileError');
    let selectedFile = null;

    function showFile(file) {
      if (file.type !== 'application/pdf') {
        fileError.textContent = 'Please select a PDF file.';
        fileError.classList.remove('hidden');
        dropZone.classList.add('shake');
        setTimeout(() => dropZone.classList.remove('shake'), 400);
        return;
      }
      selectedFile = file;
      fileError.classList.add('hidden');
      document.getElementById('fileName').textContent = file.name;
      const kb = (file.size / 1024).toFixed(1);
      document.getElementById('fileSize').textContent = kb > 1024 ? `${(kb/1024).toFixed(1)} MB` : `${kb} KB`;
      emptyState.classList.add('hidden');
      fileState.classList.remove('hidden');
      fileState.classList.add('file-enter');
      dropZone.classList.add('has-file');
    }

    function clearFile() {
      selectedFile = null;
      fileInput.value = '';
      emptyState.classList.remove('hidden');
      fileState.classList.add('hidden');
      dropZone.classList.remove('has-file');
    }

    fileInput.addEventListener('change', (e) => { if (e.target.files[0]) showFile(e.target.files[0]); });
    document.getElementById('removeFile').addEventListener('click', (e) => { e.stopPropagation(); clearFile(); });

    ['dragenter','dragover'].forEach(evt => {
      dropZone.addEventListener(evt, (e) => { e.preventDefault(); dropZone.classList.add('dragover'); });
    });
    ['dragleave','drop'].forEach(evt => {
      dropZone.addEventListener(evt, (e) => { e.preventDefault(); dropZone.classList.remove('dragover'); });
    });
    dropZone.addEventListener('drop', (e) => {
      const file = e.dataTransfer.files[0];
      if (file) showFile(file);
    });

    // Submit
    document.getElementById('submissionForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = document.getElementById('submissionForm');
        const formData = new FormData(form);

        const fileInput = document.getElementById('fileInput');
        const fileError = document.getElementById('fileError');
        const toast = document.getElementById('toast');
        const submitBtn = document.getElementById('submitBtn');

        // reset error
        fileError.classList.add('hidden');
        fileError.textContent = '';

        // validation
        if (!fileInput.files.length) {
            fileError.textContent = 'Please upload a PDF file.';
            fileError.classList.remove('hidden');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        $.ajax({
            url: '/submissions/store', // 🔁 your Laravel route
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function (response) {
                // show toast
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 3000);
                // optional reset form
                form.reset();
                // reset UI file state
                document.getElementById('fileState').classList.add('hidden');
                document.getElementById('emptyState').classList.remove('hidden');
                clearFile();
                loadRequirements(response.data.program_code, 
                response.data.participant_id, 
                response.data.batch_id);
                $('#requirementsList').html('');
                submission_modal.close();

            },
            error: function (xhr) {
                if (xhr.responseJSON?.message) {
                    fileError.textContent = xhr.responseJSON.message;
                } else {
                    fileError.textContent = 'Something went wrong. Please try again.';
                }
                fileError.classList.remove('hidden');
            },
            complete: function () {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
            }
        });
    });

    function deleteSubmission(id,program,participant,batch) {
        if (!confirm('Are you sure you want to delete this submission?')) return;

        $.ajax({
            url: `/submissions/delete/${id}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.success) {
                    $('#requirementsList').html('');
                    loadRequirements(program, 
                    participant, 
                    batch);
                }else{
                    console.log(response);
                }
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Something went wrong.');
            },
        });
    }


    function timeAgo(dateString) {
    const seconds = Math.floor((new Date() - new Date(dateString)) / 1000);
    const intervals = [
        { label: 'year',   seconds: 31536000 },
        { label: 'month',  seconds: 2592000 },
        { label: 'week',   seconds: 604800 },
        { label: 'day',    seconds: 86400 },
        { label: 'hour',   seconds: 3600 },
        { label: 'minute', seconds: 60 },
        { label: 'second', seconds: 1 },
    ];

    const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });

    for (const interval of intervals) {
        const count = Math.floor(seconds / interval.seconds);
        if (count >= 1) return rtf.format(-count, interval.label);
    }
    return 'just now';
    }




    lucide.createIcons();
</script>