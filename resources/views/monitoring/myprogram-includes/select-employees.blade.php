<dialog id="select_emp_modal" class="modal">
  <div class="modal-box p-0 rounded-2xl">

    <div class="p-4 bg-gradient-to-r from-indigo-600 to-indigo-800 flex justify-between items-center">
        <h1 class="poppins-semibold text-white">Add Participants</h1>

        <button onclick="bulkAdd()" class="btn btn-sm shadow-none bg-white/20 border-none rounded-2xl text-white"><i class="fa-regular fa-square-plus"></i> Bulk Add</button>
    </div>

    <input type="hidden" id="batchId" >


    <div class="p-4 " id="selectParent">
        <label for="employeesSelect" class="text-sm poppins-regular ">Search & Select Employees</label>
        <select class="form-control w-full p-2" id="employeesSelect"  name="empcodes[]" multiple></select>
    </div>



    <div class="flex justify-end gap-2 p-4">

        <div class="modal-action m-0">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn btn-md shadow-none"><i class="fa-solid fa-x"></i> Close</button>
      </form>
    </div>

    <button onclick="saveParticipants()" class="btn btn-md btn-success text-white bg-green-500 shadow-none shadow-none"><i class="fa-solid fa-check"></i> Confirm</button>

    </div>

    



  </div>
</dialog>


<script>
    const selectmodal = document.getElementById('select_emp_modal');


    function bulkAdd(id){
        addParticipant($('#batchId').val());
    }

    function openEmployeeModal() {
        selectmodal.showModal();

        // Initialize only once
        if (!$('#employeesSelect').hasClass("select2-hidden-accessible")) {
            $('#employeesSelect').select2({
                dropdownParent: $('#select_emp_modal'),
                placeholder: 'Search employee...',
                minimumInputLength: 2,
                width: '100%',
                ajax: {
                    url: '/employees/search',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(emp => ({
                                id: emp.EMPCODE,
                                text: emp.EMPCODE + ' - ' + emp.FIRSTNAME
                            }))
                        };
                    }
                }
            });
        }
    }


    function saveParticipants() {
        let empcodes = $('#employeesSelect').val(); // array of selected values
        let batch_id = $('#batchId').val(); // make sure you have this input

        if (!empcodes || empcodes.length === 0) {
            alert('Please select at least one employee');
            return;
        }

        $.ajax({
            url: '/participants/store',
            type: 'POST',
            data: {
                empcodes: empcodes,
                batch_id: batch_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                fetchBatches();
                alert(response.message);
                $('#employeesSelect').val(null).trigger('change');
                selectmodal.close();

            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }
</script>