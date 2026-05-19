<x-layout>
<x-slot:title>View Program</x-slot:title>
<x-monitoring-layout>
    @include('components.loading')
   

    <div class="flex p-5 border-b border-slate-300 dark:border-slate-600 items-center justify-between bg-white dark:bg-slate-800 ">
        <div>
            <a href="/programs" class="poppins-regular text-blue-500 text-[13px]"><i class="fa-solid fa-arrow-left-long"></i> Back to programs</a>
            <h1 class="text-lg poppins-bold text-slate-900 dark:text-yellow-400">{{ $myprogram->title }}</h1>
            <p class="leading-5 poppins-regular text-slate-500 text-[13px]">{{ $myprogram->description }}</p>
        </div>

         {{-- <button onclick="createBatchModal()" class="btn btn-info bg-blue-600 text-white rounded-lg shadow-none poppins-semibold"><i class="fa-solid fa-plus"></i>Add Batch</button> --}}

    </div>

    <div class="TABS flex px-5 py-2 border-b border-slate-300 bg-slate-200 dark:bg-slate-800 dark:border-slate-600 gap-2">
        <a href="/programs/{{ $myprogram->id }}" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-circle-info"></i> Details</a>

        <a href="/programs/{{ $myprogram->id }}/participants" class="btn-ghost  dark:bg-slate-600 hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-user-group"></i> Participants</a>

        <a href="/programs/{{ $myprogram->id }}/submissions" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-regular fa-file"></i> Submissions</a>      

        <a href="/programs/{{ $myprogram->id }}/requirements" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-regular fa-file"></i> Requirements</a> 
        
        @if($myprogram->tesdaOrders->isNotEmpty())
        <a href="/programs/{{ $myprogram->id }}/tesda-order" class="btn-default bg-white hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-file-lines text-indigo-600"></i> TESDA Order</a> 
        @endif

    </div>

    <div class=" flex-1  overflow-auto ">
    
        <div class="p-4">
            <h1 class="poppins-semibold">Generated TESDA Order/s</h1>
        </div>

        <div class="px-4">
            <div id="tesdaContainer"></div>
        </div>

        
    

    </div>

    
    
    <script>
        let programCode = '{{ $myprogram->program_code }}'; // or from button/data attribute

        getOrders();

        function getOrders(){

            $.ajax({
            url: `/tesda-orders/${programCode}`,
            type: 'GET',
            success: function (response) {

                let html = '';

                response.forEach(order => {

                    let scope = '';

                    if (order.with_employees && order.with_batch) {
                        scope = 'employees and batch';
                    } else if (order.with_employees) {
                        scope = 'employees only';
                    } else if (order.with_batch) {
                        scope = 'batch only';
                    } else {
                        scope = 'general order';
                    }

                    html += `
                        <div  class="hover:scale-[1.01] duration-500 shadow-lg hover:shadow-2xl mb-3 p-3 border rounded poppins-regular border-slate-300 text-sm rounded-2xl bg-white dark:bg-slate-800 dark:border-slate-600 flex gap-2 justify-between">

                            <div onclick="viewTO(${order.id})" class=" flex  gap-2">
                                
                            <div class="w-10 h-10 flex justify-center items-center bg-indigo-300/50 rounded-2xl">
                                <i class="fa-solid fa-file-lines text-indigo-600 dark:text-indigo-200 text-lg"></i>
                            </div>

                            <p class="max-w-100 md:max-w-200">
                                Subject: <strong>"${order.subject}"</strong>
                                to be signed by <strong>${order.signatory_name}</strong>
                                (${order.signatory_position}).

                                It applies to <strong>${scope}</strong>.

                                ${order.date_issued != ' ' ? `<br><em>Issued on ${order.date_issued}</em>.` : ''}

                                ${order.supersedes != ' ' ? `<br><em>This order supersedes ${order.supersedes}</em>` : ''}
                            </p>

                            </div>

                            <div>
                                <button onclick="deleteOrder(${order.id})" class="btn btn-sm btn-error btn-soft"><i class="fa-solid fa-trash-can"></i></button>
                            </div>


                        </div>
                    `;
                });

                document.getElementById('tesdaContainer').innerHTML = html;
            }
        });

        }

        

        function deleteOrder(id) {
            if (!confirm("Are you sure you want to delete this TESDA Order?")) return;

            $.ajax({
                url: '/tesda-orders/delete/' + id,
                type: 'GET',
                success: function (response) {
                    
                    if(response.status == 'success'){
                        showToast(response.message,'success');
                        getOrders();
                        
                    }
                    console.log(response);

                }
            });
        }

        function viewTO(id){
            window.open(`/tesda-order/${id}`, "_blank");
        }
    </script>

    
   
    

</x-monitoring-layout>
</x-layout>