
<style>

    .toolbar-btnClosure {
      width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
      border-radius: 6px; border: none; cursor: pointer; transition: all 0.15s;
      background: transparent; color: #64748b; position: relative;
    }
    .toolbar-btnClosure:hover { background: #f1f5f9; color: #1e293b; }
    .toolbar-btnClosure.active { background: #e0e7ff; color: #4338ca; }

    .separator { width: 1px; height: 24px; background: #e2e8f0; margin: 0 4px; flex-shrink: 0; }

    #closure_editor {
      min-height: 280px; outline: none; padding: 20px 24px; font-size: 15px;
      line-height: 1.7; color: #1e293b; caret-color: #4338ca;
    }
    #closure_editor:empty::before {
      content: attr(data-placeholder); color: #94a3b8; pointer-events: none;
    }
    #closure_editor ul, #closure_editor ol { padding-left: 24px; margin: 8px 0; }
    #closure_editor ul li, #closure_editor ol li { margin: 2px 0; }

    .dropdownClosure { position: relative; }
    .dropdown-menu {
      position: absolute; top: calc(100% + 6px); left: 50%; transform: translateX(-50%);
      background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.12); padding: 6px; z-index: 50;
      min-width: 160px; display: none;
    }
    .dropdown-menu.open { display: block; }
    .dropdown-item {
      display: flex; align-items: center; gap: 10px; padding: 8px 12px;
      border-radius: 6px; cursor: pointer; font-size: 13px; color: #334155;
      border: none; background: none; width: 100%; text-align: left; font-family: inherit;
    }
    .dropdown-item:hover { background: #f1f5f9; }
    .dropdown-item.active { background: #e0e7ff; color: #4338ca; }
    .dropdown-item .preview { font-family: 'JetBrains Mono', monospace; font-size: 12px; color: #94a3b8; margin-left: auto; }

    .color-palette {
      display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; padding: 10px;
    }
    .color-swatch {
      width: 28px; height: 28px; border-radius: 6px; cursor: pointer; border: 2px solid transparent;
      transition: all 0.15s;
    }
    .color-swatch:hover { transform: scale(1.1); }
    .color-swatch.active { border-color: #334155; box-shadow: 0 0 0 1px #fff, 0 0 0 3px #334155; }

    .editor-wrapClosure {
      border: 2px solid #e2e8f0; border-radius: 14px; overflow: hidden;
      transition: border-color 0.2s; background: #fff;
    }
    .editor-wrapClosure:focus-within { border-color: #818cf8; }

    .html-output {
      background: #1e1e2e; color: #cdd6f4; border-radius: 10px; padding: 16px;
      font-family: 'JetBrains Mono', monospace; font-size: 12px; line-height: 1.6;
      max-height: 200px; overflow: auto; white-space: pre-wrap; word-break: break-all;
    }
  </style>

<dialog id="edit_closure_modal" class="modal">
  <div class="modal-box w-11/12 max-w-3xl p-0">

    <div class="p-4 border-b border-slate-300 dark:border-slate-600 text-slate-400 dark:text-slate-100">
        <h1 class="poppins-semibold ">Edit Closure</h1> 
    </div>

    <div id="app" class="h-full w-full overflow-auto bg-slate-200 dark:bg-slate-600" style="padding: 24px;">
        
   <div style="max-width: 760px; margin: 0 auto;">
    
    <div class="editor-wrapClosure">

     <!-- Toolbar -->
     <div style="display: flex; align-items: center; gap: 2px; padding: 8px 10px; background: #fafbfc; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap;">
      <button class="toolbar-btnClosure" data-cmd="bold" title="Bold"><i data-lucide="bold" style="width:16px;height:16px;"></i></button> <button class="toolbar-btnClosure" data-cmd="italic" title="Italic"><i data-lucide="italic" style="width:16px;height:16px;"></i></button> 
      <button class="toolbar-btnClosure" data-cmd="underline" title="Underline"><i data-lucide="underline" style="width:16px;height:16px;"></i></button> <button class="toolbar-btnClosure" data-cmd="strikeThrough" title="Strikethrough"><i data-lucide="strikethrough" style="width:16px;height:16px;"></i></button>
      <div class="separator"></div>
      
      
      <!-- Bullet List Dropdown -->
      <div class="dropdownClosure">
       <button class="toolbar-btnClosure" id="ulBtnClosure" title="Bullet List"><i data-lucide="list" style="width:16px;height:16px;"></i></button>
       <div class="dropdown-menu" id="ulMenuClosure">
        <button class="dropdown-item" data-list="disc"><span>● Disc</span><span class="preview">default</span></button> <button class="dropdown-item" data-list="circle"><span>○ Circle</span><span class="preview">outline</span></button> <button class="dropdown-item" data-list="square"><span>■ Square</span><span class="preview">filled</span></button>
       </div>
      </div><!-- Ordered List Dropdown -->
      <div class="dropdown">
       <button class="toolbar-btnClosure" id="olBtnClosure" title="Ordered List"><i data-lucide="list-ordered" style="width:16px;height:16px;"></i></button>
       <div class="dropdown-menu" id="olMenuClosure">
        <button class="dropdown-item" data-ol="decimal"><span>1, 2, 3</span><span class="preview">numbers</span></button> <button class="dropdown-item" data-ol="lower-alpha"><span>a, b, c</span><span class="preview">lowercase</span></button> <button class="dropdown-item" data-ol="upper-alpha"><span>A, B, C</span><span class="preview">uppercase</span></button> <button class="dropdown-item" data-ol="lower-roman"><span>i, ii, iii</span><span class="preview">roman</span></button> <button class="dropdown-item" data-ol="upper-roman"><span>I, II, III</span><span class="preview">Roman</span></button>
       </div>
      </div>
      <div class="separator"></div><button class="toolbar-btnClosure" data-cmd="indent" title="Indent">
       <svg width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="8 6 4 12 8 18"></polyline><line x1="4" y1="12" x2="21" y2="12"></line>
       </svg></button> 
       
       
       
       <button class="toolbar-btnClosure" data-cmd="outdent" title="Outdent">
       <svg width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 6 20 12 16 18"></polyline><line x1="4" y1="12" x2="20" y2="12"></line>
       </svg></button>
      <div class="separator"></div><!-- Text Color Dropdown -->
      <div class="dropdownClosure">
       <button class="toolbar-btnClosure" id="colorBtnClosure" title="Text Color"><i data-lucide="palette" style="width:16px;height:16px;"></i></button>
       <div class="dropdown-menu" id="colorMenuClosure" style="min-width: 220px; padding: 0;">
        <div class="color-palette" id="colorPaletteClosure"></div>
       </div>
      </div>
      <div class="separator"></div><button class="toolbar-btnClosure" data-cmd="removeFormat" title="Clear Formatting"><i data-lucide="eraser" style="width:16px;height:16px;"></i></button>
     </div>
     
     <!-- Editor -->
     <div id="closure_editor" contenteditable="true" data-placeholder="Start typing here..."></div>

    </div>
    
    
    <!-- HTML Output -->
    <div style="margin-top: 20px;" class="hidden">
     <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
      <span style="font-size: 13px; font-weight: 600; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase;">HTML Output</span> <button id="copyBtn" style="font-size: 12px; font-weight: 500; color: #4338ca; background: #e0e7ff; border: none; padding: 5px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 4px; font-family: inherit;"> <i data-lucide="copy" style="width:13px;height:13px;"></i> Copy </button>
     </div>
     <div class="html-output" id="htmlOutputClosure">
        <!-- HTML will appear here -->
     </div>
    </div>
   </div>
  </div>


  <div>

    <div class="modal-action m-0 flex justify-between p-4 border-t border-slate-300 dark:border-slate-600">
      <form method="dialog">
        <!-- if there is a button, it will close the modal -->
        <button class="btn btn-sm btn-soft btn-default"><i class="fa-solid fa-x"></i> Close</button>
      </form>


      <button onclick="saveClosure()" class="btn btn-sm btn-success btn-soft text-green-600 hover:text-white"><i class="fa-solid fa-check"></i> Save</button>
    </div>

  </div>

    



  </div>
</dialog>


<script>
function saveClosure() {
    edit_closure_modal.close();

    const Chtml = closureEditor.innerHTML.trim();
    const valuec = (Chtml === '<br>' || Chtml === '') ? '' : Chtml;

    htmlOutputClosure.textContent = valuec;
    $('#closurePreviewHere').html(valuec);
    $('#closure_input').val(valuec);
}

const closureModal = document.getElementById('edit_closure_modal');

// 🚫 Disable ESC key
closureModal.addEventListener('cancel', function (e) {
    e.preventDefault();
});

// 🚫 Disable click outside
closureModal.addEventListener('click', function (e) {
    const rect = closureModal.querySelector('.modal-box').getBoundingClientRect();
    const isInDialog =
    rect.top <= e.clientY &&
    e.clientY <= rect.top + rect.height &&
    rect.left <= e.clientX &&
    e.clientX <= rect.left + rect.width;

    if (!isInDialog) e.preventDefault();
});

const closureEditor = document.getElementById('closure_editor');
const htmlOutputClosure = document.getElementById('htmlOutputClosure');
const ulMenuClosure = document.getElementById('ulMenuClosure');
const olMenuClosure = document.getElementById('olMenuClosure');
const colorMenuClosure = document.getElementById('colorMenuClosure');
const colorPaletteClosure = document.getElementById('colorPaletteClosure');

// 🎨 Colors
const colorsClosure = [
  '#000000', '#ef4444', '#f97316', '#eab308',
  '#22c55e', '#0ea5e9', '#8b5cf6', '#ec4899'
];

let savedSelectionClosure = null;

// 🎨 Color palette
colorsClosure.forEach(color => {
  const swatch = document.createElement('div');
  swatch.className = 'color-swatch';
  swatch.style.backgroundColor = color;
  swatch.dataset.color = color;

  swatch.addEventListener('click', (e) => {
    e.stopPropagation();
    e.preventDefault();

    if (savedSelectionClosure) {
      window.getSelection().removeAllRanges();
      window.getSelection().addRange(savedSelectionClosure);
    }

    const sel = window.getSelection();
    if (sel.toString().length > 0) {
      document.execCommand('foreColor', false, color);
    }

    colorMenuClosure.classList.remove('open');
    closureEditor.focus();
    updateOutput();
    savedSelectionClosure = null;
  });

  colorPaletteClosure.appendChild(swatch);
});

// Save selection for color
document.getElementById('colorBtnClosure').addEventListener('mousedown', () => {
  const sel = window.getSelection();
  if (sel.rangeCount > 0) {
    savedSelectionClosure = sel.getRangeAt(0).cloneRange();
  }
});

// Basic commands
document.querySelectorAll('[data-cmd]').forEach(btn => {
  btn.addEventListener('mousedown', e => e.preventDefault());
  btn.addEventListener('click', () => {
    document.execCommand(btn.dataset.cmd, false, null);
    closureEditor.focus();
    updateToolbarState();
    updateOutput();
  });
});

// Dropdown toggles
document.getElementById('ulBtnClosure').addEventListener('click', e => {
  e.stopPropagation();
  olMenuClosure.classList.remove('open');
  colorMenuClosure.classList.remove('open');
  ulMenuClosure.classList.toggle('open');
});

document.getElementById('olBtnClosure').addEventListener('click', e => {
  e.stopPropagation();
  ulMenuClosure.classList.remove('open');
  colorMenuClosure.classList.remove('open');
  olMenuClosure.classList.toggle('open');
});

document.getElementById('colorBtnClosure').addEventListener('click', e => {
  e.stopPropagation();
  ulMenuClosure.classList.remove('open');
  olMenuClosure.classList.remove('open');
  colorMenuClosure.classList.toggle('open');
});

document.addEventListener('click', () => {
  ulMenuClosure.classList.remove('open');
  olMenuClosure.classList.remove('open');
  colorMenuClosure.classList.remove('open');
});

// ✅ BULLET LIST
document.querySelectorAll('[data-list]').forEach(btn => {
  btn.addEventListener('mousedown', e => e.preventDefault());

  btn.addEventListener('click', e => {
    e.stopPropagation();

    document.execCommand('insertUnorderedList', false, null);

    const sel = window.getSelection();
    let node = sel.getRangeAt(0).commonAncestorContainer;
    node = node.nodeType === 3 ? node.parentNode : node;

    const ulNode = node.closest('ul');
    if (ulNode) {
      ulNode.style.listStyleType = btn.dataset.list;
    }

    ulMenuClosure.classList.remove('open');
    closureEditor.focus();
    updateOutput();
  });
});

// ✅ ORDERED LIST (FIXED 🔥)
document.querySelectorAll('[data-ol]').forEach(btn => {
  btn.addEventListener('mousedown', e => e.preventDefault());

  btn.addEventListener('click', e => {
    e.stopPropagation();

    const sel = window.getSelection();
    if (!sel.rangeCount) return;

    let node = sel.getRangeAt(0).commonAncestorContainer;
    node = node.nodeType === 3 ? node.parentNode : node;

    let olNode = node.closest('ol');

    // Create only if not exists
    if (!olNode) {
      document.execCommand('insertOrderedList', false, null);

      const newSel = window.getSelection();
      let newNode = newSel.getRangeAt(0).commonAncestorContainer;
      newNode = newNode.nodeType === 3 ? newNode.parentNode : newNode;

      const liNode = newNode.closest('li');
      if (liNode) {
        olNode = liNode.closest('ol');
      }
    }

    // Apply style
    if (olNode) {
      olNode.style.listStyleType = btn.dataset.ol;
      olNode.setAttribute('type', mapListType(btn.dataset.ol)); // 🔥 FIX
    }

    olMenuClosure.classList.remove('open');
    closureEditor.focus();
    updateOutput();
  });
});

// 🔥 mapping for uppercase fix
function mapListType(type) {
  switch (type) {
    case 'upper-alpha': return 'A';
    case 'lower-alpha': return 'a';
    case 'upper-roman': return 'I';
    case 'lower-roman': return 'i';
    default: return '1';
  }
}

// TAB handling
closureEditor.addEventListener('keydown', (e) => {
  if (e.key === 'Tab') {
    e.preventDefault();

    if (e.shiftKey) {
      document.execCommand('outdent');
    } else {
      document.execCommand('indent');
    }

    updateOutput();
  }
});

// Toolbar state
function updateToolbarState() {
  const commands = ['bold','italic','underline','strikeThrough'];

  commands.forEach(cmd => {
    const btn = document.querySelector(`[data-cmd="${cmd}"]`);
    if (!btn) return;

    try {
      const isActive = document.queryCommandState(cmd);
      btn.classList.toggle('active', isActive);
    } catch (e) {
      btn.classList.remove('active');
    }
  });
}

// Output
function updateOutput() {
  const html = closureEditor.innerHTML.trim();
  const value = (html === '<br>' || html === '') ? '' : html;
  htmlOutputClosure.textContent = value;
  $('#closure_input').val(value);
}

closureEditor.addEventListener('input', updateOutput);
closureEditor.addEventListener('keyup', updateToolbarState);
closureEditor.addEventListener('mouseup', updateToolbarState);

// Copy
document.getElementById('copyBtn').addEventListener('click', () => {
  const text = htmlOutputClosure.textContent;
  if (!text) return;

  navigator.clipboard.writeText(text).then(() => {
    const btn = document.getElementById('copyBtn');
    btn.innerHTML = 'Copied!';
    setTimeout(() => btn.innerHTML = 'Copy', 1500);
  });
});

// Paste as plain text
closureEditor.addEventListener('paste', function (e) {
  e.preventDefault();
  let text = (e.clipboardData || window.clipboardData).getData('text/plain');
  text = text.replace(/\r\n/g, '\n');
  document.execCommand('insertText', false, text);
  updateOutput();
});

lucide.createIcons();
</script>