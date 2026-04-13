<div class="p-5 space-y-4">

    <div class="flex justify-between ">
        <input type="hidden" id="progCode" value="{{ $myprogram->program_code }}">

        <div>
            <h1 class="poppins-semibold text-slate-700 dark:text-violet-400">POST Training Requirements</h1>
            <p class="poppins-regular text-xs text-slate-400">Set the requirements participants need to complete after
                each training program, <br>helping ensure proper documentation and follow-through.</p>
        </div>

        <button onclick="showModalNewReq()" class="btn btn-default btn-sm rounded-lg bg-gradient-to-br from-violet-600 to-violet-800 text-white">
            <i class="fa-solid fa-plus"></i> New Requirement
        </button>


    </div>

    <div id="requirementsList" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
        <span class="loading loading-bars loading-md"></span>
    </div>

    


</div>

<script>
    function showModalNewReq(){
        reqModal.showModal()
        lucide.createIcons();
    }

    loadRequirements($('#progCode').val());

    function loadRequirements(programCode) {

        $.ajax({
            url: `/get-requirements/${programCode}`,
            method: 'GET',
            success: function(res) {
                document.getElementById('requirementsList').innerHTML =
                res.data.map(req => `
                    <div class="slide-up overflow-hidden border border-slate-300 dark:border-slate-600 dark:bg-slate-800 bg-white rounded-2xl mb-2">
                        <div class="p-4  flex justify-between">
                            <div>
                            <p class="poppins-bold">${req.title}</p>
                            <p class="text-xs poppins-regular text-slate-600 dark:text-slate-300">${req.description ?? ''}</p>
                            </div>
                            <button onclick="deleteRequirement(${req.id})" class="btn btn-circle btn-xs btn-error btn-soft"><i class="fa-regular fa-trash-can"></i></button>
                        </div>

                        <div class="">
                            ${(req.batches || []).map((batch, index) => `
                                <div class="py-2 px-5 text-sm poppins-regular border-t border-slate-300 dark:border-slate-700">
                                    <p><i class="fa-solid fa-layer-group text-violet-500"></i> ${batch.batch}</p>
                                    <p class="text-slate-500 text-sm"><i class="fa-regular fa-calendar"></i>Due Date: ${req.due_date?.[index] ?? 'N/A'}</p>
                                </div>
                            `).join('')}
                        </div>

                        
                    
                    </div>
                `).join('');

            }
        });
    }


    function deleteRequirement(id) {

        $.ajax({
            url: `/requirements/${id}/delete` ,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if(res.status){
                    loadRequirements($('#progCode').val());
                }console.log(res);
                
            }
        });

    }




    







</script>