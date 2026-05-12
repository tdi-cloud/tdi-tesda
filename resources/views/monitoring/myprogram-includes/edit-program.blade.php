<dialog id="edit_program_modal" class="modal fade-in">
    <div class="modal-box w-full max-w-2xl slide-up p-0 rounded-2xl dark:!border-slate-700 dark:!bg-slate-900">
        <!-- Modal Header -->
        <div class="w-full sticky top-0 border-b border-slate-300 dark:border-slate-600 p-5 bg-white dark:bg-slate-800">
            <h3 id="modalTitle" class="font-bold text-lg poppins-bold">Edit Training Program</h3>
        </div>


        <!-- Modal Body / Form -->
        <form id="programForm" class="space-y-4 p-4 h-100 overflow-auto border-b border-slate-300 dark:border-slate-600 dark:bg-slate-900" novalidate>
            <!-- Program Title -->
            @csrf
            <div class="form-control w-full">
                <label class="label"> <span class="label-text text-xs poppins-semibold">Program Title 
                    <span class="text-red-500">*</span></span> </label>

                    <span id="title_required" class=" text-xs poppins-semibold text-red-500"></span>

                <input 
                type="text" 
                id="programTitle"
                name="title"
                 placeholder="Enter program title" 
                 required
                    class="input input-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
            </div><!-- Description -->
            <div class="form-control w-full">
                <label class="label"> <span class="label-text text-xs poppins-semibold">Description</span> </label>
                <span id="description_required" class=" text-xs poppins-semibold text-red-500"></span>
                <textarea id="description" rows="3" name="description" placeholder="Brief description of the program"
                    class="textarea poppins-regular text-sm textarea-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700 resize-vertical"></textarea>
            </div>
            
            <!-- Two-column row: Competency & Modality -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-control w-full">
                    <label class="label"> 
                    <span class="label-text text-xs poppins-semibold">Competency 
                        <span class="text-red-500">*</span>
                    </span> </label>
                    
                    <span id="competency_required" class=" text-xs poppins-semibold text-red-500"></span>
                    <select id="competency" required
                        name="competency"
                    
                        class="select select-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                        <option value="" disabled selected>Select competency</option>
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label"> 
                        <span class="label-text text-xs poppins-semibold">Modality 
                            <span class="text-red-500">*</span>
                        </span> 
                    </label> 
                    <span id="modality_required" class=" text-xs poppins-semibold text-red-500"></span>
                    <select id="modality" required
                    name="modality"
                        class="select select-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                        <option value="" disabled selected>Select modality</option>
                        <option value="In-person">In-person</option>
                        <option value="Online">Online / Virtual</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="self paced">Self-Paced</option>
                    </select>
                </div>
            </div>
            
            <!-- Two-column row: Target Pax & Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-control w-full">
                    <label class="label"> 
                        <span class="label-text text-xs poppins-semibold">Target Pax 
                            <span class="text-red-500">*</span>
                        </span> 
                    </label> 
                    <span id="pax_required" class=" text-xs poppins-semibold text-red-500"></span>
                    <input type="number" id="targetPax"
                    name="pax"
                        min="1" placeholder="Number of participants" required
                        class="input input-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                </div>
                <div class="form-control w-full">
                    <label class="label"> 
                        <span class="label-text text-xs poppins-semibold">Category 
                            <span class="text-red-500">*</span>
                        </span> 
                    </label> 
                    <span id="category_required" class=" text-xs poppins-semibold text-red-500"></span>
                    <select id="category" required
                    name="category"
                        class="select select-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                        <option value="" disabled selected>Select category</option>
                        <option value="Benchmarking">Benchmarking</option>
                        <option value="Capability-Building">Capability-Building</option>
                        <option value="Executive-Office">Executive-Office</option>
                        <option value="Foreign-Bilateral">Foreign-Bilateral</option>
                        <option value="Foreign-FSTP">Foreign-FSTP</option>
                        <option value="Local-In-House">Local-In-House</option>
                        <option value="Local-Public">Local-Public</option>
                        <option value="Other-Foreign">Other Foreign Program</option>
                        <option value="Regional">Regional</option>
                        <option value="Team-Building">Team-Building</option>
                    </select>
                </div>
            </div><!-- Two-column row: Program Type & Office Initiated -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-control w-full">
                    <label class="label"> 
                        <span class="label-text text-xs poppins-semibold">Program Type 
                            <span class="text-red-500">*</span>
                        </span> 
                    </label> 
                    <span id="type_required" class=" text-xs poppins-semibold text-red-500"></span>
                    <select id="programType" required
                    name="type"
                        class="select select-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                        <option value="" disabled selected>Select program type</option>
                        <option value="ADMIN">ADMIN</option>
                        <option value="TECHNICAL">TECHNICAL</option>
                        <option value="SUPERVISORY/MANAGERIAL">SUPERVISORY/MANAGERIAL</option>
                        <option value="TEAM-BUILDING">TEAM-BUILDING</option>
                        <option value="OTHER">OTHER</option>
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label"> 
                        <span class="label-text text-xs poppins-semibold">Office Initiated 
                            <span
                                class="text-red-500">*</span>
                            </span>
                         </label> 
                         <span id="initiated_required" class=" text-xs poppins-semibold text-red-500"></span>
                         <select id="officeInitiated" required
                         name="initiated"
                        class="select select-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                        <option value="" disabled selected>Select office</option>
                        <option value="TDI">TESDA Development Institute (TDI)</option>
                        <option value="NTTA">Nation TVET Trainors Academy (NTTA)</option>
                        <option value="Other-EO">Other Executive Office</option>
                        <option value="Other-Provider">Other Training Provider</option>
                    </select>
                </div>
            </div><!-- Training Provider -->
            <div class="form-control w-full">
                <label class="label"> <span class="label-text text-xs poppins-semibold">Training Provider</span>
                </label> 
                <span id="provider_required" class=" text-xs poppins-semibold text-red-500"></span>
                <input type="text"
                name="provider"
                id="trainingProvider" placeholder="Name of training provider"
                    class="input input-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
            </div><!-- Three-column row: Program Cost, Fund Source, Origin -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="form-control w-full">
                    <label class="label"> <span class="label-text text-xs poppins-semibold">Program Cost</span>
                    </label> 
                    <span id="cost_required" class=" text-xs poppins-semibold text-red-500"></span>
                    <input type="number" name="cost" id="programCost" min="0" step="0.01" value="0"
                        class="input input-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                </div>
                <div class="form-control w-full">
                    <label class="label"> 
                        <span class="label-text text-xs poppins-semibold">Fund Source 
                            <span
                                class="text-red-500">*</span>
                            </span> 
                        </label> 
                        
                        <select id="fundSource" required
                        name="fund"
                        class="select select-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                        <option value="" disabled selected>Select fund source</option>
                        <option value="CO-SDP">Central Office - SDP</option>
                        <option value="RO-SDP">Regional Office - SDP</option>
                        <option value="Other-Office">Other Office</option>
                    </select>
                    <span id="fund_required" class=" text-xs poppins-semibold text-red-500"></span>
                </div>
                <div class="form-control w-full">
                    <label class="label">
                         <span class="label-text text-xs poppins-semibold">Origin <span
                                class="text-red-500">*</span>
                            </span>
                         </label> 
                         
                         <select id="origin" required
                         name="origin"
                        class="select select-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
                        <option value="" disabled selected>Select origin</option>
                        <option value="Local">Local</option>
                        <option value="Foreign">Foreign</option>
                    </select>

                    <span id="origin_required" class=" text-xs poppins-semibold text-red-500"></span>
                </div>
            </div>
        </form><!-- Modal Footer -->


        <div class="modal-action px-5 mb-5 ">
           
                <button type="button" id="cancelBtn"
                    class="btn btn-outline poppins-bold text-sm rounded-lg">Cancel</button>
         
            
            
            <button id="submitBtn" type="button" onclick="saveProgram(currentProgramId)"
                class="btn btn-primary gap-2 poppins-semibold text-sm rounded-lg"> <i data-lucide="save"
                    style="width:16px;height:16px"></i> Save Program 
                    <span id="create_load" class="hidden loading loading-spinner loading-sm"></span>
                </button>
        </div>
    </div>

    


