<dialog id="delete_batch_modal" class="modal">
  <div class="modal-box rounded-2xl p-8 transform transition-all animate-[fadeIn_0.2s_ease-out] max-w-md w-full">
     <!-- Icon -->
     <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-5"><i data-lucide="alert-triangle" style="width:32px;height:32px;color:#ef4444;"></i>
     </div><!-- Title -->
     <h2 id="modalTitle" class="text-xl font-bold text-slate-800 text-center mb-2 dark:text-white">Delete Batch Confirmation</h2><!-- Message -->
     <p id="modalMessage" class="text-slate-500 dark:text-slate-200 text-center mb-8">Do you want to delete this Batch?</p><!-- Buttons -->

     <input type="hidden" id="delete_batch_id">
     <div class="flex gap-3">
        
        <button onclick="delete_batch_modal.close()" class="flex-1 py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-all"> No </button> 

        <button onclick="deleteBatch()" id="yes_delete_batch_btn" class="flex-1 py-3 px-4 rounded-xl bg-red-500 text-white font-semibold hover:bg-red-600 transition-all"> Yes </button>
     </div>

  </div>
</dialog>
