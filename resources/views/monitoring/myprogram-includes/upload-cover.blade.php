<style>
   
    .upload-zone { transition: all 0.3s ease; border: 2px solid #e2e8f0; background: #f8fafc; }
    .upload-zone.dragover { border-color: #0891b2; background: rgba(8, 145, 178, 0.05); }
    .preview-img { max-height: 200px; object-fit: cover; border-radius: 12px; box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
    .modal-box { border-radius: 16px; background: #ffffff; border: 1px solid #e2e8f0; }
    .slide-in { animation: slideIn 0.4s ease; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .progress-ring { width: 60px; height: 60px; border-radius: 50%; background: conic-gradient(#0891b2 0deg, #e2e8f0 0deg); display: flex; align-items: center; justify-content: center; }
    .progress-inner { width: 52px; height: 52px; border-radius: 50%; background: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; color: #0891b2; }
  </style>

<dialog id="upload_modal" class="modal">
    <div class="modal-box bg-white max-w-md w-full rounded-2xl bg-white dark:!bg-slate-800">
     <!-- Close Button -->
     <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3 text-slate-400 hover:text-slate-600">✕</button>
     </form>
     
     
     <!-- Header -->
     <div class="mb-6">
      <h3 id="modal-title" class="font-bold text-2xl text-cyan-600 mb-2">Upload Cover Photo</h3>
      <p class="text-sm text-slate-600">Select an image for your training program</p>
     </div>

     <input type="hidden" id="program_id" value="{{ $myprogram->id }}">
     
     <!-- Progress Indicator -->
     <div class="flex justify-center mb-6">
      <div class="progress-ring" id="progress-ring">
       <div class="progress-inner" id="progress-text">
        0%
       </div>
      </div>
     </div><!-- Upload Zone -->
     <div id="upload-zone" class="upload-zone rounded-xl p-2 text-center cursor-pointer mb-4" onclick="document.getElementById('file-input').click()" ondragover="event.preventDefault(); this.classList.add('dragover')" ondragleave="this.classList.remove('dragover')" ondrop="event.preventDefault(); this.classList.remove('dragover'); handleFiles(event.dataTransfer.files)">

        

      <div id="upload-placeholder" class="slide-in">
        <i class="fa-solid fa-cloud-arrow-up"></i>
       <p class="font-semibold text-slate-700 text-sm">Drag image here</p>
       <p class="text-xs text-slate-500 mt-2">or click to browse</p>
      </div>
      <div id="upload-preview" class="hidden slide-in"><img id="preview-img" class="preview-img w-full mb-4" alt="Preview">
       <p id="file-name" class="font-medium text-slate-700 text-sm truncate mb-1"></p>
       <p id="file-size" class="text-xs text-slate-500 mb-3"></p><button type="button" class="btn btn-sm btn-outline btn-sm gap-1 w-full text-cyan-600 border-cyan-300 hover:border-cyan-500" onclick="event.stopPropagation(); clearFile()"> <i data-lucide="x" style="width:14px;height:14px;"></i> Change </button>
      </div>
     </div><input type="file" id="file-input" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="handleFiles(this.files)"> <!-- Error Message -->
     <div id="error-msg" class="hidden mb-3 slide-in">
      <div class="bg-red-100 border border-red-300 rounded-lg p-3">
       <div class="flex gap-2"><i data-lucide="alert-circle" style="width:16px;height:16px;" class="text-red-600 flex-shrink-0 mt-0.5"></i>
        <p id="error-text" class="text-sm text-red-700"></p>
       </div>
      </div>
     </div><!-- Success Message -->
     <div id="success-msg" class="hidden mb-3 slide-in">
      <div class="bg-green-100 border border-green-300 rounded-lg p-3">
       <div class="flex gap-2"><i data-lucide="check-circle" style="width:16px;height:16px;" class="text-green-600 flex-shrink-0 mt-0.5"></i>
        <p class="text-sm text-green-700 font-medium">Upload successful!</p>
       </div>
      </div>
     </div><!-- Requirements -->
     <div class="mb-4 bg-blue-50 rounded-lg p-3">
      <div class="space-y-2 text-xs">
       <div class="flex items-center gap-2"><i data-lucide="check" style="width:14px;height:14px;" class="text-cyan-600 flex-shrink-0"></i> <span class="text-slate-700">JPG, PNG or WebP</span>
       </div>
       <div class="flex items-center gap-2"><i data-lucide="check" style="width:14px;height:14px;" class="text-cyan-600 flex-shrink-0"></i> <span class="text-slate-700">Max 2 MB</span>
       </div>
       <div class="flex items-center gap-2"><i data-lucide="check" style="width:14px;height:14px;" class="text-cyan-600 flex-shrink-0"></i> <span class="text-slate-700">1280×720px recommended</span>
       </div>
      </div>
     </div><!-- Actions -->
     <div class="modal-action gap-2 justify-end">


      <form method="dialog">
       <button class="btn btn-sm btn-ghost text-slate-700 hover:text-slate-900">Cancel</button>
      </form><button id="upload-btn" class="btn btn-sm gap-2 bg-cyan-600 hover:bg-cyan-700 border-0 text-white font-semibold" disabled onclick="submitUpload()"> 
        <i class="fa-solid fa-cloud-arrow-up"></i>
        Upload </button>
     </div>
    </div>
    <form method="dialog" class="modal-backdrop">
     <button>close</button>
    </form>
   </dialog>


   <script>
let existingImageUrl = null;
let selectedFile = null;
let uploadProgress = 0;

const MAX_SIZE = 2 * 1024 * 1024;

// ======================
// INIT EXISTING IMAGE
// ======================
@if($cover && $cover->image)
    setExistingImage("{{ asset('storage/' . $cover->image) }}");
@endif

function uploadModal() {
    upload_modal.showModal();

    @if($cover && $cover->image)
        setExistingImage("{{ asset('storage/' . $cover->image) }}");
    @endif
}

// ======================
// SET EXISTING IMAGE
// ======================
function setExistingImage(url) {
    if (!url) return;

    existingImageUrl = url;

    const img = document.getElementById('preview-img');
    img.src = url;

    document.getElementById('upload-placeholder').classList.add('hidden');
    document.getElementById('upload-preview').classList.remove('hidden');

    document.getElementById('file-name').textContent = 'Existing image';
    document.getElementById('file-size').textContent = '';

    // DO NOT block upload
    selectedFile = null;

    const btn = document.getElementById('upload-btn');
    btn.disabled = false; // ✅ allow update
    btn.classList.remove('btn-disabled');

    updateProgress(100);
}

// ======================
// HANDLE FILE INPUT
// ======================
function handleFiles(files) {
    if (!files || !files.length) return;

    const file = files[0];
    const allowed = ['image/jpeg', 'image/png', 'image/webp'];

    if (!allowed.includes(file.type)) {
        showError('Invalid format. Use JPG, PNG, or WebP.');
        return;
    }

    if (file.size > MAX_SIZE) {
        showError('File exceeds 2 MB limit.');
        return;
    }

    selectedFile = file;

    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('preview-img').src = e.target.result;

        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent =
            (file.size / 1024).toFixed(1) + ' KB';

        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('upload-preview').classList.remove('hidden');

        const btn = document.getElementById('upload-btn');
        btn.disabled = false; // ✅ enable upload
        btn.classList.remove('btn-disabled');

        updateProgress(35);
        hideError();

        lucide.createIcons();
    };

    reader.readAsDataURL(file);
}

// ======================
// CLEAR FILE
// ======================
function clearFile() {
    selectedFile = null;
    document.getElementById('file-input').value = '';

    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('upload-preview').classList.add('hidden');

    const btn = document.getElementById('upload-btn');
    btn.disabled = true;

    updateProgress(0);
    hideError();
    hideSuccess();
}

// ======================
// PROGRESS
// ======================
function updateProgress(percent) {
    uploadProgress = percent;

    const progressText = document.getElementById('progress-text');
    const ring = document.getElementById('progress-ring');

    progressText.textContent = percent + '%';

    const deg = (percent / 100) * 360;
    ring.style.background = `conic-gradient(#06b6d4 ${deg}deg, #334155 ${deg}deg)`;
}

// ======================
// ERRORS
// ======================
function showError(msg) {
    document.getElementById('error-text').textContent = msg;
    document.getElementById('error-msg').classList.remove('hidden');
}

function hideError() {
    document.getElementById('error-msg').classList.add('hidden');
}

function hideSuccess() {
    document.getElementById('success-msg').classList.add('hidden');
}

// ======================
// SUBMIT UPLOAD
// ======================
function submitUpload() {
    const btn = document.getElementById('upload-btn');

    if (!selectedFile) {
        showError('Please select a new image to upload.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Uploading…';

    let formData = new FormData();
    formData.append('image', selectedFile);
    formData.append('program_id', document.getElementById('program_id').value);

    let progress = 35;
    const interval = setInterval(() => {
        progress += Math.random() * 25;
        if (progress >= 95) progress = 95;
        updateProgress(Math.floor(progress));
    }, 300);

    $.ajax({
        url: '/upload-cover',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            clearInterval(interval);
            updateProgress(100);

            document.getElementById('success-msg').classList.remove('hidden');


            if (response.image_url) {
                setExistingImage(response.image_url); // 🔥 update preview instantly
            }

            btn.innerHTML = '<i data-lucide="check"></i> Done';
            btn.disabled = true;

            location.reload();

            lucide.createIcons();
        },
        error: function() {
            clearInterval(interval);
            showError('Upload failed. Try another file.');

            btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload';
            btn.disabled = false;
        }
    });
}

// ======================
// RESET MODAL
// ======================
document.getElementById('upload_modal').addEventListener('close', () => {
    setTimeout(() => {
        selectedFile = null;
        uploadProgress = 0;

        document.getElementById('file-input').value = '';

        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('upload-preview').classList.add('hidden');

        const btn = document.getElementById('upload-btn');
        btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload';
        btn.disabled = true;

        updateProgress(0);
        hideError();
        hideSuccess();
    }, 100);
});

// ======================
lucide.createIcons();
  </script>