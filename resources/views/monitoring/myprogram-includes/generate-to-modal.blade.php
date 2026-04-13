<style>

#PreviewHere:empty::before {
  content: attr(data-placeholder); color: #94a3b8; pointer-events: none;
}
#PreviewHere ul, #PreviewHere ol { padding-left: 24px; margin: 8px 0; }
#PreviewHere ul li, #PreviewHere ol li { margin: 2px 0; }
</style>

<dialog id="TO_modal" class="modal">
  <div class="modal-box  max-w-[90vw] p-0 rounded-2xl !border-none" >


    <div class="px-5 py-3 border-b border-slate-300 dark:border-slate-600 bg-gradient-to-r from-indigo-600 to-indigo-800">
        <h3 class="poppins-semibold text-white"><i class="fa-regular fa-file-lines"></i> Generate TESDA Order</h3>
    </div>
    
    <input type="hidden" id="programCode">


    <div class="w-full px-5 py-2 grid grid-cols-1 lg:grid-cols-3 gap-4 items-start bg-white dark:bg-slate-800">

        {{-- HEADER --}}
        <div class="HEADER border rounded-2xl rounded-2xl border-slate-300 dark:border-slate-600 overflow-hidden shadow-lg">
            <div class="p-2 bg-white dark:bg-slate-600">
                <p class="poppins-regular text-[12px] text-slate-500 dark:text-slate-200"><i class="fa-solid fa-heading"></i> Header</p>
            </div>
            <div class="p-4 bg-slate-200 dark:bg-slate-700 space-y-4">

                <fieldset class="fieldset">
                    <legend class="fieldset-legend poppins-regular p-0">SUBJECT</legend>
                    <textarea id="subject" class="textarea text-justify h-24 w-full rounded-lg shadow-lg outline-none focus:border-none poppins-regular" placeholder="Enter Subject..."></textarea>
                </fieldset>

                <div class="grid gap-2 grid-cols-1 md:grid-cols-2">

                  <fieldset class="fieldset">
                    <legend class="fieldset-legend p-0 poppins-regular">DATE ISSUED</legend>
                    <input type="text" id="date_issued" class="input w-full rounded-lg poppins-regular outline-none focus:border-none shadow-lg" placeholder="" />
                  </fieldset>

                  <fieldset class="fieldset">
                    <legend class="fieldset-legend p-0 poppins-regular">EFFECTIVITY</legend>
                    <input type="text" id="effectivity" class="input w-full rounded-lg poppins-regular outline-none focus:border-none shadow-lg" placeholder="" />
                  </fieldset>

                </div>

                <div class="grid gap-2 grid-cols-1 md:grid-cols-2">

                  <fieldset class="fieldset">
                    <legend class="fieldset-legend p-0 poppins-regular">SERIES</legend>
                    <input type="number" id="series"  min="1900" max="2099" class="input w-full rounded-lg poppins-regular outline-none focus:border-none shadow-lg" placeholder="Enter here..." />
                  </fieldset>

                  <fieldset class="fieldset">
                    <legend class="fieldset-legend p-0 poppins-regular">SUPERSEDES</legend>
                    <input type="text" id="supersedes" class="input w-full rounded-lg poppins-regular outline-none focus:border-none shadow-lg" placeholder="" />
                  </fieldset>

                </div>


            </div>

        </div>

        {{-- 2ND SECTION AFTER HEADER --}}
        <div class=" space-y-4">

          {{-- BODY --}}
        <div class="BODY border rounded-2xl rounded-2xl border-slate-300 dark:border-slate-600 overflow-hidden shadow-lg ">
            <div class="p-2 bg-white dark:bg-slate-600">
                <p class="poppins-regular text-[12px] text-slate-500 dark:text-slate-200"><i class="fa-solid fa-b"></i> Body</p>
                
            </div>
            
            <div class="p-4 bg-slate-200 dark:bg-slate-700 ">

              <div onclick="edit_body_modal.showModal()" class="bg-slate-100 dark:bg-slate-600 p-2 rounded-2xl shadow-lg hover:bg-slate-100 cursor-pointer hover:scale-[1.05] duration-500 dark:hover:bg-slate-500">

                <div id="PreviewHere" class="line-clamp-4 bg-white dark:bg-slate-500 rounded-lg"></div>

              </div>

              <input type="hidden" name="body" id="body_input" >

                


            </div>

        </div>


        {{-- CLOSURE --}}
        <div class="CLOSURE border rounded-2xl rounded-2xl border-slate-300 dark:border-slate-600 overflow-hidden shadow-lg ">
            <div class="p-2 bg-white dark:bg-slate-600">
                <p class="poppins-regular text-[12px] text-slate-500 dark:text-slate-200"><i class="fa-solid fa-c"></i> Closure</p>
                
            </div>
            
            <div class="p-4 bg-slate-200 dark:bg-slate-700 space-y-4">

              <div onclick="edit_closure_modal.showModal()" class="bg-slate-100 dark:bg-slate-600 p-2 rounded-2xl shadow-lg hover:bg-slate-100 cursor-pointer hover:scale-[1.05] duration-500 dark:hover:bg-slate-500">

                <div id="closurePreviewHere" class="line-clamp-4 bg-white dark:bg-slate-500 rounded-lg"></div>

                

                

              </div>

              <input type="hidden" name="closure" id="closure_input" >

                


            </div>

        </div>


        </div>

        {{-- 3RD SECTION  --}}
        <div class="space-y-4">

          <div class="shadow-lg p-4 rounded-2xl bg-white dark:bg-slate-600 border border-slate-300 dark:border-slate-600 dark:bg-slate-700  text-center space-y-2">
            <h1 class="poppins-semibold text-sm text-slate-700 dark:text-white">Include the list of employees?</h1>
            <input type="hidden" name="with_employees" id="with_employees" value="0">
            <div class="flex gap-2">
              <button type="button" id="yesBtn" class="btn btn-sm rounded-lg flex-1" onclick="setEmp(1)">Yes</button>
              <button type="button" id="noBtn" class="bg-red-500 btn btn-sm rounded-lg flex-1" onclick="setEmp(0)">No</button>
            </div>
          </div>

          <div class="shadow-lg p-4 rounded-2xl bg-white dark:bg-slate-600 border border-slate-300 dark:border-slate-600 dark:bg-slate-700  text-center space-y-2">
            <h1 class="poppins-semibold text-sm text-slate-700 dark:text-white">Add Batch Description?</h1>
            <input type="hidden" name="with_batch" id="with_batch" value="0">
            <div class="flex gap-2">
              <button type="button" id="yesBtn_batch" class="btn btn-sm rounded-lg flex-1" onclick="setBatch(1)">Yes</button>
              <button type="button" id="noBtn_batch" class="bg-red-500 btn btn-sm rounded-lg flex-1" onclick="setBatch(0)">No</button>
            </div>
          </div>


          <div class="BODY border rounded-2xl rounded-2xl border-slate-300 dark:border-slate-600 overflow-hidden shadow-lg ">

            <div class="p-2 flex justify-between items-center bg-white dark:bg-slate-600">
                <p class="poppins-regular text-[14px] text-slate-500 dark:text-slate-200"><i class="fa-solid fa-signature"></i> Signatory</p>
                
              
                  <button onclick="openSignatoryModal()" class="btn btn-info bg-blue-500 shadow-none rounded-lg btn-sm text-white"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </div>


            
            
            <div class="p-4 bg-slate-200 dark:bg-slate-700">

              <div class=" space-y-2">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend p-0 poppins-regular">Employee Name</legend>
                    <input type="text" id="signatory_name" name="signatory_name" class="input w-full rounded-lg poppins-regular outline-none focus:border-none shadow-lg" placeholder="" required/>
                  </fieldset>

                  <fieldset class="fieldset">
                    <legend class="fieldset-legend p-0 poppins-regular">Employee Position</legend>
                    <input type="text" id="signatory_position" name="signatory_position" class="input w-full rounded-lg poppins-regular outline-none focus:border-none shadow-lg" placeholder="" required/>
                  </fieldset>
              </div>

            
            </div>

        </div>

        </div>

        

    </div>
    
    <div class="p-4 border-t border-slate-300 dark:border-slate-600 flex justify-between  bg-white dark:bg-slate-800">

      <div class="modal-action m-0">
      <form method="dialog">
        <!-- if there is a button, it will close the modal -->
        <button class="btn btn-sm rounded-lg"><i class="fa-solid fa-x"></i>Close</button>
      </form>
    </div>

     <button onclick="submitTESDAOrder()" class="btn btn-success btn-sm rounded-lg"><i class="fa-solid fa-check"></i> Confirm</button>

    </div>

    




  </div>