</dialog>


<script>

     const competencies = [
  "Leadership - Practice Strategic and Critical Thinking (PSCT)",
  "Leadership - Drive Performance for Integrity and Service (DPIS)",
  "Leadership - Establish Linkages and Networking for Programs and Services (ELN)",
  "Leadership - Plan and Organize for Greater Impact (POGI)",
  "Leadership - Lead in a Continuously Changing Environment (LCCE)",
  "Leadership - Develop and Empower Others to Establish Collective Accountability for Results (DEO)",
  "Core - Exemplify Integrity",
  "Core - Deliver Service Excellence (DSE)",
  "Core - Solve Problems and Make Decisions (SPMD)",
  "Core - Work Effectively in TVET (WETE)",
  "Organizational - Deliver Programs and Services",
  "Organizational - Develop Lifelong Learning and Career Development Interventions (DLLCDI)",
  "Organizational - Write Effectively (WE)",
  "Organizational - Speak Effectively (SE)",
  "Organizational - Promote Learning and Innovation (PLI)",
  "Organizational - Establish Teamwork (ET)",
  "Technical - Financial Management - Accounting Competencies",
  "Technical - Financial Management - Budgeting Competencies",
  "Technical - Financial Management - Cash Management Competencies",
  "Technical - Financial Management - Procurement Competencies",
  "Technical - Financial Management - Financial Reporting and Analysis",
  "Technical - (HRM) Training and Development Competencies",
  "Technical - (HRM) Performance Management Competencies",
  "Technical - (HRM) Talent Acquisition Competencies",
  "Technical - (HRM) Presentation Skills",
  "Technical - Information Technology",
  "Technical - Effective Partnerships and Networking",
  "Technical - Planning and Execution Competencies",
  "Technical - Program Development and Management",
  "Technical - Quality Management and Assurance",
  "Technical - Standards Development",
  "TTI - (TTI) Conduct competency assessment",
  "TTI - (TTI) Develop learning materials",
  "TTI - (TTI) Develop learning materials for e-learning",
  "TTI - (TTI) Develop training curriculum",
  "TTI - (TTI) Implement enrolment systems and procedures",
  "TTI - (TTI) Evaluate training/learning effectiveness",
  "TTI - (TTI) Facilitate development of competency standards",
  "TTI - (TTI) Formulate institutional policies, guidelines and procedures",
  "TTI - (TTI) Facilitate learning sessions",
  "TTI - (TTI) Apply facilitation skills",
  "TTI - (TTI) Perform guidance services",
  "TTI - (TTI) Implement workplace health, safety, security practices and environmental requirements",
  "TTI - (TTI) Manage library",
  "TTI - (TTI) Manage training institution",
  "TTI - (TTI) Apply planning, organizing and delivering skills",
  "TTI - (TTI) Plan training sessions",
  "TTI - (TTI) Apply presentation skills",
  "TTI - (TTI) Generate resources",
  "TTI - (TTI) Supervise work-based learning",
  "TTI - (TTI) Conduct training needs assessment"
];

