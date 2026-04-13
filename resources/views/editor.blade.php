<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rich Text Editor</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&amp;family=JetBrains+Mono:wght@400;500&amp;display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body { font-family: 'DM Sans', sans-serif; margin: 0; }

    .toolbar-btn {
      width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
      border-radius: 6px; border: none; cursor: pointer; transition: all 0.15s;
      background: transparent; color: #64748b; position: relative;
    }
    .toolbar-btn:hover { background: #f1f5f9; color: #1e293b; }
    .toolbar-btn.active { background: #e0e7ff; color: #4338ca; }

    .separator { width: 1px; height: 24px; background: #e2e8f0; margin: 0 4px; flex-shrink: 0; }

    #editor {
      min-height: 280px; outline: none; padding: 20px 24px; font-size: 15px;
      line-height: 1.7; color: #1e293b; caret-color: #4338ca;
    }
    #editor:empty::before {
      content: attr(data-placeholder); color: #94a3b8; pointer-events: none;
    }
    #editor ul, #editor ol { padding-left: 24px; margin: 8px 0; }
    #editor ul li, #editor ol li { margin: 2px 0; }

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
      max-height: 200px; overflow: auto; white-space: pre-wrap; word-break: break-all;
    }
  </style>
  <style>body { box-sizing: border-box; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>

 <body class="h-full">


  <div id="app" class="h-full w-full overflow-auto" style="background: #f8fafc; padding: 24px;">
   <div style="max-width: 760px; margin: 0 auto;">
    <h1 id="editor-title" style="font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 20px;">Rich Text Editor</h1>
    <div class="editor-wrap">
     <!-- Toolbar -->
     <div style="display: flex; align-items: center; gap: 2px; padding: 8px 10px; background: #fafbfc; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap;">
      <button class="toolbar-btn" data-cmd="bold" title="Bold"><i data-lucide="bold" style="width:16px;height:16px;"></i></button> <button class="toolbar-btn" data-cmd="italic" title="Italic"><i data-lucide="italic" style="width:16px;height:16px;"></i></button> <button class="toolbar-btn" data-cmd="underline" title="Underline"><i data-lucide="underline" style="width:16px;height:16px;"></i></button> <button class="toolbar-btn" data-cmd="strikeThrough" title="Strikethrough"><i data-lucide="strikethrough" style="width:16px;height:16px;"></i></button>
      <div class="separator"></div><!-- Bullet List Dropdown -->
      <div class="dropdown">
       <button class="toolbar-btn" id="ulBtn" title="Bullet List"><i data-lucide="list" style="width:16px;height:16px;"></i></button>
       <div class="dropdown-menu" id="ulMenu">
        <button class="dropdown-item" data-list="disc"><span>● Disc</span><span class="preview">default</span></button> <button class="dropdown-item" data-list="circle"><span>○ Circle</span><span class="preview">outline</span></button> <button class="dropdown-item" data-list="square"><span>■ Square</span><span class="preview">filled</span></button>
       </div>
      </div><!-- Ordered List Dropdown -->
      <div class="dropdown">
       <button class="toolbar-btn" id="olBtn" title="Ordered List"><i data-lucide="list-ordered" style="width:16px;height:16px;"></i></button>
       <div class="dropdown-menu" id="olMenu">
        <button class="dropdown-item" data-ol="decimal"><span>1, 2, 3</span><span class="preview">numbers</span></button> <button class="dropdown-item" data-ol="lower-alpha"><span>a, b, c</span><span class="preview">lowercase</span></button> <button class="dropdown-item" data-ol="upper-alpha"><span>A, B, C</span><span class="preview">uppercase</span></button> <button class="dropdown-item" data-ol="lower-roman"><span>i, ii, iii</span><span class="preview">roman</span></button> <button class="dropdown-item" data-ol="upper-roman"><span>I, II, III</span><span class="preview">Roman</span></button>
       </div>
      </div>
      <div class="separator"></div><button class="toolbar-btn" data-cmd="indent" title="Indent">
       <svg width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="8 6 4 12 8 18"></polyline><line x1="4" y1="12" x2="21" y2="12"></line>
       </svg></button> 
       
       
       
       <button class="toolbar-btn" data-cmd="outdent" title="Outdent">
       <svg width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 6 20 12 16 18"></polyline><line x1="4" y1="12" x2="20" y2="12"></line>
       </svg></button>
      <div class="separator"></div><!-- Text Color Dropdown -->
      <div class="dropdown">
       <button class="toolbar-btn" id="colorBtn" title="Text Color"><i data-lucide="palette" style="width:16px;height:16px;"></i></button>
       <div class="dropdown-menu" id="colorMenu" style="min-width: 220px; padding: 0;">
        <div class="color-palette" id="colorPalette"></div>
       </div>
      </div>
      <div class="separator"></div><button class="toolbar-btn" data-cmd="removeFormat" title="Clear Formatting"><i data-lucide="eraser" style="width:16px;height:16px;"></i></button>
     </div>
     
     <!-- Editor -->
     <div id="editor" contenteditable="true" data-placeholder="Start typing here..."></div>
    </div>
    
    
    <!-- HTML Output -->
    <div style="margin-top: 20px;">
     <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
      <span style="font-size: 13px; font-weight: 600; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase;">HTML Output</span> <button id="copyBtn" style="font-size: 12px; font-weight: 500; color: #4338ca; background: #e0e7ff; border: none; padding: 5px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 4px; font-family: inherit;"> <i data-lucide="copy" style="width:13px;height:13px;"></i> Copy </button>
     </div>
     <div class="html-output" id="htmlOutput"><!-- HTML will appear here -->
     </div>
    </div>
   </div>
  </div>

  
  <script>
    const editor = document.getElementById('editor');
    const htmlOutput = document.getElementById('htmlOutput');
    const ulMenu = document.getElementById('ulMenu');
    const olMenu = document.getElementById('olMenu');
    const colorMenu = document.getElementById('colorMenu');
    const colorPalette = document.getElementById('colorPalette');

    // Color palette
    const colors = [
      '#000000', '#ef4444', '#f97316', '#eab308',
      '#22c55e', '#0ea5e9', '#8b5cf6', '#ec4899'
    ];

    let savedSelection = null;

    colors.forEach(color => {
      const swatch = document.createElement('div');
      swatch.className = 'color-swatch';
      swatch.style.backgroundColor = color;
      swatch.dataset.color = color;
      swatch.addEventListener('click', (e) => {
        e.stopPropagation();
        e.preventDefault();
        
        if (savedSelection) {
          window.getSelection().removeAllRanges();
          window.getSelection().addRange(savedSelection);
        }
        
        const sel = window.getSelection();
        if (sel.toString().length > 0) {
          document.execCommand('foreColor', false, color);
        }
        
        colorMenu.classList.remove('open');
        editor.focus();
        updateOutput();
        savedSelection = null;
      });
      colorPalette.appendChild(swatch);
    });

    // Save selection before opening color menu
    document.getElementById('colorBtn').addEventListener('mousedown', () => {
      const sel = window.getSelection();
      if (sel.rangeCount > 0) {
        savedSelection = sel.getRangeAt(0).cloneRange();
      }
    });

    // Simple commands
    document.querySelectorAll('[data-cmd]').forEach(btn => {
      btn.addEventListener('mousedown', e => e.preventDefault());
      btn.addEventListener('click', () => {
        document.execCommand(btn.dataset.cmd, false, null);
        editor.focus();
        updateToolbarState();
        updateOutput();
      });
    });

    // Dropdown toggles
    document.getElementById('ulBtn').addEventListener('click', e => {
      e.stopPropagation();
      olMenu.classList.remove('open');
      colorMenu.classList.remove('open');
      ulMenu.classList.toggle('open');
    });
    document.getElementById('olBtn').addEventListener('click', e => {
      e.stopPropagation();
      ulMenu.classList.remove('open');
      colorMenu.classList.remove('open');
      olMenu.classList.toggle('open');
    });
    document.getElementById('colorBtn').addEventListener('click', e => {
      e.stopPropagation();
      ulMenu.classList.remove('open');
      olMenu.classList.remove('open');
      colorMenu.classList.toggle('open');
    });
    document.addEventListener('click', () => {
      ulMenu.classList.remove('open');
      olMenu.classList.remove('open');
      colorMenu.classList.remove('open');
    });

    // Unordered list style
    document.querySelectorAll('[data-list]').forEach(btn => {
      btn.addEventListener('mousedown', e => e.preventDefault());
      btn.addEventListener('click', e => {
        e.stopPropagation();
        document.execCommand('insertUnorderedList', false, null);
        
        // Find the UL that contains the current cursor
        const sel = window.getSelection();
        if (sel.rangeCount > 0) {
          const range = sel.getRangeAt(0);
          let node = range.commonAncestorContainer;
          let liNode = node.nodeType === 3 ? node.parentNode : node;
          while (liNode && liNode !== editor && liNode.tagName !== 'LI') {
            liNode = liNode.parentNode;
          }
          if (liNode && liNode.tagName === 'LI') {
            const ulNode = liNode.closest('ul');
            if (ulNode) {
              ulNode.style.listStyleType = btn.dataset.list;
            }
          }
        }
        
        ulMenu.classList.remove('open');
        editor.focus();
        updateOutput();
      });
    });

    // Ordered list style
    document.querySelectorAll('[data-ol]').forEach(btn => {
      btn.addEventListener('mousedown', e => e.preventDefault());
      btn.addEventListener('click', e => {
        e.stopPropagation();
        document.execCommand('insertOrderedList', false, null);
        
        // Find the OL that contains the current cursor
        const sel = window.getSelection();
        if (sel.rangeCount > 0) {
          const range = sel.getRangeAt(0);
          let node = range.commonAncestorContainer;
          let liNode = node.nodeType === 3 ? node.parentNode : node;
          while (liNode && liNode !== editor && liNode.tagName !== 'LI') {
            liNode = liNode.parentNode;
          }
          if (liNode && liNode.tagName === 'LI') {
            const olNode = liNode.closest('ol');
            if (olNode) {
              olNode.style.listStyleType = btn.dataset.ol;
            }
          }
        }
        
        olMenu.classList.remove('open');
        editor.focus();
        updateOutput();
      });
    });

    // Handle Tab key for nested lists
    editor.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        e.preventDefault();
        const sel = window.getSelection();
        if (sel.rangeCount > 0) {
          const range = sel.getRangeAt(0);
          let node = range.commonAncestorContainer;
          
          // Find the LI element we're in
          let liNode = node.nodeType === 3 ? node.parentNode : node;
          while (liNode && liNode !== editor && liNode.tagName !== 'LI') {
            liNode = liNode.parentNode;
          }
          
          if (liNode && liNode.tagName === 'LI') {
            if (e.shiftKey) {
              // Outdent: move list item up one level
              document.execCommand('outdent', false, null);
            } else {
              // Indent: create nested list
              document.execCommand('indent', false, null);
            }
            updateOutput();
            updateToolbarState();
          }
        }
      }
    });

    // Toolbar active state
    function updateToolbarState() {
      ['bold','italic','underline','strikeThrough'].forEach(cmd => {
        const btn = document.querySelector(`[data-cmd="${cmd}"]`);
        if (btn) btn.classList.toggle('active', document.queryCommandState(cmd));
      });
    }

    function updateOutput() {
      const html = editor.innerHTML.trim();
      htmlOutput.textContent = html === '<br>' || html === '' ? '' : html;
    }

    editor.addEventListener('input', updateOutput);
    editor.addEventListener('keyup', updateToolbarState);
    editor.addEventListener('mouseup', updateToolbarState);

    // Copy button
    document.getElementById('copyBtn').addEventListener('click', () => {
      const text = htmlOutput.textContent;
      if (!text) return;
      navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.innerHTML = '<i data-lucide="check" style="width:13px;height:13px;"></i> Copied!';
        lucide.createIcons();
        setTimeout(() => {
          btn.innerHTML = '<i data-lucide="copy" style="width:13px;height:13px;"></i> Copy';
          lucide.createIcons();
        }, 1500);
      });
    });

    // Element SDK
    const defaultConfig = {
      editor_title: 'Rich Text Editor',
      background_color: '#f8fafc',
      surface_color: '#ffffff',
      text_color: '#1e293b',
      accent_color: '#4338ca',
      muted_color: '#64748b',
      font_family: 'DM Sans',
      font_size: 15
    };

    function applyConfig(config) {
      const c = { ...defaultConfig, ...config };
      const app = document.getElementById('app');
      app.style.background = c.background_color;
      document.getElementById('editor-title').textContent = c.editor_title;
      document.getElementById('editor-title').style.color = c.text_color;
      editor.style.color = c.text_color;
      editor.style.fontSize = c.font_size + 'px';
      editor.style.caretColor = c.accent_color;
      const font = `${c.font_family}, DM Sans, sans-serif`;
      document.getElementById('editor-title').style.fontFamily = font;
      editor.style.fontFamily = font;
    }

    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange: async (config) => applyConfig(config),
        mapToCapabilities: (config) => {
          const c = { ...defaultConfig, ...config };
          const mut = (key) => ({
            get: () => c[key] || defaultConfig[key],
            set: (v) => { c[key] = v; window.elementSdk.setConfig({ [key]: v }); }
          });
          return {
            recolorables: [mut('background_color'), mut('surface_color'), mut('text_color'), mut('accent_color'), mut('muted_color')],
            borderables: [],
            fontEditable: mut('font_family'),
            fontSizeable: mut('font_size')
          };
        },
        mapToEditPanelValues: (config) => new Map([
          ['editor_title', config.editor_title || defaultConfig.editor_title]
        ])
      });
    }

    lucide.createIcons();
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9eaf914bc73d4ee4',t:'MTc3NTk2ODk4MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>