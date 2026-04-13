<style>
   
 
    .wrap {
      width: 100%;
      max-width: 600px;
    }
 
    /* Header */
    .bulk-title {
      font-size: 2rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      line-height: 1;
      margin-bottom: 0.3rem;
    }
 
    .bulk-sub {
      font-size: 0.82rem;
      color: var(--muted);
    }
 
    /* Card */
    .card {
      background: var(--surface);
      border: 1.5px solid var(--border);
      border-radius: var(--radius);
      padding: 2rem;
    }
 
    
 
    .hint {
      margin-top: 0.4rem;
      font-size: 0.7rem;
      color: var(--muted);
      font-family: var(--mono);
    }
 
    /* Preview chips */
    #preview {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      margin-top: 1rem;
    }
 
    .chip {
      background: var(--accent-light);
      border: 1px solid rgba(200,75,47,0.2);
      color: var(--accent);
      font-family: var(--mono);
      font-size: 0.7rem;
      padding: 3px 9px;
      border-radius: 3px;
    }
 
    .chip-more {
      background: var(--bg);
      border: 1px solid var(--border);
      color: var(--muted);
      font-family: var(--mono);
      font-size: 0.7rem;
      padding: 3px 9px;
      border-radius: 3px;
    }
 
 
    /* Submit button */
    #submit-btn {
      width: 100%;
      padding: 0.9rem 1.5rem;
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: var(--radius);
      font-family: var(--sans);
      font-size: 0.9rem;
      font-weight: 600;
      letter-spacing: 0.04em;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      transition: background 0.15s, transform 0.1s;
    }
 
    #submit-btn:hover:not(:disabled) { background: #a83a20; }
    #submit-btn:active:not(:disabled) { transform: scale(0.99); }
    #submit-btn:disabled { background: #d9c9c5; cursor: not-allowed; }
 
    .spinner {
      width: 15px;
      height: 15px;
      border: 2px solid rgba(255,255,255,0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
      display: none;
    }
 
    @keyframes spin { to { transform: rotate(360deg); } }
 
    /* Results */
    #results {
      margin-top: 1.5rem;
      display: none;
      flex-direction: column;
      gap: 0.6rem;
    }
 
    .result-box {
      padding: 0.9rem 1rem;
      border-radius: var(--radius);
      border: 1.5px solid;
      font-size: 0.82rem;
      line-height: 1.6;
      font-family: var(--mono);
      border-radius: 15px;
    }
 
    .result-box .r-tag {
      font-size: 0.65rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      display: block;
      margin-bottom: 4px;
      opacity: 0.65;
      
    }
 
    .result-box.success { background: rgba(42,122,75,0.06); border-color: rgba(42,122,75,0.3); color: var(--success); }
    .result-box.skipped { background: rgba(160,107,0,0.06); border-color: rgba(160,107,0,0.3); color: var(--warn); }
    .result-box.notfound { background: rgba(200,75,47,0.06); border-color: rgba(200,75,47,0.3); color: var(--danger); }
    .result-box.error    { background: rgba(200,75,47,0.06); border-color: rgba(200,75,47,0.3); color: var(--danger); }
  </style>


<dialog id="bulk_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box rounded-2xl">
    <h1 class="bulk-title">Bulk Add Participants</h1>
    <p class="bulk-sub poppins-regular">— Paste employee codes below to enrolled them to this batch</p>

    

    <form id="bulk_form">

      {{-- Hidden fields --}}
      <input type="hidden" name="batch_id"     value="" id="batchIdBulkInput">
      <input type="hidden" name="attendance"   value="Pending">
      <input type="hidden" name="hours"        value="0">
      <input type="hidden" name="added_by"     value="{{ auth()->user()->empcode }}">
      <input type="hidden" name="requirements" value="required">

  
    <label class="poppins-regular text-xs " for="empcodes">Employee Codes</label>
    <textarea 
    id="empcodes"
    name="empcodes"
    placeholder="Paste Employee IDs Here... i.e. 2026-1234" 
    class="textarea h-30 dark:bg-slate-700 poppins-regular textarea-info w-full rounded-2xl mt-4 bg-slate-100">
  </textarea>

  <p class="hint">Separate codes by newline · comma · semicolon · or space</p>
  <div id="preview"></div>

   </form>

 

    <div class="w-full flex gap-2 justify-end mt-4">
        <div class="modal-action m-0">
            <form method="dialog">
                <!-- if there is a button in form, it will close the modal -->
                <button onclick="clearTextbox()" class="btn rounded-lg">Cancel</button>
            </form>
        </div>

        <button id="bulk-submit-btn" class="btn rounded-lg bg-blue-600 text-white">Submit</button>
    </div>

    <div id="results"></div>


    <script>

      function clearTextbox(){
        $('#empcodes').val('');
      }
