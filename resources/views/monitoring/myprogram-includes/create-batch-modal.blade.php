<dialog id="create_batch_modal" class="modal">
    <div class="modal-box p-0 rounded-2xl">

        <div class="w-full bg-gradient-to-r from-blue-700 to-blue-900 p-5 flex items-center gap-2">

            <div class="w-10 h-10 flex items-center justify-center bg-white/20 rounded-lg">
                <i class="fa-solid fa-layer-group text-white text-lg"></i>
            </div>

            <div>
                <h1 id="createBatchTitle" class="text-white poppins-bold text-lg leading-5">Add new Batch</h1>
                <p class="poppins-medium text-sm text-slate-200 leading-4">Program Session</p>
            </div>

        </div>

        <div class="px-5 py-2">
          
            <form id="batchForm">
              @csrf

              <input type="hidden" id="idForEditBatch">

              <input type="hidden" name="program_code" value="{{ $myprogram->program_code }}" >
             
                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">Batch name</legend>
                    <input type="text"
                        name="batch"
                        id="batch_name"
                        class="input w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular"
                        placeholder="Enter Batch name..." />
                </fieldset>

       
              <div class="grid grid-cols-1 md:grid-cols-2 gap-2"> 
                
                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">Status</legend>
                    <select
                    name="status"
                    id="batch_status"
                    class="select w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular">
                        <option value="Active">Active</option>
                        <option value="Completed">Completed</option>
                        <option value="Upcoming">Upcoming</option>
                        <option value="Rescheduled">Rescheduled</option>
                        
                    </select>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">Modality</legend>
                    <select 
                    name="modality"
                    id="batch_modality"
                    class="select w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular">
                        <option value="In-person">In-person</option>
                        <option value="Online">Online</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Self paced">Self paced</option>
                    </select>
                </fieldset>

              </div>


                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">Venue</legend>
                    <input 
                    name="venue"
                    id="batch_venue"
                    type="text"
                        class="input w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular"
                        placeholder="Enter venue..." />
                </fieldset>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2"> 

                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">Start Date</legend>
                    <input type="date"
                    name="date_start"
                    id="batch_date_start"
                        class="input w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular"
                         />
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">End Date</legend>
                    <input type="date"
                    name="date_end"
                    id="batch_date_end"
                        class="input w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular"
                         />
                </fieldset>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2"> 

                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">Start Time</legend>
                    <input type="time"
                    name="time_start"
                    id="batch_time_start"
                        value="08:00"
                        class="input w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular"
                         />
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">End Time</legend>
                    <input type="time"
                        value="17:00"
                        name="time_end"
                        id="batch_time_end"
                        class="input w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular"
                         />
                </fieldset>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2"> 

                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">Number of Days</legend>
                    <input type="number"
                    name="days"
                        value="0"
                        id="batch_days"
                        class="input w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular"
                        placeholder="Enter venue..." />
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-semibold">Target Hours</legend>
                    <input type="number"
                    name="hours"
                        value="8"
                        id="batch_hours"
                        class="input w-full bg-slate-200 dark:bg-slate-700 outline-none border-none rounded-lg poppins-regular"
                         />
                </fieldset>
                

                </div>

               

            </form>

        </div>

        <div class="flex gap-2 p-5 justify-end">

          <div class="modal-action m-0">
            <form method="dialog">
                <!-- if there is a button in form, it will close the modal -->
                <button class="btn rounded-lg poppins-semibold">Cancel</button>
            </form>
        </div>

        <button id="batch_submit_btn" class="btn btn-info bg-blue-600 text-white rounded-lg poppins-semibold"><i class="fa-regular fa-save"></i> Save Batch <span id="batch_load" class="hidden loading loading-dots loading-sm"></span></button>
        <button id="batch_edit_btn" class="hidden btn btn-info bg-blue-600 text-white rounded-lg poppins-semibold"><i class="fa-solid fa-pen"></i> Save Batch <span id="batch_load" class="hidden loading loading-dots loading-sm"></span></button>


        </div>

        
    </div>
</dialog>


<script>

 document.getElementById('batch_submit_btn').addEventListener('click', function(){
    document.getElementById('batch_submit_btn').disabled = true;
    document.getElementById('batch_load').classList.remove('hidden');
    loading_modal.showModal();
    submitBatchForm();
 }) 

async function submitBatchForm() {
    try {
        const formData = new FormData(document.getElementById('batchForm'));

        const response = await fetch('/create-batches', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData, // stringify the plain object
        });

        const result = await response.json();
        // console.log(result);
        
        document.getElementById('batch_submit_btn').disabled = false;
        document.getElementById('batch_load').classList.add('hidden');
        loading_modal.close();
        if(result.status === 'success'){
            create_batch_modal.close();
            document.getElementById('batchForm').reset();
            fetchBatches();
            showToast('Batch added successfully', type = 'success')
        }else console.log(result);


    } catch (error) {
        console.error('Request failed: ', error);
    }
}

$('#batch_edit_btn').on('click', function(){
    $('#batch_edit_btn').prop('disabled', true);
    submitEditBatchForm();
    
});

async function submitEditBatchForm() {
    try {
        const formData = new FormData(document.getElementById('batchForm'));


        const response = await fetch(`/batch/${$('#idForEditBatch').val()}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData, // stringify the plain object
        });

        const result = await response.json();
        $('#batch_edit_btn').prop('disabled', false);
        if(result.success){
            document.getElementById('batchForm').reset();
            create_batch_modal.close();
            fetchBatches();
            showToast('Batch Updated!', 'success')
        }else console.log(result);
        


    } catch (error) {
        console.error('Request failed: ', error);
    }
}




</script>
