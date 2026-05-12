<fieldset class="fieldset  w-full">
    <legend class="fieldset-legend m-0 p-0">Search Program</legend>
    <label class="input w-full outline-none border border-slate-200 dark:border-slate-600 rounded-lg poppins-regular text-sm shadow-lg">
    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <g
        stroke-linejoin="round"
        stroke-linecap="round"
        stroke-width="2.5"
        fill="none"
        stroke="currentColor"
        >
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.3-4.3"></path>
        </g>
    </svg>
    <input id="searchProg" type="search" class="grow  w-full " placeholder="Search program..." />
    </label>

</fieldset>


<fieldset class="fieldset w-30">
    <legend class="fieldset-legend m-0 p-0">Year:</legend>
        <select id="yearFilter" class="select w-full outline-none border border-slate-200 dark:border-slate-600 rounded-lg poppins-regular text-sm shadow-lg">
            <option value="">All Years</option>
           
        </select>
</fieldset>

<fieldset class="fieldset w-20">
    <legend class="fieldset-legend m-0 p-0">Show:</legend>
        <select id="perPage" class="select w-full outline-none border border-slate-200 dark:border-slate-600 rounded-lg poppins-regular text-sm shadow-lg">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
</fieldset>


