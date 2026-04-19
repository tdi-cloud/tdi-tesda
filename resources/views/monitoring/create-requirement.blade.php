<dialog id="reqModal" class="modal ">

     <!-- Backdrop -->
     <form method="dialog" class="modal-backdrop">
      <button aria-label="Close modal"></button>
     </form><!-- Modal Box Container -->


     <div class="modal-box w-11/12 max-w-lg rounded-2xl p-6">

      <!-- Header with gradient -->
      <div class="mb-6 pb-4 border-b border-gray-100">
       <h2 id="modalTitle" class="text-xl font-bold poppins-bold text-gray-900 dark:text-violet-300">New Requirement</h2>
       <p class="text-xs text-gray-500 dark:text-slate-200 mt-1 poppin-regular">Fill in the details below to create a new program requirement</p>
      </div>
      
      <!-- Form Content -->
      <form id="reqForm" class="space-y-4" method="POST" action="/create-requirement">
        @csrf
        <input type="hidden" name="program_code" value="{{ $myprogram->program_code }}">

       <!-- Title Selection -->
       <div class="form-control w-full">
        <label class="label pb-1 w-full flex justify-between"> <span class="label-text font-medium text-sm text-gray-700 poppins-regular dark:text-slate-200">Requirement Type</span> 
            <span class="text-xs font-medium text-purple-600">Required</span> 
        </label> 
        <select name="title" id="reqTitle" class="select select-bordered select-sm w-full bg-slate-200 dark:bg-slate-600 border-gray-300  text-sm" required> 
            <option selected value="TREAP">Terminal Report (TREAP)</option> 
            <option value="REAP">Terminal and Re-entry Action Plan Report (REAP)</option> 
            <option value="TDOR">Training Development Outcome Report (TDOR)</option> 
            <option value="AAR">After Activity Report</option> 
            <option value="Feedback Report">Feedback Report</option> 
            <option value="Benchmarking Report">Benchmarking Report</option> 
        
        </select>
       </div>

       <!-- Description -->
       <div class="form-control w-full">
        <label class="label pb-1 w-full flex justify-between"> 
            <span class="label-text font-medium text-sm text-gray-700 poppins-regular dark:text-slate-200">Description</span> 
            </label> 
            <textarea name="description" id="reqDesc" class="textarea textarea-bordered textarea-sm w-full h-20 bg-slate-200 dark:bg-slate-600 border-gray-100  resize-none text-sm" placeholder="Describe what this requirement entails..." ></textarea>
       </div>
       
       {{-- <!-- Date Due -->
       <div class="form-control w-full ">
        <label class="label pb-1 w-full flex justify-between"> 
            <span class="label-text font-medium text-sm text-gray-700 poppins-regular dark:text-slate-200">Due Date</span> 
        <span class="text-xs font-medium text-purple-600">Required</span> 
        </label> <input type="date" id="reqDate" class="input input-bordered input-sm w-full bg-slate-200 dark:bg-slate-600 border-gray-300 text-sm" required>
       </div> --}}
       
       <!-- Required Toggle -->
       <div class="form-control w-full">
        <label class="dark:bg-slate-700  w-full label cursor-pointer justify-start gap-3 bg-purple-50 px-3 py-2 rounded-lg border border-purple-100"> 
            <input name="required" type="checkbox" id="reqRequired" class="checkbox checkbox-sm checkbox-primary" checked>
         <div class="flex-1">
          <span class="label-text font-medium text-sm ">Mandatory Requirement</span>
          <p class="text-xs text-gray-600 dark:text-slate-200 mt-0.5" id="reqRequiredHint">Participants must complete this requirement</p>
         </div>
        
        
        </label>
       </div><!-- Action Buttons -->
       <div class="modal-action gap-2 pt-4 border-t border-gray-100 mt-6">
        <button type="button" onclick="document.getElementById('reqModal').close()" class="btn btn-ghost btn-sm flex-1 text-sm"> Cancel </button> <button type="submit" class="btn btn-sm btn-primary bg-gradient-to-r from-purple-600 to-violet-600 border-0 text-white flex-1 text-sm"> <i data-lucide="check" style="width:16px;height:16px;"></i> Save Requirement </button>
       </div>
      
    </form>
     </div>
</dialog>



<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    

    $('#reqForm').submit(function(e){
        e.preventDefault();
        try{
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
            
                success: function(res) {
                    
                    if(res.status){
                        reqModal.close();
                        showToast('Requirement Added', 'success');
                        loadRequirements($('#progCode').val());
                    }else console.log(res);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        // loop all errors
                        $.each(errors, function(field, messages){
                            $('#error-' + field).text(messages[0]);
                        });

                    } else {
                        showToast('Something went wrong!', 'error');
                    }
                }
            });
        } catch (err) {
            console.error(err);
            showToast('Unexpected error occurred!', 'error');
        }

    })
</script>