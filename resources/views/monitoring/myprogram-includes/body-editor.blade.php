<style>
    .toolbar-btn {
      width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
      border-radius: 6px; border: none; cursor: pointer; transition: all 0.15s;
      background: transparent; color: #64748b; position: relative;
    }
    .toolbar-btn:hover { background: #f1f5f9; color: #1e293b; }
    .toolbar-btn.active { background: #e0e7ff; color: #4338ca; }

    .separator { width: 1px; height: 24px; background: #e2e8f0; margin: 0 4px; flex-shrink: 0; }

    #tbEditor {
      min-height: 280px; outline: none; padding: 20px 24px; font-size: 15px;
      line-height: 1.7; color: #1e293b; caret-color: #4338ca;
    }
    #tbEditor:empty::before {
      content: attr(data-placeholder); color: #94a3b8; pointer-events: none;
    }
    #tbEditor ul, #editor ol { padding-left: 24px; margin: 8px 0; }
    #tbEditor ul li, #editor ol li { margin: 2px 0; }

    .dropdown { position: relative; }
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

    .editor-wrap {
      border: 2px solid #e2e8f0; border-radius: 14px; overflow: hidden;
      transition: border-color 0.2s; background: #fff;
    }
    .editor-wrap:focus-within { border-color: #818cf8; }

    .html-output {
      background: #1e1e2e; color: #cdd6f4; border-radius: 10px; padding: 16px;
      font-family: 'JetBrains Mono', monospace; font-size: 12px; line-height: 1.6;
      min-height: 200px; overflow: auto; white-space: pre-wrap; word-break: break-all;
    }
</style>

<dialog id="edit_body_modal" class="modal">
  <div class="modal-box w-11/12 max-w-3xl p-0">

    <div class="p-4 border-b border-slate-300 dark:border-slate-600 text-slate-400 dark:text-slate-100">
        <h1 class="poppins-semibold">Edit Body</h1>
    </div>

    <div id="app" class="h-full w-full overflow-auto bg-slate-200 dark:bg-slate-600" style="padding: 24px;">

      <div style="max-width: 760px; margin: 0 auto;">

        <div class="editor-wrap">

          <!-- Toolbar -->
          <div style="display: flex; align-items: center; gap: 2px; padding: 8px 10px; background: #fafbfc; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap;">
            <button class="toolbar-btn" data-cmd="bold" title="Bold"><i data-lucide="bold" style="width:16px;height:16px;"></i></button>
            <button class="toolbar-btn" data-cmd="italic" title="Italic"><i data-lucide="italic" style="width:16px;height:16px;"></i></button>
            <button class="toolbar-btn" data-cmd="underline" title="Underline"><i data-lucide="underline" style="width:16px;height:16px;"></i></button>
            <button class="toolbar-btn" data-cmd="strikeThrough" title="Strikethrough"><i data-lucide="strikethrough" style="width:16px;height:16px;"></i></button>
            <div class="separator"></div>

            <!-- Bullet List Dropdown -->
            <div class="dropdown hidden">
              <button class="toolbar-btn" id="tbUlBtn" title="Bullet List"><i data-lucide="list" style="width:16px;height:16px;"></i></button>
              <div class="dropdown-menu" id="tbUlMenu">
                <button class="dropdown-item" data-list="disc"><span>● Disc</span><span class="preview">default</span></button>
                <button class="dropdown-item" data-list="circle"><span>○ Circle</span><span class="preview">outline</span></button>
                <button class="dropdown-item" data-list="square"><span>■ Square</span><span class="preview">filled</span></button>
              </div>
            </div>

            <!-- Ordered List Dropdown -->
            <div class="dropdown hidden">
              <button class="toolbar-btn" id="tbOlBtn" title="Ordered List"><i data-lucide="list-ordered" style="width:16px;height:16px;"></i></button>
              <div class="dropdown-menu" id="tbOlMenu">
                <button class="dropdown-item" data-ol="decimal"><span>1, 2, 3</span><span class="preview">numbers</span></button>
                <button class="dropdown-item" data-ol="lower-alpha"><span>a, b, c</span><span class="preview">lowercase</span></button>
                <button class="dropdown-item" data-ol="upper-alpha"><span>A, B, C</span><span class="preview">uppercase</span></button>
                <button class="dropdown-item" data-ol="lower-roman"><span>i, ii, iii</span><span class="preview">roman</span></button>
                <button class="dropdown-item" data-ol="upper-roman"><span>I, II, III</span><span class="preview">Roman</span></button>
              </div>
            </div>

            <div class="separator"></div>
            <button class="toolbar-btn" data-cmd="indent" title="Indent">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="8 6 4 12 8 18"></polyline><line x1="4" y1="12" x2="21" y2="12"></line></svg>
            </button>
            <button class="toolbar-btn" data-cmd="outdent" title="Outdent">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 6 20 12 16 18"></polyline><line x1="4" y1="12" x2="20" y2="12"></line></svg>
            </button>
            <div class="separator"></div>

            <!-- Text Color Dropdown -->
            <div class="dropdown">
              <button class="toolbar-btn" id="tbColorBtn" title="Text Color"><i data-lucide="palette" style="width:16px;height:16px;"></i></button>
              <div class="dropdown-menu" id="tbColorMenu" style="min-width: 220px; padding: 0;">
                <div class="color-palette" id="tbColorPalette"></div>
              </div>
            </div>

            <div class="separator"></div>
            <button class="toolbar-btn" data-cmd="removeFormat" title="Clear Formatting"><i data-lucide="eraser" style="width:16px;height:16px;"></i></button>
          </div>

          <!-- Editor -->
          <div id="tbEditor"  contenteditable="true" data-placeholder="Start typing here..."></div>

        </div>

        <!-- HTML Output -->
        <div style="margin-top: 20px;" class="hidden">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <span style="font-size: 13px; font-weight: 600; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase;">HTML Output</span>
            <button id="tbCopyBtn" style="font-size: 12px; font-weight: 500; color: #4338ca; background: #e0e7ff; border: none; padding: 5px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 4px; font-family: inherit;">
              <i data-lucide="copy" style="width:13px;height:13px;"></i> Copy
            </button>
          </div>
          <div class="html-output h-200" id="tbOutput"></div>
        </div>

      </div>
    </div>

    <div>
      <div class="modal-action m-0 flex justify-between p-4 border-t border-slate-300 dark:border-slate-600">
        <form method="dialog">
          <button class="btn btn-sm btn-soft btn-default"><i class="fa-solid fa-x"></i> Close</button>
        </form>
        <button onclick="saveBody()" class="btn btn-sm btn-success btn-soft text-green-600 hover:text-white"><i class="fa-solid fa-check"></i> Save</button>
      </div>
    </div>

  </div>
</dialog>

<script>
function saveBody() {
    edit_body_modal.close();
    const html = tbEditor.innerHTML.trim();
    const value = (html === '<br>' || html === '') ? '' : html;
    tbOutput.textContent = value;
    $('#PreviewHere').html(value);
    $('#body_input').val(value);
}

const tbModal = document.getElementById('edit_body_modal');

tbModal.addEventListener('cancel', e => e.preventDefault());

tbModal.addEventListener('click', function (e) {
    const rect = tbModal.querySelector('.modal-box').getBoundingClientRect();
    const inside =
        rect.top <= e.clientY && e.clientY <= rect.top + rect.height &&
        rect.left <= e.clientX && e.clientX <= rect.left + rect.width;
    if (!inside) e.preventDefault();
});

const tbEditor       = document.getElementById('tbEditor');
const tbOutput       = document.getElementById('tbOutput');
const tbUlMenu       = document.getElementById('tbUlMenu');
const tbOlMenu       = document.getElementById('tbOlMenu');
const tbColorMenu    = document.getElementById('tbColorMenu');
const tbColorPalette = document.getElementById('tbColorPalette');

const tbColors = [
    '#000000', '#ef4444', '#f97316', '#eab308',
    '#22c55e', '#0ea5e9', '#8b5cf6', '#ec4899'
];

let tbSavedSel = null;

tbColors.forEach(color => {
    const swatch = document.createElement('div');
    swatch.className = 'color-swatch';
    swatch.style.backgroundColor = color;

    swatch.addEventListener('click', (e) => {
        e.stopPropagation();
        e.preventDefault();

        if (tbSavedSel) {
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(tbSavedSel);
        }

        document.execCommand('foreColor', false, color);

        tbColorMenu.classList.remove('open');
        tbEditor.focus();
        tbUpdateOutput();
        tbSavedSel = null;
    });

    tbColorPalette.appendChild(swatch);
});

document.getElementById('tbColorBtn').addEventListener('mousedown', () => {
    const sel = window.getSelection();
    if (sel.rangeCount > 0) {
        tbSavedSel = sel.getRangeAt(0).cloneRange();
    }
});

document.getElementById('tbUlBtn').addEventListener('click', e => {
    e.stopPropagation();
    tbOlMenu.classList.remove('open');
    tbColorMenu.classList.remove('open');
    tbUlMenu.classList.toggle('open');
});

document.getElementById('tbOlBtn').addEventListener('click', e => {
    e.stopPropagation();
    tbUlMenu.classList.remove('open');
    tbColorMenu.classList.remove('open');
    tbOlMenu.classList.toggle('open');
});

document.getElementById('tbColorBtn').addEventListener('click', e => {
    e.stopPropagation();
    tbUlMenu.classList.remove('open');
    tbOlMenu.classList.remove('open');
    tbColorMenu.classList.toggle('open');
});

document.addEventListener('click', () => {
    tbUlMenu.classList.remove('open');
    tbOlMenu.classList.remove('open');
    tbColorMenu.classList.remove('open');
});

document.querySelectorAll('[data-list]').forEach(btn => {
    btn.addEventListener('mousedown', e => e.preventDefault());
    btn.addEventListener('click', e => {
        e.stopPropagation();
        tbEditor.focus();
        document.execCommand('insertUnorderedList', false, null);
        setTimeout(() => {
            const sel = window.getSelection();
            if (!sel.rangeCount) return;
            let node = sel.getRangeAt(0).commonAncestorContainer;
            node = node.nodeType === 3 ? node.parentNode : node;
            const ul = node.closest('ul');
            if (ul) {
                ul.querySelectorAll('li').forEach(li => {
                    li.style.listStyleType = btn.dataset.list;
                });
                ul.style.listStyleType = btn.dataset.list;
            }
            tbUpdateOutput();
        }, 0);
        tbUlMenu.classList.remove('open');
    });
});

document.querySelectorAll('[data-ol]').forEach(btn => {
    btn.addEventListener('mousedown', e => e.preventDefault());
    btn.addEventListener('click', e => {
        e.stopPropagation();
        tbEditor.focus();
        document.execCommand('insertOrderedList', false, null);
        setTimeout(() => {
            const sel = window.getSelection();
            if (!sel.rangeCount) return;
            let node = sel.getRangeAt(0).commonAncestorContainer;
            node = node.nodeType === 3 ? node.parentNode : node;
            const ol = node.closest('ol');
            if (ol) {
                ol.style.listStyleType = btn.dataset.ol;
            }
            tbUpdateOutput();
        }, 0);
        tbOlMenu.classList.remove('open');
    });
});

tbEditor.addEventListener('keydown', (e) => {
    if (e.key === 'Tab') {
        e.preventDefault();
        document.execCommand(e.shiftKey ? 'outdent' : 'indent', false, null);
        tbUpdateOutput();
    }
});

function tbUpdateOutput() {
    const html = tbEditor.innerHTML.trim();
    const value = (html === '<br>' || html === '') ? '' : html;
    tbOutput.textContent = value;
    $('#body_input').val(value);
}

tbEditor.addEventListener('input', tbUpdateOutput);

document.getElementById('tbCopyBtn').addEventListener('click', () => {
    const text = tbOutput.textContent;
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('tbCopyBtn');
        btn.innerHTML = 'Copied!';
        setTimeout(() => btn.innerHTML = 'Copy', 1500);
    });
});

lucide.createIcons();
</script>