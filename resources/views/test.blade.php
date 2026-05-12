
  <style>

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
    <style>body { box-sizing: border-box; }</style>
    <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  </head>
  <body class="h-full">
    <div id="app" class="h-full w-full flex items-center justify-center bg-slate-50 p-6">
    <div class="text-center max-w-sm">
      <!-- Illustration -->
      <div class="float-anim fade-in mb-6 mx-auto w-28 h-28 rounded-full bg-indigo-50 flex items-center justify-center border-2 border-dashed border-indigo-200"><i data-lucide="inbox" style="width:48px;height:48px;color:#6366f1;"></i>
      </div><!-- Text -->
      <h3 id="empty-title" class="fade-in-delay text-lg font-semibold text-slate-800 mb-2">No programs found</h3>
      <p id="empty-message" class="fade-in-delay-2 text-sm text-slate-500 mb-6">Get started by creating your first program. It only takes a moment.</p><!-- CTA Button --> <button id="cta-btn" class="fade-in-delay-2 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm"> <i data-lucide="plus" style="width:16px;height:16px;"></i> <span id="cta-text">Create Program</span> </button>
    </div>
    </div>

    
    <script>
          lucide.createIcons();
  </script>