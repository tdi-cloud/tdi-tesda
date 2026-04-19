<div class=" w-full p-5 overflow-auto">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-start" id="program_grid">
        <span class="loading loading-dots loading-sm"></span>

        {{-- SINGLE PROGRAM --}}


    </div>

</div>

<script>
    function renderPrograms(programs) {
        const container = document.getElementById('program_grid');
        
        if (programs.length === 0) {
            container.innerHTML = '<p>No programs found.</p>';
            return;
        }

        container.innerHTML = programs.map(program => `
            <div 
            onclick="window.location.href='/programs/${program.id}' "
            class="w-full hover:scale-[1.03] shadow-lg relative duration-500 rounded-2xl border border-slate-300 bg-white dark:bg-slate-800 dark:border-slate-600 overflow-hidden">

            <img class="max-h-40 object-cover w-full h-full" 
            src="/storage/${program.cover_pages.length > 0 
                ? program.cover_pages[0].image 
                : 'default.png'}" 
            alt="">

            <div class="p-4">
                <h1 class="leading-5 poppins-semibold dark:text-yellow-500">${program.title}</h1>
                <p class="line-clamp-1 poppins-regular leading-4 text-slate-400 text-[13px] mt-2">${program.description}</p>
            </div>

            <div class="px-4 space-y-2 pb-4">
                ${program.batches.map(batch => `<div class="rounded-lg border-slate-300 dark:border-slate-600 text-sm">
                    <p class="poppins-semibold"><i class="fa-solid fa-layer-group text-violet-500"></i> ${batch.batch}</p>
                    <div class="text-xs flex gap-2">
                        <p class="poppins-regular text-slate-500 dark:text-slate-300"><i class="fa-regular fa-calendar text-cyan-600"></i> ${formatDate(batch.date_start)}</p>
                        <p class="poppins-regular text-slate-500 dark:text-slate-300"><i class="fa-solid fa-user-group text-cyan-600"></i> ${batch.participants_count} Participants</p>

                    </div>
                    
                </div>`).join('')}
                
            </div>
                
            </div>
        `).join('');
    }

    async function getPrograms(search = '') {
        const response = await fetch(`/get-programs?search=${search}`, {
            headers: {
                'Accept': 'application/json',
            }
        });

        const result = await response.json();
        console.log(result.data);
        renderPrograms(result.data);
    }

    document.getElementById('searchInput').addEventListener('keyup', function() {
        getPrograms(this.value);
    });
    
    function formatDate(dateStr) {
        if (!dateStr) return 'TBD';
        try {
        return new Date(dateStr).toLocaleDateString('en-US', {
            month: 'short', day: 'numeric', year: 'numeric'
        });
        } catch { return dateStr; }
    }

    getPrograms();
</script>
