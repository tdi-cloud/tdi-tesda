<x-layout>
    <x-slot:title>
        Forgot Password
    </x-slot:title>
    @include('components.loading')

    <section class="flex w-full justify-center items-center h-screen">
        <div class="w-100 p-5 rounded-2xl border border-slate-300">
            <div class="flex gap-2 justify-center">
                <img src="{{ asset('images/TDI.png') }}" class="w-5" alt="">
                <span class="text-xs poppins-regular text-sky-900">TESDA Development institute</span>
            </div>

            <div class="flex gap-2 items-start my-6">
                <div class="icon min-w-13 min-h-13 flex items-center justify-center text-white text-lg bg-gradient-to-t from-sky-600 to-sky-800 rounded-2xl">
                    <i class="fa-solid fa-key"></i>
                </div>

                <div>
                    <h1 class="poppins-semibold">Forgot Password?</h1>
                    <p class="text-sm leading-4 text-slate-500">No worries! Enter your email address and we'll send you a link to reset your password.</p>
                </div>
            </div>

            <form method="POST" id="reset_form" action="{{ route('password.email') }}">
                @csrf

                @if (session('success'))
                    <p class="text-green-500 text-sm">{{ session('success') }}</p>
                @endif

                <div>
                    <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" placeholder="Enter your email"
                        class="w-full p-2 rounded-lg bg-slate-200 border-slate-300 border @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end mt-4">

                    <button type="submit" id="resetFormBtn" class="btn btn-neutral rounded-lg bg-sky-800 shadow-none">Send Reset Link</button>

                </div>

                
            </form>

        </div>
    </section>


    <script>
        $('#resetFormBtn').on('click', function(){
            loading_modal.showModal();
        });

    </script>



</x-layout>