$(function () {
 
  var API_URL = '{{ route("api.participants.bulk-add") }}'; // update route name as needed
  var CSRF    = $('meta[name="csrf-token"]').attr('content');
 
  /* ── Parse codes from textarea ── */
  function parseCodes(raw) {
    var seen = {};
    var result = [];
    raw.split(/[\s,;\n]+/).forEach(function(c) {
      c = c.trim();
      if (c && !seen[c]) { seen[c] = true; result.push(c); }
    });
    return result;
  }
 
  /* ── Live preview as user types ── */
  $('#empcodes').on('input', function () {
    var codes   = parseCodes($(this).val());
    var $preview = $('#preview').empty();
 
    if (!codes.length) {
      $('#bulk-submit-btn').prop('disabled', true);
      return;
    }
 
    $('#bulk-submit-btn').prop('disabled', false);
 
    var visible = codes.slice(0, 15);
    $.each(visible, function(i, c) {
      $preview.append('<span class="chip">' + c + '</span>');
    });
 
    if (codes.length > 15) {
      $preview.append('<span class="chip-more">+' + (codes.length - 15) + ' more</span>');
    }
  });
 
  /* ── Submit via AJAX (button is outside the form) ── */
  $('#bulk-submit-btn').on('click', function () {
    var formEl  = document.getElementById('bulk_form');
    var formData = new FormData(formEl);
 
    if (!$.trim($('#empcodes').val())) return;
 
    setLoading(true);
    $('#results').hide().empty();
 
    $.ajax({
      url:         API_URL,
      method:      'POST',
      data:        formData,
      processData: false,   // must be false for FormData
      contentType: false,   // must be false for FormData
      headers:     { 'X-CSRF-TOKEN': CSRF },
      success: function (res) {
        renderResults(res);
      },
      error: function (xhr) {
        var msg = 'Something went wrong. Please try again.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = xhr.responseJSON.message;
        }
        renderResults({ error: msg });
      },
      complete: function () {
        setLoading(false);
      }
    });
  });
 
  /* ── UI helpers ── */
  function setLoading(on) {
    $('#bulk-submit-btn').prop('disabled', on);
    $('#spinner').css('display', on ? 'block' : 'none');
    $('#bulk-submit-btn').text(on ? 'Adding participants…' : 'Submit');
  }
 
  function renderResults(res) {
    fetchBatches();
    var $r = $('#results').css('display', 'flex');
 
    if (res.error) {
      $r.append(box('error',    '✕ Error',     res.error));
      return;
    }
    if (res.success)  $r.append(box('success',  '✓ Added',     res.success));
    if (res.skipped)  $r.append(box('skipped',  '⚠ Skipped',   res.skipped));
    if (res.notfound) $r.append(box('notfound', '✕ Not Found', res.notfound));

    setTimeout(() => {
      bulk_modal.close();
      var $r = $('#results').css('display', 'none');
    }, 3000);
  }
 
  function box(type, tag, msg) {
    
    return '<div class="result-box  ' + type + '">'
         +   '<span class="r-tag">' + tag + '</span>'
         +   msg
         + '</div>';
  }
 
});
</script>

   

    
  </div>
</dialog>