const selectComp = document.getElementById('competency');

competencies.forEach(item => {
  const option = document.createElement('option');
  option.value = item;
  option.textContent = item;
  selectComp.appendChild(option);
});


    let currentProgramId = null;


    function editProgModal(id){

        currentProgramId = id;
        
        lucide.createIcons();
        fetch(`/programs/${id}/edit`)
        .then(res => res.json())
        .then(program => {

         
            $('#programTitle').val(program.title);
            $('#description').val(program.description);
            $('#targetPax').val(program.pax);
            $('#trainingProvider').val(program.provider);
            $('#programCost').val(program.cost);

            // SELECTS (IMPORTANT: use .val AFTER options exist)
            $('#competency').val(program.competency);
            $('#modality').val(program.modality);
            $('#category').val(program.category);
            $('#programType').val(program.type);
            $('#officeInitiated').val(program.initiated);
            $('#fundSource').val(program.fund);
            $('#origin').val(program.origin);

 
            document.getElementById('edit_program_modal').showModal();
        });
    }

    $("#cancelBtn").on('click', ()=>{
        edit_program_modal.close();
    });

    function saveProgram(id) {

    const formData = {
        title: $('#programTitle').val(),
        description: $('#description').val(),
        competency: $('#competency').val(),
        modality: $('#modality').val(),
        pax: $('#targetPax').val(),
        category: $('#category').val(),
        type: $('#programType').val(),
        initiated: $('#officeInitiated').val(),
        provider: $('#trainingProvider').val(),
        cost: $('#programCost').val(),
        fund: $('#fundSource').val(),
        origin: $('#origin').val(),
    };

    fetch(`/programs/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, 'success');
        console.log(data.message);

        // close modal
        programForm.reset();
        document.getElementById('edit_program_modal').close();
        
    })
    .catch(err => console.error(err));
}


</script>