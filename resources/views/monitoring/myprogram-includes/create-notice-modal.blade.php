<dialog id="create_notice_modal" class="modal">
  <div class="modal-box p-0 rounded-2xl">

    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>


   <div class="w-full max-w-xl rounded-2xl shadow-sm border overflow-hidden" style="background: #ffffff; border-color: #C8DDEF;">
    <div class="flex items-center gap-3 px-6 py-4" style="background: #D6E8F7; border-bottom: 1px solid #C8DDEF;">
     <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center" style="background: #3B82C4;"><i data-lucide="info" style="width:20px;height:20px;color:#fff;"></i>
     </div>
     <h1 id="heading" class="text-lg font-semibold" style="color: #1E3A5F;">Information</h1>
    </div>
    <div class="px-6 py-5 space-y-4" style="color: #334E68;">
     <p class="leading-relaxed">Programs with <strong>TESDA Orders</strong> are managed by the Central Office; therefore, you do not need to add this program.</p>
     <p class="leading-relaxed">Only programs that are <strong>regionally initiated</strong> may be added to the system by the HRMOs of the respective region.</p>
     <p class="leading-relaxed" style="color: #3B82C4; font-weight: 500;">Thank you.</p>
    </div>
   </div>



  </div>
</dialog>

<script>
  function yesNotice(){
    blank_modal.close();
    create_notice_modal.showModal();
  }
</script>