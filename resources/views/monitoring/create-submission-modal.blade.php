<dialog id="submissionModal" class="modal">
  <div class="modal-box w-full max-w-lg bg-white dark:bg-slate-800 rounded-xl p-5">

    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>

    <h3 class="text-lg font-bold mb-4">Submission</h3>

    <input type="hidden" id="submission_participant_id">
    <input type="hidden" id="submission_batch_id">
    <input type="hidden" id="programCode" value="{{ $myprogram->program_code }}">

    <div class="space-y-3">

    <label class="text-sm">Requirement</label>
    <select id="requirement_select" class="select select-bordered w-full">
        <option>Loading...</option>
    </select>

    <label class="text-sm mt-3">Upload File</label>
    <input type="file" id="submission_file" class="file-input file-input-bordered w-full">

    <button class="btn btn-primary w-full mt-3" onclick="saveSubmission()">
        Save Submission
    </button>

    </div>



    

  </div>
</dialog>

<script>

    function openSubmissionModal(participantId, batchId) {

        $('#submission_participant_id').val(participantId);
        $('#submission_batch_id').val(batchId);

        $('#requirement_select').html('<option>Loading...</option>');

        fetch(`/participants/${participantId}/available-requirements`)
            .then(res => res.json())
            .then(data => {
                const select = $('#requirement_select');
                select.empty();

                if (data.data.length === 0) {
                    select.append(`<option value="">No available requirements</option>`);
                    return;
                }

                data.data.forEach(req => {
                    select.append(`
                        <option value="${req.id}">
                            ${req.title}
                        </option>
                    `);
                });
            });

        document.getElementById('submissionModal').showModal();
    }

    function saveSubmission() {
        const participantId = $('#submission_participant_id').val();
        const batchId = $('#submission_batch_id').val();
        const program = $('#programCode').val();
        const requirementId = $('#requirement_select').val();
        const file = $('#submission_file')[0].files[0];

        if (!requirementId || !file) {
            showToast('Select requirement and file', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('participant_id', participantId);
        formData.append('batch_id', batchId);
        formData.append('requirement_id', requirementId);
        formData.append('file', file);
        formData.append('program_code', program);

        fetch('/submissions/admin/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async (res) => {
            const data = await res.json();

            // ❌ Handle Laravel errors (500, 422, 409, etc.)
            if (!res.ok) {
                throw data;
            }

            return data;
        })
        .then(data => {
            if (data.success) {
                showToast('Saved!', 'success');
                document.getElementById('submissionModal').close();
                fetchBatches();
            } else {
                showToast(data.message || 'Failed to save', 'error');
            }
        })
        .catch(error => {
            // 🔥 THIS is where Laravel error message appears
            console.error('AJAX Error:', error);

            showToast(
                error.message || 'Server error occurred',
                'error'
            );
        });
    }
</script>