</dialog>



<script>


    function submitTESDAOrder(){
        loading_modal.showModal();
      

          const data = {
              program_code: document.getElementById('programCode').value,
              subject: document.getElementById('subject').value,
              series: document.getElementById('series').value,
              date_issued: document.getElementById('date_issued').value,
              effectivity: document.getElementById('effectivity').value,
              supersedes: document.getElementById('supersedes').value,
              body: document.getElementById('body_input').value,
              with_employees: document.getElementById('with_employees').value,
              with_batch: document.getElementById('with_batch').value,
              closure: document.getElementById('closure_input').value,
              signatory_name: document.getElementById('signatory_name').value,
              signatory_position: document.getElementById('signatory_position').value
          };

          fetch('/TESDAOrder/store', {
              method: 'POST',
              headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify(data)
          })
          .then(res => res.json())
          .then(res => {
              loading_modal.close();
              if(res.errors){
                console.log(res.errors);
              }else{
                TO_modal.close();
                window.open(`/tesda-order/${res.data.id}`, '_blank');
                window.location.reload();
              }
              
          })
          .catch(err => {
              loading_modal.close();
              // console.error('Error:', err);
          });
      
    }

    function setEmp(val) {
        document.getElementById('with_employees').value = val;

        if (val == 1) {
            yesBtn.classList.add('bg-green-500');
            noBtn.classList.remove('bg-red-500');
        } else {
            noBtn.classList.add('bg-red-500');
            yesBtn.classList.remove('bg-green-500');
        }
    }

    function setBatch(val) {
        document.getElementById('with_batch').value = val;

        if (val == 1) {
            yesBtn_batch.classList.add('bg-green-500');
            noBtn_batch.classList.remove('bg-red-500');
        } else {
            noBtn_batch.classList.add('bg-red-500');
            yesBtn_batch.classList.remove('bg-green-500');
        }
    }



    document.querySelectorAll('.open-to-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            const data = JSON.parse(this.dataset.program);
            showTOModal(data);
        });
    });

    function showTOModal(program){
      $('#programCode').val(program.program_code);
      const body = `In the interest of the service and in line with the Authority’s Staff Development Program, the following TESDA personnel are hereby authorized to attend the <b>${program.title}</b> to be conducted by ${program.provider}:`

      const closure = `They shall attend the program on official time. Four (4) days after the completion of the program, they are required to submit the following in accordance with Learning and Development Guidelines:<div><ul style="list-style-type: disc;"><li>A copy of all the learning materials to the TESDA Development Institute;</li><li>A photo copy of their certificates of attendance/participation;</li><li>A undefined on the knowledge/skills acquired.</li></ul><div>The registration fees shall be chargeable against the available fund of the Staff Development Program (SDP) subject to existing government accounting, budgeting, and auditing rules and regulations. This Order shall take effect as indicated</div></div>`

      $('#editor').html(body);
      $('#closure_editor').html(closure);
      $('#PreviewHere').html(body);
      $('#closure_input').val(closure);
      $('#closurePreviewHere').html(closure);
      $('#body_input').val(body);
      TO_modal.showModal();
    }

   

    document.getElementById('series').value = new Date().getFullYear();


    const TOModal = document.getElementById('TO_modal');

    // 🚫 Disable ESC key
  TOModal.addEventListener('cancel', function (e) {
    e.preventDefault();
  });

  // 🚫 Disable click outside
  TOModal.addEventListener('click', function (e) {
    const rect = TOModal.querySelector('.modal-box').getBoundingClientRect();
    const isInDialog =
      rect.top <= e.clientY &&
      e.clientY <= rect.top + rect.height &&
      rect.left <= e.clientX &&
      e.clientX <= rect.left + rect.width;

    if (!isInDialog) {
      e.preventDefault();
    }
  });


</script>


@include('monitoring.myprogram-includes.body-editor')
@include('monitoring.myprogram-includes.closure-editor')
@include('monitoring.myprogram-includes.search-signatory')