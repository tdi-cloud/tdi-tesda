<x-layout>
    <x-slot:title>
        Edit Profile
    </x-slot:title>

    <section class="w-full px-5 md:px-[10%] py-5">

        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 items-start">

            <div class="p-5 bg-white dark:bg-slate-700/50 rounded-2xl border border-slate-300 dark:border-slate-800">
                <h1 class="poppins-medium text-slate-900 dark:text-white ">Profile Information</h1>
                <p class="poppins-regular text-[12px] text-slate-500 dark:text-slate-300">Update your account's profile information and email
                    address.</p>

                <form action="" class="mt-2">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-medium">Username</legend>
                        <input type="text" 
                        name="username"
                        class="input w-full rounded-lg bg-slate-200 dark:bg-slate-700 poppins-medium text-[12px]" placeholder="Type here"
                        value="{{ auth()->user()->username }}"
                        />
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend poppins-medium">Email</legend>
                        <input type="email" 
                        name="email"
                        value="{{ auth()->user()->email }}"
                        class="input w-full rounded-lg bg-slate-200 dark:bg-slate-700 poppins-medium text-[12px]" placeholder="Type here" />
                    </fieldset>

                    <button class="px-4 mt-4 btn btn-neutral dark:bg-sky-800 btn-sm poppins-medium">
                        Save
                    </button>
                </form>

            </div>

            <div class="p-5 bg-white dark:bg-slate-700/50 rounded-2xl border border-slate-300 dark:border-slate-800 ">
                <h1 class="poppins-medium text-slate-900 dark:text-white">Delete Account</h1>
                <p class="poppins-regular text-[12px] text-slate-500 dark:text-slate-300">Once your account is deleted, all of its resources
                    and data will be permanently deleted. Before deleting your account, please download any data or
                    information that you wish to retain.</p>

                <button class="btn btn-error btn-sm poppins-regular text-[12px] mt-4">
                    <i class="fa-regular fa-trash-can"></i>
                    Delete Account</button>
            </div>

        </div>

    </section>


</x-layout>
