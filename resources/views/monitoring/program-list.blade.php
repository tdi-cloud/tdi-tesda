<style id="2n4k8p">
.card-pop {
    opacity: 0;
    transform: scale(0.8) translateY(20px);
}

.card-pop.show {
    opacity: 1;
    transform: scale(1) translateY(0);
    transition: 
        transform 0.4s cubic-bezier(.34,1.56,.64,1), 
        opacity 0.3s ease;
}

@keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .float-anim { animation: float 3s ease-in-out infinite; }
        .fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .fade-in-delay { animation: fadeIn 0.6s ease-out 0.15s forwards; opacity: 0; }
        .fade-in-delay-2 { animation: fadeIn 0.6s ease-out 0.3s forwards; opacity: 0; }
</style>

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
            container.innerHTML = `<div class="h-full w-full col-span-4 flex items-center justify-center p-6">
            <div class="text-center max-w-sm">
            <!-- Illustration -->
            <div class="float-anim fade-in mb-6 mx-auto w-28 h-28 rounded-full bg-indigo-50 flex items-center justify-center border-2 border-dashed border-indigo-200"><i data-lucide="inbox" style="width:48px;height:48px;color:#6366f1;"></i>
            </div>
            <!-- Text -->
            <h3 id="" class="fade-in-delay text-lg poppins-semibold text-slate-800 mb-2">No programs found</h3>
            <p id="" class="fade-in-delay-2 poppins-regular text-sm text-slate-500 mb-6">Get started by creating first program. It only takes a moment.</p>

            </div>
            </div>`;
            lucide.createIcons();
            return;
        }


        container.innerHTML = programs.map((program, index) => `
            <div 
            class="card-pop w-full hover:scale-[1.03] shadow-lg relative duration-500 rounded-2xl border border-slate-300 bg-white dark:bg-slate-800 dark:border-slate-600 overflow-hidden">

                <div class="p-4">
                    <div class="flex gap-2">
                        <h1 onclick="window.location.href='/programs/${program.id}'" 
                        class="leading-5 poppins-semibold dark:text-yellow-500 flex-1 line-clamp-3 cursor-pointer">${program.title}</h1>
                        <button 
                        class="delete-program-btn btn btn-xs btn-error btn-circle btn-soft"
                        data-id="${program.id}">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>

                
                    
                    <p onclick="window.location.href='/programs/${program.id}'"
                    class="hidden line-clamp-1 poppins-regular leading-4 text-slate-400 text-[13px] mt-2 cursor-pointer">${program.description}</p>

                    <p 
                        class="poppins-regular leading-4 text-slate-400 text-[13px] mt-2 ">
                        <i class="fa-regular fa-file-lines"></i> ${program.requirements_count} Requirement(s)
                    </p>
                </div>

                
                

                <div class="px-4 pb-4 space-y-2 cursor-pointer" onclick="window.location.href='/programs/${program.id}/participants'">
    
                    ${program.batches.slice(0, 2).map(batch => `
                        <div class="rounded-lg border-slate-300 dark:border-slate-600 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 duration-500">
                            <p class="poppins-semibold mb-1">
                                <i class="fa-solid fa-layer-group text-violet-500"></i> ${batch.batch}
                                ${getStatusBadge(batch.status)} 
                            </p>

                            <div class="text-xs flex gap-3">
                                <p class="poppins-regular text-slate-500 dark:text-slate-300">
                                    <i class="fa-regular fa-calendar text-cyan-600"></i> ${formatDate(batch.date_start)}
                                </p>

                                <p class="poppins-regular text-slate-500 dark:text-slate-300">
                                    <i class="fa-regular fa-clock text-cyan-600"></i> ${batch.hours}hrs
                                </p>

                                <p class="poppins-regular text-slate-500 dark:text-slate-300">
                                    <i class="fa-solid fa-user-group text-cyan-600"></i> ${batch.participants_count} Participants
                                </p>
                            </div>
                        </div>
                    `).join('')}

                    ${program.batches.length > 2 ? `
                        <div class="text-xs text-center text-slate-500 dark:text-slate-400 poppins-medium pt-1">
                            +${program.batches.length - 2} more
                        </div>
                    ` : ''}

                </div>
            </div>
        `).join('');

        // ✅ Animate with stagger
        const cards = container.querySelectorAll('.card-pop');

        cards.forEach((card, i) => {
            setTimeout(() => {
                card.classList.add('show');
            }, i * 80); // 🔥 adjust speed here (80ms stagger)
        });
    }

    function getStatusBadge(status) {
        const map = {
            active: 'badge-info',
            completed: 'badge-success',
            upcoming: 'badge-primary',
            rescheduled: 'badge-error',
        };

        const badgeClass = map[status.toLowerCase()] || 'badge-ghost';

        return `<span class="badge badge-sm badge-soft ${badgeClass}">${status}</span>`;
    }

    async function getPrograms(search = '', status = '') {
        const response = await fetch(`/get-programs?search=${search}&status=${status}`, {
            headers: {
                'Accept': 'application/json',
            }
        });

        const result = await response.json();
        console.log(result.data);
        renderPrograms(result.data);
    }

    document.getElementById('statusFilter').addEventListener('change', function () {

        const search = document.getElementById('searchInput').value;

        getPrograms(search, this.value);

    });

    document.getElementById('searchInput').addEventListener('keyup', function() {

        const status = document.getElementById('statusFilter').value;

        getPrograms(this.value, status);

    });
    
    function formatDate(dateStr) {
        if (!dateStr) return 'TBD';
        try {
        return new Date(dateStr).toLocaleDateString('en-US', {
            month: 'short', day: 'numeric', year: 'numeric'
        });
        } catch { return dateStr; }
    }

    getPrograms('', '');

    $(document).on('click', '.delete-program-btn', function () {
        let id = $(this).data('id');

        if (!confirm('Are you sure you want to delete this program?')) {
            return;
        }

        $.ajax({
            url: `/programs/${id}/delete` ,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                alert(res.message);
                // location.reload(); // or remove row dynamically
                getPrograms('', '');
            },
            error: function (err) {
                alert('Error deleting record.');
            }
        });
    });

    

    
</script>
