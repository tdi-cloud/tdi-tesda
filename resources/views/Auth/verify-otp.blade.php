<x-layout>

@if(session('success'))
        <div class="toast toast-top toast-center">

            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

  
    
    <main class="h-[100vh] w-full flex items-center justify-center p-4 overflow-auto">
   <div class="w-full max-w-md">
    <div id="card" class="bg-white rounded-2xl shadow-lg shadow-stone-200/60 p-8 sm:p-10 text-center"><!-- Icon -->
     <div id="icon-wrap" class="mx-auto w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center mb-6"><i data-lucide="mail" style="width:28px;height:28px;color:#1d4ed8;"></i>
     </div>
     <h1 id="heading" class="text-2xl poppins-bold text-stone-900 mb-2" >Verify Your Email</h1>
     <p id="description" class="text-stone-500 text-sm mb-8 poppins-regular">Enter the OTP sent to your email</p>

     <form id="otp-form" method='POST' action="{{ route('otp.verify') }}" class="space-y-5">
        @csrf
      <div class="text-left">
        <label for="otp" class="block text-xs font-semibold text-stone-600 mb-1.5 uppercase tracking-wide">One-Time Password</label> 
        
        <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" placeholder="• • • • • •" autocomplete="one-time-code" class="otp-input w-full text-center text-2xl tracking-[0.4em] font-semibold border-2 border-stone-200 rounded-xl px-4 py-3.5 text-stone-900 placeholder:text-stone-300 transition-all duration-200 outline-none">
      </div>

    
      
      
      <button id="verify-btn" type="submit" class="verify-btn w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm py-3.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2"> <i data-lucide="shield-check" style="width:18px;height:18px;"></i> <span id="btn-text">Verify OTP</span> </button>


    </form>
     
     <!-- Success message (hidden) -->
     <div id="success-msg" class="hidden mt-5 text-emerald-600 text-sm font-medium flex items-center justify-center gap-1.5"><i data-lucide="check-circle" style="width:16px;height:16px;"></i> <span>OTP verified successfully!</span>
     </div>
     
     
     <!-- Error message (hidden) -->
     @error('otp')
     <div id="error-msg" class="ERROR mt-5 text-red-500 text-sm font-medium flex items-center justify-center gap-1.5"><i data-lucide="alert-circle" style="width:16px;height:16px;"></i> 
        <span>{{ $message }}</span>
     </div>
      @enderror



    </div>
   </div>
  </main>

<script>
    lucide.createIcons();

    // Form handling
    // document.getElementById('otp-form').addEventListener('submit', function() {
      
    //   const val = document.getElementById('otp').value.trim();
    
    // });

    // Allow only digits in OTP
    // document.getElementById('otp').addEventListener('input', function() {
    //   this.value = this.value.replace(/\D/g, '');
    // });
  </script>








 
</x-layout>