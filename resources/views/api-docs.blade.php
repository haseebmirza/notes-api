<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Notes API Docs</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=DM+Sans:wght@400;500;600&display=swap">
  <style>
  :root {
    --bg:        #0b0f1a;
    --surface:   #111827;
    --surface2:  #1a2236;
    --surface3:  #212d42;
    --border:    #1e2d45;
    --border2:   #263548;
    --accent:    #39d353;
    --accent-dim:#39d35314;
    --accent-mid:#39d35338;
    --text:      #e2e8f4;
    --text-2:    #8896b0;
    --text-3:    #4a5872;
    --get:       #60a5fa;
    --post:      #34d399;
    --del:       #f87171;
    --put:       #fbbf24;
    --get-bg:    rgba(96,165,250,0.1);
    --post-bg:   rgba(52,211,153,0.1);
    --del-bg:    rgba(248,113,113,0.1);
    --put-bg:    rgba(251,191,36,0.1);
    color-scheme: dark;
  }
  *,*::before,*::after{box-sizing:border-box;}
  body {
    margin:0;
    background:var(--bg);
    color:var(--text);
    font-family:'DM Sans',system-ui,sans-serif;
    font-size:15px;
    line-height:1.6;
    -webkit-font-smoothing:antialiased;
  }
  .mono{font-family:'JetBrains Mono','Courier New',monospace;}
  .app { display:flex; flex-direction:column; min-height:100%; }
  .topnav {
    position:sticky;
    top:env(safe-area-inset-top,0px);
    z-index:200;
    display:flex;
    align-items:center;
    gap:16px;
    padding:0 24px;
    height:52px;
    background:rgba(11,15,26,0.9);
    backdrop-filter:blur(12px);
    border-bottom:1px solid var(--border);
  }
  .topnav-logo {
    font-family:'JetBrains Mono',monospace;
    font-weight:700;font-size:.9rem;
    color:var(--text);text-decoration:none;
    display:flex;align-items:center;gap:6px;
  }
  .logo-cursor {
    display:inline-block;width:8px;height:16px;
    background:var(--accent);
    animation:blink 1.1s step-end infinite;
  }
  @keyframes blink{50%{opacity:0;}}
  .topnav-sep{width:1px;height:20px;background:var(--border);margin-inline:4px;}
  .topnav-title{font-size:.8rem;color:var(--text-3);}
  .topnav-right{margin-left:auto;display:flex;align-items:center;gap:16px;}
  .topnav-right a{font-size:.8rem;color:var(--text-2);text-decoration:none;transition:color .15s;}
  .topnav-right a:hover{color:var(--text);}
  .topnav-badge {
    font-family:'JetBrains Mono',monospace;
    font-size:.65rem;padding:3px 8px;
    border-radius:3px;background:var(--accent-dim);
    border:1px solid var(--accent-mid);color:var(--accent);
  }
  .body-split { display:flex; flex:1; }
  .sidebar {
    width:248px;flex-shrink:0;
    position:sticky;top:52px;
    height:calc(100vh - 52px);overflow-y:auto;
    border-right:1px solid var(--border);
    padding:24px 0 40px;
    scrollbar-width:thin;scrollbar-color:var(--border) transparent;
  }
  .sidebar-section-label {
    font-family:'JetBrains Mono',monospace;
    font-size:.62rem;font-weight:600;
    letter-spacing:.12em;text-transform:uppercase;
    color:var(--text-3);padding:0 20px 10px;
  }
  .sidebar-intro { margin-bottom:24px; }
  .sidebar-intro a {
    display:flex;align-items:center;gap:8px;
    padding:7px 20px;font-size:.82rem;color:var(--text-2);
    text-decoration:none;transition:color .12s,background .12s;
    border-left:2px solid transparent;
  }
  .sidebar-intro a:hover{color:var(--text);background:var(--surface2);}
  .sidebar-intro a.active{color:var(--accent);border-left-color:var(--accent);}
  .endpoint-item {
    display:flex;align-items:center;gap:10px;
    padding:8px 20px;cursor:pointer;
    transition:background .12s;
    border-left:2px solid transparent;user-select:none;
  }
  .endpoint-item:hover{background:var(--surface2);}
  .endpoint-item.active{background:var(--accent-dim);border-left-color:var(--accent);}
  .endpoint-item .ep-path {
    font-family:'JetBrains Mono',monospace;
    font-size:.73rem;color:var(--text-2);
    flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
  }
  .endpoint-item.active .ep-path{color:var(--text);}
  .method-pill {
    font-family:'JetBrains Mono',monospace;
    font-size:.58rem;font-weight:700;
    padding:2px 6px;border-radius:2px;
    letter-spacing:.04em;flex-shrink:0;min-width:38px;text-align:center;
  }
  .pill-get   {color:var(--get);  background:var(--get-bg);}
  .pill-post  {color:var(--post); background:var(--post-bg);}
  .pill-delete{color:var(--del);  background:var(--del-bg);}
  .pill-put   {color:var(--put);  background:var(--put-bg);}
  .main { flex:1; min-width:0; padding:0; }
  .panel { display:none; }
  .panel.active { display:block; }
  .intro-panel { padding:48px 48px 56px; max-width:780px; }
  .intro-panel h1 {
    font-family:'JetBrains Mono',monospace;
    font-size:2rem;font-weight:700;
    letter-spacing:-.02em;margin:0 0 16px;text-wrap:balance;
  }
  .intro-panel h1 span{color:var(--accent);}
  .intro-panel .lead{font-size:1rem;color:var(--text-2);line-height:1.75;max-width:58ch;margin:0 0 32px;}
  .base-url-box {
    display:inline-flex;align-items:center;gap:12px;
    background:var(--surface);border:1px solid var(--border);
    border-radius:6px;padding:12px 18px;margin-bottom:32px;
  }
  .base-url-label{font-family:'JetBrains Mono',monospace;font-size:.68rem;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;}
  .base-url-val{font-family:'JetBrains Mono',monospace;font-size:.85rem;color:var(--accent);}
  .intro-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-top:8px;}
  .intro-card{background:var(--surface);border:1px solid var(--border);border-radius:6px;padding:20px;}
  .intro-card h3{font-family:'JetBrains Mono',monospace;font-size:.85rem;font-weight:600;margin:0 0 8px;}
  .intro-card p{font-size:.82rem;color:var(--text-2);margin:0;line-height:1.6;}
  .format-pills{display:flex;gap:8px;margin-top:24px;flex-wrap:wrap;}
  .format-pill{font-family:'JetBrains Mono',monospace;font-size:.68rem;padding:4px 10px;border-radius:3px;border:1px solid var(--border);background:var(--surface2);color:var(--text-2);}
  .ep-panel { display:grid;grid-template-columns:1fr 420px;min-height:calc(100vh - 52px);align-items:start; }
  .ep-left { padding:40px 40px 60px;border-right:1px solid var(--border);min-width:0; }
  .ep-right {
    position:sticky;top:52px;max-height:calc(100vh - 52px);overflow-y:auto;
    padding:24px 24px 40px;background:var(--surface);
    scrollbar-width:thin;scrollbar-color:var(--border) transparent;
  }
  .ep-header{margin-bottom:28px;}
  .ep-method-path{display:flex;align-items:center;gap:12px;margin-bottom:12px;flex-wrap:wrap;}
  .ep-method-badge {font-family:'JetBrains Mono',monospace;font-size:.8rem;font-weight:700;padding:5px 12px;border-radius:4px;letter-spacing:.04em;}
  .badge-get   {color:var(--get);  background:var(--get-bg);  border:1px solid rgba(96,165,250,.2);}
  .badge-post  {color:var(--post); background:var(--post-bg); border:1px solid rgba(52,211,153,.2);}
  .badge-delete{color:var(--del);  background:var(--del-bg);  border:1px solid rgba(248,113,113,.2);}
  .badge-put   {color:var(--put);  background:var(--put-bg);  border:1px solid rgba(251,191,36,.2);}
  .ep-path-text{font-family:'JetBrains Mono',monospace;font-size:1.1rem;font-weight:600;color:var(--text);}
  .ep-path-text .param{color:var(--text-2);}
  .ep-description{color:var(--text-2);font-size:.9rem;line-height:1.7;max-width:54ch;}
  .ep-section{margin-bottom:28px;}
  .ep-section-title{
    font-family:'JetBrains Mono',monospace;font-size:.65rem;font-weight:600;
    letter-spacing:.12em;text-transform:uppercase;color:var(--text-3);
    margin:0 0 12px;padding-bottom:8px;border-bottom:1px solid var(--border);
  }
  .param-table{width:100%;border-collapse:collapse;}
  .param-table tr{border-bottom:1px solid var(--border);}
  .param-table tr:last-child{border-bottom:none;}
  .param-table td{padding:10px 8px;font-size:.82rem;vertical-align:top;}
  .param-name{font-family:'JetBrains Mono',monospace;font-size:.78rem;color:var(--text);white-space:nowrap;}
  .param-required{font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--del);padding:1px 5px;border-radius:2px;background:var(--del-bg);margin-left:6px;}
  .param-optional{font-family:'JetBrains Mono',monospace;font-size:.6rem;color:var(--text-3);padding:1px 5px;border-radius:2px;background:var(--surface3);margin-left:6px;}
  .param-type{font-family:'JetBrains Mono',monospace;font-size:.7rem;color:var(--put);white-space:nowrap;}
  .param-desc{color:var(--text-2);font-size:.82rem;padding-left:8px;}
  .response-codes{display:flex;flex-direction:column;gap:8px;}
  .response-code{display:flex;align-items:flex-start;gap:12px;padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:5px;}
  .rcode{font-family:'JetBrains Mono',monospace;font-size:.75rem;font-weight:700;padding:2px 7px;border-radius:3px;flex-shrink:0;}
  .rcode-2xx{color:var(--post);background:var(--post-bg);}
  .rcode-4xx{color:var(--del);background:var(--del-bg);}
  .rcode-desc{font-size:.82rem;color:var(--text-2);}
  .right-section{margin-bottom:24px;}
  .right-label{
    font-family:'JetBrains Mono',monospace;font-size:.62rem;font-weight:600;
    letter-spacing:.1em;text-transform:uppercase;color:var(--text-3);margin:0 0 10px;
    display:flex;align-items:center;justify-content:space-between;
  }
  .copy-mini{
    font-family:'JetBrains Mono',monospace;font-size:.6rem;padding:3px 8px;
    border-radius:2px;border:1px solid var(--border);
    background:transparent;color:var(--text-3);cursor:pointer;transition:all .15s;
  }
  .copy-mini:hover{border-color:var(--accent);color:var(--accent);}
  .copy-mini:focus-visible{outline:2px solid var(--accent);outline-offset:2px;}
  .code-box{
    background:var(--bg);border:1px solid var(--border);border-radius:5px;
    font-family:'JetBrains Mono',monospace;font-size:.72rem;line-height:1.7;
    padding:16px;overflow-x:auto;white-space:pre;
  }
  .j-key{color:#93c5fd;}.j-str{color:#86efac;}.j-num{color:#fca5a5;}
  .j-bool{color:#c4b5fd;}.j-null{color:var(--text-3);}.j-punc{color:var(--text-3);}
  .curl-flag{color:#60a5fa;}.curl-url{color:var(--accent);}.curl-str{color:#fca5a5;}
  .sidebar-toggle{
    display:none;background:none;border:1px solid var(--border);
    color:var(--text-2);padding:5px 10px;border-radius:4px;cursor:pointer;
    font-family:'JetBrains Mono',monospace;font-size:.7rem;
  }
  @media(max-width:900px){
    .ep-panel{grid-template-columns:1fr;}
    .ep-right{position:static;max-height:none;border-top:1px solid var(--border);border-right:none;}
    .ep-left{border-right:none;padding:24px 20px 40px;}
  }
  @media(max-width:680px){
    .sidebar{position:fixed;top:52px;left:0;bottom:0;transform:translateX(-100%);transition:transform .2s;z-index:150;background:var(--bg);}
    .sidebar.open{transform:translateX(0);}
    .sidebar-toggle{display:block;}
    .main{width:100%;}
    .intro-grid{grid-template-columns:1fr;}
    .intro-panel{padding:28px 20px 40px;}
  }
  </style>
</head>
<body>

<div class="app">
<nav class="topnav">
  <a href="/" class="topnav-logo mono">notes-api<span class="logo-cursor"></span></a>
  <span class="topnav-sep"></span>
  <span class="topnav-title">Documentation</span>
  <div class="topnav-right">
    <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">☰ menu</button>
    <span class="topnav-badge">v1.0</span>
    <a href="/docs.postman" target="_blank" rel="noopener">Postman ↗</a>
    <a href="https://github.com/haseebmirza/notes-api" target="_blank" rel="noopener">GitHub ↗</a>
  </div>
</nav>

<div class="body-split">
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-intro">
      <p class="sidebar-section-label">Overview</p>
      <a href="#" class="active" onclick="showIntro(event)">Introduction</a>
    </div>
    <div>
      <p class="sidebar-section-label">Notes</p>
      <div id="ep-list"></div>
    </div>
  </aside>

  <main class="main" id="main">
    <div class="panel active" id="panel-intro">
      <div class="intro-panel">
        <h1>Notes API <span>docs.</span></h1>
        <p class="lead">
          A clean REST API built on Laravel 13. Create and manage notes, attach files to S3, search across content, soft-delete and restore. All responses are JSON.
        </p>
        <div class="base-url-box">
          <span class="base-url-label">Base URL</span>
          <span class="base-url-val">{{ url('/') }}</span>
        </div>
        <div class="intro-grid">
          <div class="intro-card">
            <h3>Authentication</h3>
            <p>No authentication required. This is an open API — all endpoints are public.</p>
          </div>
          <div class="intro-card">
            <h3>Response format</h3>
            <p>All responses return <code class="mono" style="font-size:.78rem;color:var(--accent)">application/json</code>. Resources are wrapped in a <code class="mono" style="font-size:.78rem;color:var(--accent)">data</code> key.</p>
          </div>
          <div class="intro-card">
            <h3>File uploads</h3>
            <p>Use <code class="mono" style="font-size:.78rem;color:var(--accent)">multipart/form-data</code> for any endpoint that accepts a file. Accepted: jpg, png, pdf. Max 5 MB.</p>
          </div>
          <div class="intro-card">
            <h3>Pagination</h3>
            <p>List endpoints return 15 items per page. Use <code class="mono" style="font-size:.78rem;color:var(--accent)">?page=2</code> to paginate. Meta includes total, per_page, last_page.</p>
          </div>
        </div>
        <div class="format-pills" style="margin-top:28px;">
          <span class="format-pill">Laravel 13</span>
          <span class="format-pill">PHP 8.5</span>
          <span class="format-pill">MySQL · RDS</span>
          <span class="format-pill">AWS S3</span>
          <span class="format-pill">EC2 · eu-north-1</span>
        </div>
      </div>
    </div>

    <div id="ep-panels"></div>
  </main>
</div>
</div>

<script>
const BASE = '{{ url('/') }}';

const endpoints = [
  {
    id: 'list-notes',
    method: 'GET',
    path: '/api/notes',
    title: 'List notes',
    description: 'Returns a paginated list of all notes, newest first. Use the search parameter to filter by title or body content.',
    queryParams: [
      { name: 'search', type: 'string',  required: false, desc: 'Filter notes whose title or body contains this text.' },
      { name: 'page',   type: 'integer', required: false, desc: 'Page number. Defaults to 1. Returns 15 items per page.' },
    ],
    bodyParams: [],
    responses: [
      { code: '200', label: 'OK', desc: 'Paginated list of notes with meta.' },
    ],
    curl: `curl \${BASE}/api/notes`,
    curlSearch: `curl "\${BASE}/api/notes?search=meeting"`,
    example: {
      data: [
        { id: 1, title: "Meeting notes", body: "Discussed Q4 targets.", file_url: null, deleted_at: null, created_at: "2026-09-30T06:00:00Z", updated_at: "2026-09-30T06:00:00Z" }
      ],
      links: { first: `\${BASE}/api/notes?page=1`, last: `\${BASE}/api/notes?page=1`, prev: null, next: null },
      meta: { current_page: 1, last_page: 1, per_page: 15, total: 1 }
    }
  },
  {
    id: 'create-note',
    method: 'POST',
    path: '/api/notes',
    title: 'Create note',
    description: 'Creates a new note. Optionally attach a file which will be stored on S3 at notes/{id}/filename.',
    bodyParams: [
      { name: 'title', type: 'string',  required: true,  desc: 'The note title. Max 255 characters.' },
      { name: 'body',  type: 'string',  required: true,  desc: 'The note body content.' },
      { name: 'file',  type: 'file',    required: false, desc: 'File attachment. jpg, png, or pdf. Max 5 MB.' },
    ],
    responses: [
      { code: '201', label: 'Created', desc: 'Note created successfully.' },
      { code: '422', label: 'Unprocessable', desc: 'Validation failed — title or body missing, or invalid file.' },
    ],
    curl: `curl -X POST \${BASE}/api/notes \\\n  -H "Content-Type: application/json" \\\n  -d '{"title":"Meeting notes","body":"Q4 discussion."}'`,
    example: {
      data: { id: 1, title: "Meeting notes", body: "Q4 discussion.", file_url: null, deleted_at: null, created_at: "2026-09-30T06:00:00Z", updated_at: "2026-09-30T06:00:00Z" }
    }
  },
  {
    id: 'get-note',
    method: 'GET',
    path: '/api/notes/{id}',
    title: 'Get note',
    description: 'Returns a single note by its ID.',
    urlParams: [
      { name: 'id', type: 'integer', required: true, desc: 'The ID of the note.' },
    ],
    bodyParams: [],
    responses: [
      { code: '200', label: 'OK', desc: 'The note resource.' },
      { code: '404', label: 'Not Found', desc: 'Note does not exist or has been soft-deleted.' },
    ],
    curl: `curl \${BASE}/api/notes/1`,
    example: {
      data: { id: 1, title: "Meeting notes", body: "Q4 discussion.", file_url: null, deleted_at: null, created_at: "2026-09-30T06:00:00Z", updated_at: "2026-09-30T06:00:00Z" }
    }
  },
  {
    id: 'update-note',
    method: 'PUT',
    path: '/api/notes/{id}',
    title: 'Update note',
    description: 'Updates a note. All fields are optional — send only what you want to change. Uploading a new file replaces the existing S3 file.',
    urlParams: [
      { name: 'id', type: 'integer', required: true, desc: 'The ID of the note to update.' },
    ],
    bodyParams: [
      { name: 'title', type: 'string', required: false, desc: 'New title. Max 255 characters.' },
      { name: 'body',  type: 'string', required: false, desc: 'New body content.' },
      { name: 'file',  type: 'file',   required: false, desc: 'Replacement file. Deletes the old S3 file automatically.' },
    ],
    responses: [
      { code: '200', label: 'OK', desc: 'Updated note resource.' },
      { code: '422', label: 'Unprocessable', desc: 'Validation failed.' },
      { code: '404', label: 'Not Found', desc: 'Note not found.' },
    ],
    curl: `curl -X PUT \${BASE}/api/notes/1 \\\n  -H "Content-Type: application/json" \\\n  -d '{"title":"Updated title"}'`,
    example: {
      data: { id: 1, title: "Updated title", body: "Q4 discussion.", file_url: null, deleted_at: null, created_at: "2026-09-30T06:00:00Z", updated_at: "2026-09-30T07:30:00Z" }
    }
  },
  {
    id: 'delete-note',
    method: 'DELETE',
    path: '/api/notes/{id}',
    title: 'Delete note',
    description: 'Soft-deletes a note. The note is hidden from list results but can be restored. The S3 file is not removed — use force-delete for that.',
    urlParams: [
      { name: 'id', type: 'integer', required: true, desc: 'The ID of the note to delete.' },
    ],
    bodyParams: [],
    responses: [
      { code: '204', label: 'No Content', desc: 'Note soft-deleted. No response body.' },
      { code: '404', label: 'Not Found', desc: 'Note not found.' },
    ],
    curl: `curl -X DELETE \${BASE}/api/notes/1`,
    example: null
  },
  {
    id: 'restore-note',
    method: 'POST',
    path: '/api/notes/{id}/restore',
    title: 'Restore note',
    description: 'Restores a previously soft-deleted note. The note becomes visible in list results again.',
    urlParams: [
      { name: 'id', type: 'integer', required: true, desc: 'The ID of the trashed note to restore.' },
    ],
    bodyParams: [],
    responses: [
      { code: '200', label: 'OK', desc: 'Restored note resource with deleted_at set to null.' },
      { code: '404', label: 'Not Found', desc: 'Note not found in trash.' },
    ],
    curl: `curl -X POST \${BASE}/api/notes/1/restore`,
    example: {
      data: { id: 1, title: "Meeting notes", body: "Q4 discussion.", file_url: null, deleted_at: null, created_at: "2026-09-30T06:00:00Z", updated_at: "2026-09-30T08:00:00Z" }
    }
  },
  {
    id: 'force-delete',
    method: 'DELETE',
    path: '/api/notes/{id}/force',
    title: 'Force delete',
    description: 'Permanently deletes a soft-deleted note and removes its S3 file. This action cannot be undone. The note must be soft-deleted first.',
    urlParams: [
      { name: 'id', type: 'integer', required: true, desc: 'The ID of the trashed note to permanently delete.' },
    ],
    bodyParams: [],
    responses: [
      { code: '204', label: 'No Content', desc: 'Note and S3 file permanently deleted.' },
      { code: '404', label: 'Not Found', desc: 'Note not found in trash.' },
    ],
    curl: `curl -X DELETE \${BASE}/api/notes/1/force`,
    example: null
  },
  {
    id: 'upload-file',
    method: 'POST',
    path: '/api/notes/{id}/file',
    title: 'Upload file',
    description: 'Uploads or replaces the file attachment for a note. The file is stored on S3 at notes/{id}/filename. Replaces any existing attachment.',
    urlParams: [
      { name: 'id', type: 'integer', required: true, desc: 'The ID of the note.' },
    ],
    bodyParams: [
      { name: 'file', type: 'file', required: true, desc: 'File to attach. jpg, png, or pdf. Max 5 MB. Use multipart/form-data.' },
    ],
    responses: [
      { code: '200', label: 'OK', desc: 'Updated note with the new file_url.' },
      { code: '422', label: 'Unprocessable', desc: 'File missing, wrong type, or exceeds 5 MB.' },
      { code: '404', label: 'Not Found', desc: 'Note not found.' },
    ],
    curl: `curl -X POST \${BASE}/api/notes/1/file \\\n  -F "file=@agenda.pdf"`,
    example: {
      data: { id: 1, title: "Meeting notes", body: "Q4 discussion.", file_url: "https://haseeb-notes-files.s3.eu-north-1.amazonaws.com/notes/1/agenda.pdf", deleted_at: null, created_at: "2026-09-30T06:00:00Z", updated_at: "2026-09-30T09:00:00Z" }
    }
  },
];

function methodClass(m) {
  return { GET:'get', POST:'post', DELETE:'delete', PUT:'put', PATCH:'put' }[m] || 'get';
}

function jsonHL(val, indent=0) {
  if (val === null) return `<span class="j-null">null</span>`;
  if (typeof val === 'boolean') return `<span class="j-bool">${val}</span>`;
  if (typeof val === 'number') return `<span class="j-num">${val}</span>`;
  if (typeof val === 'string') return `<span class="j-str">"${val}"</span>`;
  const pad = '  '.repeat(indent);
  const inner = '  '.repeat(indent+1);
  if (Array.isArray(val)) {
    if (val.length === 0) return `<span class="j-punc">[]</span>`;
    const items = val.map(v => inner + jsonHL(v, indent+1)).join(',\n');
    return `<span class="j-punc">[</span>\n${items}\n${pad}<span class="j-punc">]</span>`;
  }
  const entries = Object.entries(val).map(([k,v]) =>
    `${inner}<span class="j-key">"${k}"</span><span class="j-punc">: </span>${jsonHL(v, indent+1)}`
  ).join(',\n');
  return `<span class="j-punc">{</span>\n${entries}\n${pad}<span class="j-punc">}</span>`;
}

function curlHL(str) {
  return str
    .replace(/(curl)/g, '<span class="curl-flag">$1</span>')
    .replace(/(-X \w+|-H|-F|-d)/g, '<span class="curl-flag">$1</span>')
    .replace(/(https?:\/\/[^\s\\']+)/g, '<span class="curl-url">$1</span>')
    .replace(/'([^']*)'/g, `<span class="curl-str">'$1'</span>`);
}

function copyText(text, btn) {
  navigator.clipboard.writeText(text).then(() => {
    const orig = btn.textContent;
    btn.textContent = 'copied!';
    btn.style.color = 'var(--accent)';
    btn.style.borderColor = 'var(--accent)';
    setTimeout(() => { btn.textContent = orig; btn.style.color=''; btn.style.borderColor=''; }, 1800);
  }).catch(() => {});
}

const epList   = document.getElementById('ep-list');
const epPanels = document.getElementById('ep-panels');

endpoints.forEach(ep => {
  const mc = methodClass(ep.method);

  const item = document.createElement('div');
  item.className = 'endpoint-item';
  item.dataset.id = ep.id;
  item.innerHTML = `<span class="method-pill pill-${mc}">${ep.method}</span><span class="ep-path">${ep.path}</span>`;
  item.onclick = () => showEndpoint(ep.id);
  epList.appendChild(item);

  const urlSection = (ep.urlParams||[]).length ? `
    <div class="ep-section">
      <p class="ep-section-title">URL Parameters</p>
      <table class="param-table">
        ${(ep.urlParams||[]).map(p=>`
        <tr>
          <td><span class="param-name">{${p.name}}</span><span class="${p.required?'param-required':'param-optional'}">${p.required?'required':'optional'}</span></td>
          <td><span class="param-type">${p.type}</span></td>
          <td class="param-desc">${p.desc}</td>
        </tr>`).join('')}
      </table>
    </div>` : '';

  const querySection = (ep.queryParams||[]).length ? `
    <div class="ep-section">
      <p class="ep-section-title">Query Parameters</p>
      <table class="param-table">
        ${(ep.queryParams||[]).map(p=>`
        <tr>
          <td><span class="param-name">${p.name}</span><span class="${p.required?'param-required':'param-optional'}">${p.required?'required':'optional'}</span></td>
          <td><span class="param-type">${p.type}</span></td>
          <td class="param-desc">${p.desc}</td>
        </tr>`).join('')}
      </table>
    </div>` : '';

  const bodySection = ep.bodyParams.length ? `
    <div class="ep-section">
      <p class="ep-section-title">Request Body</p>
      <table class="param-table">
        ${ep.bodyParams.map(p=>`
        <tr>
          <td><span class="param-name">${p.name}</span><span class="${p.required?'param-required':'param-optional'}">${p.required?'required':'optional'}</span></td>
          <td><span class="param-type">${p.type}</span></td>
          <td class="param-desc">${p.desc}</td>
        </tr>`).join('')}
      </table>
    </div>` : '';

  const responseCodes = `
    <div class="ep-section">
      <p class="ep-section-title">Response Codes</p>
      <div class="response-codes">
        ${ep.responses.map(r=>`
        <div class="response-code">
          <span class="rcode ${r.code.startsWith('2')?'rcode-2xx':'rcode-4xx'}">${r.code}</span>
          <span class="rcode-desc"><strong>${r.label}</strong> — ${r.desc}</span>
        </div>`).join('')}
      </div>
    </div>`;

  const pathDisplay = ep.path.replace(/\{(\w+)\}/g, '<span class="param">{$1}</span>');
  const exampleJson = ep.example ? jsonHL(ep.example) : '<span class="j-null">// 204 No Content — empty response body</span>';
  const curlRaw = ep.curl.replace(/<[^>]+>/g,'');
  const curlSecondary = ep.curlSearch ? `
    <div class="right-section" style="margin-top:16px;">
      <p class="right-label">With search<button class="copy-mini" data-copy="${ep.curlSearch.replace(/"/g,'&quot;')}" onclick="copyText(this.dataset.copy,this)">copy</button></p>
      <div class="code-box">${curlHL(ep.curlSearch)}</div>
    </div>` : '';

  const panel = document.createElement('div');
  panel.className = 'panel';
  panel.id = `panel-${ep.id}`;
  panel.innerHTML = `
    <div class="ep-panel">
      <div class="ep-left">
        <div class="ep-header">
          <div class="ep-method-path">
            <span class="ep-method-badge badge-${mc}">${ep.method}</span>
            <span class="ep-path-text">${pathDisplay}</span>
          </div>
          <p class="ep-description">${ep.description}</p>
        </div>
        ${urlSection}
        ${querySection}
        ${bodySection}
        ${responseCodes}
      </div>
      <div class="ep-right">
        <div class="right-section">
          <p class="right-label">Request<button class="copy-mini" onclick="copyText(${JSON.stringify(curlRaw)},this)">copy</button></p>
          <div class="code-box">${curlHL(ep.curl)}</div>
        </div>
        ${curlSecondary}
        <div class="right-section">
          <p class="right-label">Response</p>
          <div class="code-box">${exampleJson}</div>
        </div>
      </div>
    </div>`;
  epPanels.appendChild(panel);
});

function hideAll() {
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.endpoint-item').forEach(i => i.classList.remove('active'));
  document.querySelectorAll('.sidebar-intro a').forEach(a => a.classList.remove('active'));
}

function showIntro(e) {
  if (e) e.preventDefault();
  hideAll();
  document.getElementById('panel-intro').classList.add('active');
  document.querySelector('.sidebar-intro a').classList.add('active');
  closeSidebar();
}

function showEndpoint(id) {
  hideAll();
  const panel = document.getElementById(`panel-${id}`);
  if (panel) panel.classList.add('active');
  const item = document.querySelector(`.endpoint-item[data-id="${id}"]`);
  if (item) item.classList.add('active');
  closeSidebar();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
}
</script>
</body>
</html>
