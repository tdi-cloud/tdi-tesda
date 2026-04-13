<x-layout>
    <x-slot:title>
        Login
    </x-slot:title>



    @error('email')
        <div class="toast toast-top toast-center">

            <div class="alert alert-warning">
                <span>{{ $message }}</span>
            </div>
        </div>
    @enderror

    <div class="max-w-full container h-screen flex justify-center items-center relative">

        <div class="card w-100 rounded-2xl p-5 border border-slate-300/50 dark:border-slate-500 bg-white dark:bg-slate-700 shadow-2xl">
            <div class="w-full  mb-4 flex items-center  gap-2 ">
                <img class="w-15 overflow-hidden rounded-lg" src="{{ asset('images/TDI.png') }}" alt="">
                <h1 class="poppins-bold text-2xl text-sky-900 dark:text-white">Log In</h1>
            </div>

            <div>
                <form method="POST" id="login_form" action="/login">
                    @csrf
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-semibold">Email

                        </legend>
                        <input type="text" name="email" value="{{ old('email') }}"
                            class="input poppins-medium dark:bg-slate-600 border @error('username') border-red-500 @enderror text-[12px] w-full outline-none rounded-lg bg-slate-100 focus:border-blue-500"
                            onkeydown="return event.key !== ' '"
                            onpaste="let p=event.clipboardData.getData('text');if(p.includes(' ')){event.preventDefault();this.value+=p.replace(/ /g,'')}"
                            placeholder="Enter email" required />
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-semibold">Password
                            @error('password')
                                <span class="text-red-500 text-xs">• {{ $message }}</span>
                            @enderror

                        </legend>

                        <div
                            class="flex items-center dark:bg-slate-600 border dark:border-slate-500 border-slate-300 @error('password') !border-red-500 @enderror bg-slate-100 rounded-lg py-1 px-3">
                            <input type="password" name="password" id="inputPass" value="{{ old('password') }}"
                                class="  poppins-medium  border-none text-[12px] w-full outline-none "
                                placeholder="Enter your password" required />
                            <button type="button" onclick="togglePass()" class="btn btn-sm btn-ghost btn-circle">
                                <i id="showPass" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </fieldset>

                    <label class="label flex items-center">
                        <input type="checkbox" name='remember'
                            class="checkbox checkbox-sm text-sm poppins-regular mt-2" />
                        <span class="mt-1 text-[12px] poppins-regular">Remember me</span>
                    </label>


                    <div class="w-full flex justify-end mt-6 items-center gap-4">
                        <a href="/forgot-password" class="text-xs poppins-regular underline text-blue-500">Forgotten
                            your password?</a>
                        <button type="submit" id="login_btn" class="btn bg-sky-700 text-white btn-md rounded-lg">
                            Log In 
                            <span id="login_loading" class="hidden loading loading-dots loading-sm"></span>
                        </button>
                    </div>
                </form>


            </div>

            <div class="border-t border-slate-200 mt-2 text-center pt-4">

                <p class="poppins-regular text-[12px]"><span class="text-slate-600 dark:text-slate-100">Doesn't have an account?</span> <a
                        href="/register" class="text-blue-500 underline">Sign Up</a></p>



            </div>


        </div>

    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('inputPass');
            const icon = document.getElementById('showPass');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }


        $('#login_form').on('submit', function () {
            $('#login_btn').prop('disabled', true);
            $('#login_loading').removeClass('hidden');
        });


        


        
    </script>

</x-layout>
