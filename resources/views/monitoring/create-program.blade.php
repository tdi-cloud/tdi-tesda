<dialog id="trainingModal" class="modal fade-in">
    <div class="modal-box w-full max-w-2xl slide-up p-0 rounded-2xl ">
        <!-- Modal Header -->
        <div class="w-full sticky top-0 border-b border-slate-300 dark:border-slate-600 p-5 bg-white dark:bg-slate-800">
            <h3 id="modalTitle" class="font-bold text-lg poppins-bold">Create Training Program</h3>
        </div>


        <!-- Modal Body / Form -->
        <form id="programForm" class="space-y-4 p-4 h-100 overflow-auto border-b border-slate-300 dark:border-slate-600" novalidate>
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



            </div>
            
            
            <!-- Two-column row: Target Pax & Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

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



            </div><!-- Two-column row: Program Type & Office Initiated -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            
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
            
            <!-- Training Provider -->
            <div class="form-control w-full">
                <label class="label"> <span class="label-text text-xs poppins-semibold">Training Provider</span>
                </label> 
                <span id="provider_required" class=" text-xs poppins-semibold text-red-500"></span>
                <input type="text"
                name="provider"
                id="trainingProvider" placeholder="Name of training provider"
                    class="input input-bordered w-full poppins-regular text-sm rounded-lg bg-slate-100 dark:bg-slate-700">
            </div><!-- Three-column row: Program Cost, Fund Source, Origin -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

            </div>
        </form><!-- Modal Footer -->


        <div class="modal-action px-5 mb-5 ">
            <form method="dialog">
                <button type="button" id="cancelBtn"
                    class="btn btn-outline poppins-bold text-sm rounded-lg">Cancel</button>
            </form><button id="submitBtn" type="button"
                class="btn btn-primary gap-2 poppins-semibold text-sm rounded-lg"> <i data-lucide="save"
                    style="width:16px;height:16px"></i> Save Program 
                    <span id="create_load" class="hidden loading loading-spinner loading-sm"></span>
                </button>
        </div>
    </div>

    


</dialog>


<div id="prog_result_toast" class="toast hidden">
    <div class="alert alert-success">
        <span class="text-[12px] poppins-regular"><i class="fa-solid fa-check"></i> New Program Created</span>
    </div>
</div>


<script>
    // --- Modal Logic ---
    const create_modal = document.getElementById('trainingModal');
    const openBtn = document.getElementById('openModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const submitBtn = document.getElementById('submitBtn');
    const toast = document.getElementById('successToast');

    function openModal() {
        create_modal.showModal();
        blank_modal.close();
    }

    function closeModal() {
        create_modal.close();
        document.getElementById('trainingProvider').removeAttribute('readonly');
        document.getElementById('trainingProvider').classList.remove('opacity-60');
    }

    openBtn.addEventListener('click', openModal);
    cancelBtn.addEventListener('click', closeModal);
    create_modal.addEventListener('click', (e) => {
        if (e.target === create_modal) closeModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && create_modal.open) closeModal();
    });


    lucide.createIcons();
    

    document.getElementById('submitBtn').addEventListener('click', function(){
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('cancelBtn').disabled = true;
        document.getElementById('create_load').classList.remove('hidden');
        document.getElementById('loading_modal').showModal();
        submitProgram();
    });

    async function submitProgram(){
        try{
            const formData = new FormData(document.getElementById('programForm'));

            const response = await fetch('/create-program', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            });

            const result = await response.json();
            console.log(result)
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('cancelBtn').disabled = false;
            document.getElementById('create_load').classList.add('hidden');
            document.getElementById('loading_modal').close();
            if(result.status == 'success'){
                
                document.getElementById('programForm').reset();
                trainingModal.close();
                window.location.href = `/programs/${result.id}`;

            }
            else if(result.errors){
                if(result.errors.title.length > 0) document.getElementById('title_required').textContent = result.errors.title;
                if(result.errors.competency.length > 0) document.getElementById('competency_required').textContent = result.errors.competency;
                if(result.errors.modality.length > 0) document.getElementById('modality_required').textContent = result.errors.modality;
                if(result.errors.pax.length > 0) document.getElementById('pax_required').textContent = result.errors.pax;
                if(result.errors.category.length > 0) document.getElementById('category_required').textContent = result.errors.category;
                if(result.errors.type.length > 0) document.getElementById('type_required').textContent = result.errors.type;
                if(result.errors.initiated.length > 0) document.getElementById('initiated_required').textContent = result.errors.initiated;
                // if(result.errors.cost.length > 0) document.getElementById('cost_required').textContent = result.errors.cost;
                if(result.errors.fund.length > 0) document.getElementById('fund_required').textContent = result.errors.fund;
                if(result.errors.origin.length > 0) document.getElementById('origin_required').textContent = result.errors.origin;
            }
        } 
        catch (error){
            console.error('Request failed: ', error);
        }

    }


    document.getElementById('officeInitiated').addEventListener('change', function () {
        const provider = document.getElementById('trainingProvider');
        if (this.value === 'Other-Provider') {
            provider.value = '';
            provider.removeAttribute('readonly');
            provider.classList.remove('opacity-60');
            provider.focus();
        } else {
            provider.value = this.options[this.selectedIndex].text;
            provider.setAttribute('readonly', true);
            provider.classList.add('opacity-60');
        }
    });

    
</script>
