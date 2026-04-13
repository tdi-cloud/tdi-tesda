<x-layout>




    <x-slot:title>
        Reset Password
    </x-slot:title>

    <section class="flex w-full justify-center items-center h-screen">
        <div class="w-100 p-5 rounded-2xl border border-slate-300">
            <div class="flex gap-2 justify-center">
                <img src="{{ asset('images/TDI.png') }}" class="w-5" alt="">
                <span class="text-xs poppins-regular text-sky-900">TESDA Development institute</span>
            </div>

            <div class="flex gap-2 items-center my-6">
                <div
                    class="icon min-w-13 min-h-13 flex items-center justify-center text-white text-lg bg-gradient-to-t from-green-600 to-green-800 rounded-2xl">
                    <i class="fa-solid fa-key"></i>
                </div>

                <div>
                    <h1 class="poppins-semibold">Reset your password</h1>

                </div>
            </div>
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="w-full bg-slate-200 flex p-2  rounded-lg items-center gap-2">
                    <input type="password" name="password" placeholder="New Password"
                        id="inputPass"
                        class="w-full border-none outline-none
                        border @error('password') border-red-500 @enderror">

                        <i onclick="togglePass()" id="showPass" class="fa-regular fa-eye"></i>
                   
                </div>
                 @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                <div class="w-full bg-slate-200 flex p-2  rounded-lg items-center gap-2">
                    <input
                    class="w-full border-none outline-none"
                    id="inputPassCon"
                    type="password" name="password_confirmation" placeholder="Confirm New Password">

                    <i onclick="togglePassCon()" id="showPassCon" class="fa-regular fa-eye"></i>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-neutral bg-sky-800 text-white rounded-lg">Reset Password</button>
                </div>
                
            </form>

        </div>
    </section>

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

        function togglePassCon(){
            const input = document.getElementById('inputPassCon');
            const icon = document.getElementById('showPassCon');

            if(input.type === 'password'){
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }else{
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>


</x-layout>
