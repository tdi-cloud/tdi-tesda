<x-layout>
    <x-slot:title>
        Register
    </x-slot:title>

    @error('email')
        <div class="toast toast-top toast-center">

        <div class="alert alert-warning">
            <span><i class="fa-regular fa-circle-xmark"></i> {{ $message }}</span>
        </div>
    </div>
    @enderror
  
    <div class="w-full  h-screen flex justify-center items-center">

        <div class="card w-100 rounded-2xl p-5 border border-slate-300/50 bg-white">
            <div class="w-full  mb-4 flex items-center gap-2">
                <img class="w-15 overflow-hidden rounded-lg" src="{{ asset('images/TDI.png') }}" alt="">

                <div>
                    <h1 class="poppins-bold text-2xl text-sky-900">Sign Up</h1>
                    <p class="text-sm poppins-regular text-slate-500">Create your account</p>
                </div>
                
                
            </div>

            <div>
                <form id="registerForm" method="POST" action="{{ route('register.sendOtp') }}">
                  @csrf
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-semibold">Username
                            @error('username')
                            <span class="text-red-500 text-xs">• {{ $message }}</span>
                            @enderror
                        </legend>
                        <input type="text"
                            name="username"
                            value="{{ old('username') }}"
                            class="input poppins-medium border @error('username') border-red-500 @enderror text-[12px] w-full outline-none rounded-lg bg-slate-100 focus:border-blue-500"
                            onkeydown="return event.key !== ' '"
                            onpaste="let p=event.clipboardData.getData('text');if(p.includes(' ')){event.preventDefault();this.value+=p.replace(/ /g,'')}"
                            placeholder="Enter Username" required/>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-semibold">Employee Code 
                            @error('empcode')
                            <span class="text-red-500 text-xs">• {{ $message }}</span>
                            @enderror
                        </legend>
                        <input type="text"
                            name="empcode"
                            id="empcode"
                            class="input @error('empcode') border-red-500 @enderror poppins-medium  text-[12px] w-full outline-none rounded-lg bg-slate-100 focus:border-blue-500"
                            onkeydown="return event.key !== ' '"
                            onpaste="let p=event.clipboardData.getData('text');if(p.includes(' ')){event.preventDefault();this.value+=p.replace(/ /g,'')}"
                            placeholder="Enter employee code e.g. 2026-1234" value="{{ old('empcode')}}" required/>
                        
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-semibold">Password
                        @error('password')
                            <span class="text-red-500 text-xs">• {{ $message }}</span>
                         @enderror

                        </legend>
                       
                        <div class="flex items-center border border-slate-300 @error('password') !border-red-500 @enderror bg-slate-100 rounded-lg py-1 px-3">
                            <input type="password" 
                            name="password"
                            id="inputPass"
                            value="{{ old('password')}}"
                            class=" poppins-medium  border-none text-[12px] w-full outline-none "
                            placeholder="Enter your password" required/>
                            <button 
                            type="button" 
                            onclick="togglePass()" 
                            class="btn btn-sm btn-ghost btn-circle">
                                <i id="showPass" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-semibold">Confirm Password</legend>
                        <div class="flex items-center border border-slate-300 @error('password_confirmation') !border-red-500 @enderror bg-slate-100 rounded-lg py-1 px-3">
                            <input 
                            id="inputConPass"
                            type="password" 
                            name="password_confirmation"
                            value="{{ old('password_confirmation')}}"
                            class="poppins-medium  border-none text-[12px] w-full outline-none  "
                            placeholder="Confirm Password"  required/>
                            <button 
                            onclick="confirmtogglePass()"
                            type="button" 
                            class="btn btn-sm btn-ghost btn-circle">
                            <i 
                            id="showConPass"
                            class="fa-regular fa-eye"></i>
                        </button>
                        </div>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-semibold">Email Address</legend>
                        <input 
                        type="email" 
                        name="email"
                        value="{{ old('email') }}"
                            class="input poppins-medium border @error('email') border-red-500 @enderror text-[12px] w-full outline-none rounded-lg bg-slate-100 focus:border-blue-500"
                            placeholder="Enter your email address" required/>
                        
                    </fieldset>

                    <div class="w-full flex justify-between items-center mt-4">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 underline">Log In</a>
                      <button type='button' id="checkEmpcodeBtn" class="btn btn-neutral btn-md rounded-lg">
                        Continue
                        <i class="fa-solid fa-angle-right"></i>
                      </button>
                    </div>
                </form>


            </div>


        </div>

    </div>

{{-- DaisyUI Modal --}}
<dialog id="empcode_modal" class="modal">
    <div class="modal-box rounded-2xl">
        <div class="flex flex-col items-center">
            <img
            class="w-40 "
            src="https://img.freepik.com/premium-vector/two-men-discussing-solutions-near-large-question-mark_932695-5584.jpg?semt=ais_hybrid&w=740&q=80" alt="">
            <h3 class="font-bold text-lg mb-4 poppins-bold">Confirm Your Identity</h3>
        </div>
        

        {{-- Employee Info --}}
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="font-semibold">Employee Code:</span>
                <span id="modal_empcode"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Name:</span>
                <span id="modal_name"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Position:</span>
                <span id="modal_office"></span>
            </div>
        </div>

        <p class="text-sm text-gray-500 mt-4 poppins-medium">
            Is this your information? Click <strong>Confirm</strong> to proceed.
        </p>

        <div class="modal-action">
            {{-- Cancel --}}
            <button class="btn" onclick="empcode_modal.close()">Cancel</button>

            {{-- Continue — submits the actual form --}}
            <button id="confirmBtn" class="btn btn-primary">Confirm</button>
        </div>
    </div>
</dialog>

{{-- Error Modal --}}
<dialog id="error_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg text-error">Employee Not Found</h3>
        <p id="error_message" class="text-sm mt-2"></p>
        <div class="modal-action">
            <button class="btn" onclick="error_modal.close()">Close</button>
        </div>
    </div>
</dialog>

    <script>
        function togglePass(){
            const input = document.getElementById('inputPass');
            const icon = document.getElementById('showPass');

            if(input.type === 'password'){
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }else{
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function confirmtogglePass(){
            const input = document.getElementById('inputConPass');
            const icon = document.getElementById('showConPass');

            if(input.type === 'password'){
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }else{
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
        


        document.getElementById('checkEmpcodeBtn').addEventListener('click', async function () {
        const empcode = document.getElementById('empcode').value;

        if (!empcode) {
            alert('Please enter your Employee Code.');
            return;
        }

        try {
            const response = await fetch('{{ route("register.checkEmpcode") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ empcode: empcode })
            });

            const data = await response.json();

            if (data.found) {
                // Populate modal with employee info
                document.getElementById('modal_empcode').textContent  = data.empcode;
                document.getElementById('modal_name').textContent     = data.name;
                document.getElementById('modal_office').textContent = data.office;
                // console.log(data);

                // Show confirm modal
                empcode_modal.showModal();
            } else {
                // Show error modal
                document.getElementById('error_message').textContent = data.message;
                error_modal.showModal();
            }

        } catch (error) {
            console.error('Error:', error);
        }
    });

    // On confirm — submit the actual form
    document.getElementById('confirmBtn').addEventListener('click', function () {
        document.getElementById('checkEmpcodeBtn').disabled = true;
        empcode_modal.close();
        document.getElementById('registerForm').submit();
        
    });
    </script>

</x-layout>
