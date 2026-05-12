<x-layout>
<x-slot:title>View Program</x-slot:title>
<x-monitoring-layout>
    @include('components.loading')
   

    <div class="flex gap-4 p-5 border-b border-slate-300 dark:border-slate-600 items-center justify-between bg-white dark:bg-slate-800 ">
        <div class="flex-1">
            <a href="/programs" class="poppins-regular text-blue-500 text-[13px]"><i class="fa-solid fa-arrow-left-long"></i> Back to programs</a>
            <h1 class="text-lg poppins-bold text-slate-900 dark:text-yellow-400">{{ $myprogram->title }}</h1>
            <p class="leading-5 poppins-regular text-slate-500 text-[13px]">{{ $myprogram->description }}</p>
        </div>

        <div class="flex gap-2 ">
            <div class="dropdown dropdown-end poppins-regular hidden">
            <div tabindex="0" role="button" class="btn btn-default shadow-xl rounded-box">Generate <i class="fa-solid fa-angle-down"></i></div>
            <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                <li  data-program='@json($myprogram)' class="open-to-modal  poppins-medium"><a><i class="fa-solid fa-file-lines text-indigo-600"></i> TESDA Order</a></li>
            </ul>
            </div>


            <button onclick="createBatchModal()" class="hidden btn btn-info bg-blue-600 text-white rounded-lg shadow-xl poppins-semibold"><i class="fa-solid fa-plus"></i>Add Batch</button>
        </div>

         

    </div>

    <div class="TABS flex px-5 py-2 border-b border-slate-300 bg-slate-200 dark:bg-slate-800 dark:border-slate-600 gap-2">
        <a href="/programs/{{ $myprogram->id }}" class="btn-ghost dark:bg-slate-600 hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-circle-info"></i> Details</a>

        <a href="/programs/{{ $myprogram->id }}/participants" class="btn-ghost  hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-user-group"></i> Participants</a>

        <a href="/programs/{{ $myprogram->id }}/submissions" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-regular fa-file"></i> Submissions</a>      

        <a href="/programs/{{ $myprogram->id }}/requirements" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-regular fa-file"></i> Requirements</a>  

        <a href="/programs/{{ $myprogram->id }}/certificate" class="btn-default bg-white dark:bg-slate-600 hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-award"></i> Certificate</a>
        
        @if($myprogram->tesdaOrders->isNotEmpty())
        <a href="/programs/{{ $myprogram->id }}/tesda-order" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-file-lines text-indigo-600"></i> TESDA Order</a> 
        @endif

    </div>

    <div class=" flex-1  overflow-auto ">

        <div class="px-5 py-4 flex justify-between">
            <div class="flex gap-2 items-center">
                <div class="flex w-12 h-12 justify-center items-center text-indigo-600 bg-indigo-200 rounded-lg text-2xl">
                    <i class="fa-solid fa-award"></i>
                </div>

                    <div>
                    <h1 class="poppins-semibold text-lg">Certificates</h1>
                    <p class="poppins-regular text-xs text-slate-400 dark:text-slate-200">Issue and manage training completion certificates</p>
                </div>
            </div>

 
        </div>

        <div>
            <form action="/certificate/setup/store"
                method="POST"
                enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="batch_id" value="{{ $batch->id }}">

            <h3>Template</h3>
            <input type="text" name="template_name">

            <h3>Background</h3>
            <input type="file" name="background">

            <h3>Content Editor</h3>
            <textarea name="content" id="editor"></textarea>

            <h3>Signatory</h3>

            <select id="employee_select">
            @foreach($employees as $e)
            <option
                value="{{ $e->EMPCODE }}"
                data-name="{{ $e->FIRSTNAME }} {{ $e->LASTNAME }}"
                data-position="{{ $e->POSITION }}">
                {{ $e->FIRSTNAME }} {{ $e->LASTNAME }}
            </option>
            @endforeach
            </select>

            <input type="text" name="signatory_name" id="signatory_name">
            <input type="text" name="signatory_position" id="signatory_position">

            <h3>Signature Image</h3>
            <input type="file" name="signature_image">

            <button type="submit">Save Setup</button>

            </form>
        </div>


        <button id="bulkGenerate">Generate Certificates</button>

        
  
    
    </div>

    <script>
        $('#employee_select').on('change', function () {

            let opt = $(this).find(':selected');

            $('#signatory_name').val(opt.data('name'));
            $('#signatory_position').val(opt.data('position'));

        }); 

        $('#bulkGenerate').click(function(){

            $.post('/certificate/bulk',{
                batch_id:1,
                _token:'{{ csrf_token() }}'
            },function(res){
                alert(res.count + " generated");
            });

        });
    </script>
  


</x-monitoring-layout>
</x-layout>