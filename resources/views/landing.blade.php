<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Notes API</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=DM+Sans:wght@400;500;600&display=swap">
  <style>
  :root {
    --bg:        #0b0f1a;
    --surface:   #111827;
    --surface2:  #1a2236;
    --border:    #1e2d45;
    --accent:    #39d353;
    --accent-dim:#39d35318;
    --accent-mid:#39d35340;
    --text:      #e2e8f4;
    --text-2:    #8896b0;
    --text-3:    #4a5568;
    --get:       #60a5fa;
    --post:      #34d399;
    --del:       #f87171;
    --put:       #fbbf24;
    color-scheme: dark;
  }
  *, *::before, *::after { box-sizing: border-box; }
  body {
    margin: 0;
    background: var(--bg);
    color: var(--text);
    font-family: 'DM Sans', system-ui, sans-serif;
    font-size: 16px;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
  }
  .mono { font-family: 'JetBrains Mono', 'Courier New', monospace; }
  nav {
    position: sticky;
    top: env(safe-area-inset-top, 0px);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-block: 16px;
    padding-inline: 32px;
    background: rgba(11,15,26,0.85);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
  }
  .nav-logo {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    font-size: 1rem;
    color: var(--text);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .nav-logo .cursor {
    display: inline-block;
    width: 9px;
    height: 18px;
    background: var(--accent);
    animation: blink 1.1s step-end infinite;
  }
  @keyframes blink { 50% { opacity: 0; } }
  .nav-links {
    display: flex;
    align-items: center;
    gap: 24px;
    list-style: none;
    margin: 0; padding: 0;
  }
  .nav-links a {
    color: var(--text-2);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.15s;
  }
  .nav-links a:hover { color: var(--text); }
  .nav-links .btn-nav {
    color: var(--accent);
    border: 1px solid var(--accent-mid);
    padding: 6px 14px;
    border-radius: 4px;
    transition: background 0.15s;
  }
  .nav-links .btn-nav:hover { background: var(--accent-dim); color: var(--accent); }
  .wrap {
    max-width: 1120px;
    margin-inline: auto;
    padding-inline: 32px;
  }
  .hero { padding-block: 80px 72px; }
  .hero-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 56px;
    align-items: center;
  }
  .hero-eyebrow {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    color: var(--accent);
    text-transform: uppercase;
    margin-bottom: 20px;
  }
  .hero h1 {
    font-family: 'JetBrains Mono', monospace;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    line-height: 1.15;
    margin: 0 0 20px;
    text-wrap: balance;
    letter-spacing: -0.02em;
  }
  .hero h1 span { color: var(--accent); }
  .hero-desc {
    color: var(--text-2);
    font-size: 1.0625rem;
    line-height: 1.7;
    max-width: 46ch;
    margin: 0 0 32px;
  }
  .hero-ctas {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 36px;
  }
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    border-radius: 5px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s;
    cursor: pointer;
    border: none;
  }
  .btn-primary { background: var(--accent); color: #0b0f1a; }
  .btn-primary:hover { background: #4fe668; }
  .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
  .btn-secondary:hover { border-color: var(--text-3); }
  .badges { display: flex; gap: 8px; flex-wrap: wrap; }
  .badge {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 3px;
    background: var(--surface2);
    border: 1px solid var(--border);
    color: var(--text-2);
    letter-spacing: 0.03em;
  }
  .terminal {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.78rem;
    line-height: 1.65;
    box-shadow: 0 24px 64px rgba(0,0,0,0.5);
  }
  .terminal-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
  }
  .dot { width: 10px; height: 10px; border-radius: 50%; }
  .dot-r { background: #ff5f57; }
  .dot-y { background: #febc2e; }
  .dot-g { background: #28c840; }
  .terminal-title { font-size: 0.72rem; color: var(--text-3); margin-left: auto; letter-spacing: 0.03em; }
  .terminal-body { padding: 20px; min-height: 320px; }
  .t-prompt { color: var(--accent); }
  .t-cmd { color: var(--text); }
  .t-key { color: #60a5fa; }
  .t-str { color: #fbbf24; }
  .t-num { color: #f87171; }
  .t-null { color: var(--text-3); }
  .t-comment { color: var(--text-3); }
  .t-cursor {
    display: inline-block;
    width: 7px;
    height: 14px;
    background: var(--accent);
    vertical-align: text-bottom;
    animation: blink 1.1s step-end infinite;
  }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 0; }
  .features { padding-block: 72px; }
  .section-label {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 12px;
  }
  .section-title {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.6rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    text-wrap: balance;
    margin: 0 0 48px;
  }
  .feature-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    background: var(--border);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
  }
  .feature-card { background: var(--surface); padding: 32px 28px; }
  .feature-icon {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    color: var(--accent);
    background: var(--accent-dim);
    border: 1px solid var(--accent-mid);
    padding: 5px 10px;
    border-radius: 3px;
    display: inline-block;
    margin-bottom: 20px;
    letter-spacing: 0.05em;
  }
  .feature-card h3 {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 12px;
    letter-spacing: -0.01em;
  }
  .feature-card p { color: var(--text-2); font-size: 0.9rem; margin: 0; line-height: 1.65; }
  .endpoints { padding-block: 72px; }
  .endpoint-table { width: 100%; border-collapse: collapse; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
  .endpoint-table thead tr { background: var(--surface2); }
  .endpoint-table th {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--text-3);
    padding: 12px 20px;
    text-align: left;
    border-bottom: 1px solid var(--border);
  }
  .endpoint-table td { padding: 14px 20px; font-size: 0.875rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
  .endpoint-table tr:last-child td { border-bottom: none; }
  .endpoint-table tbody tr { background: var(--surface); transition: background 0.1s; }
  .endpoint-table tbody tr:hover { background: var(--surface2); }
  .method {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 3px;
    letter-spacing: 0.05em;
  }
  .method-get    { color: var(--get);  background: rgba(96,165,250,0.12); }
  .method-post   { color: var(--post); background: rgba(52,211,153,0.12); }
  .method-delete { color: var(--del);  background: rgba(248,113,113,0.12); }
  .method-put    { color: var(--put);  background: rgba(251,191,36,0.12); }
  .path { font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; color: var(--text); }
  .endpoint-desc { color: var(--text-2); }
  .quickstart { padding-block: 72px; }
  .code-block { background: var(--surface); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
  .code-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
  }
  .code-lang { font-family: 'JetBrains Mono', monospace; font-size: 0.68rem; color: var(--text-3); letter-spacing: 0.08em; text-transform: uppercase; }
  .copy-btn {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    padding: 4px 12px;
    border-radius: 3px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-2);
    cursor: pointer;
    transition: all 0.15s;
    letter-spacing: 0.03em;
  }
  .copy-btn:hover { border-color: var(--accent); color: var(--accent); }
  .copy-btn:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
  code.block {
    display: block;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.82rem;
    line-height: 1.75;
    padding: 24px;
    overflow-x: auto;
    color: var(--text);
  }
  .hl-flag  { color: #60a5fa; }
  .hl-url   { color: var(--accent); }
  .hl-key   { color: var(--put); }
  .hl-val   { color: #fca5a5; }
  footer { border-top: 1px solid var(--border); padding-block: 32px; }
  .footer-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
  .footer-left { font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; color: var(--text-3); }
  .footer-left span { color: var(--accent); }
  .footer-links { display: flex; gap: 20px; list-style: none; margin: 0; padding: 0; }
  .footer-links a { font-size: 0.8rem; color: var(--text-3); text-decoration: none; transition: color 0.15s; }
  .footer-links a:hover { color: var(--text-2); }
  @media (max-width: 768px) {
    nav { padding-inline: 16px; }
    .nav-links { gap: 14px; }
    .nav-links li:not(:last-child) { display: none; }
    .wrap { padding-inline: 16px; }
    .hero { padding-block: 48px 40px; }
    .hero-inner { grid-template-columns: 1fr; gap: 36px; }
    .feature-grid { grid-template-columns: 1fr; }
    .endpoint-table th:last-child, .endpoint-table td:last-child { display: none; }
    .footer-inner { flex-direction: column; align-items: flex-start; }
  }
  @media (prefers-reduced-motion: reduce) {
    .t-cursor, .nav-logo .cursor { animation: none; }
  }
  </style>
</head>
<body>

<nav>
  <a href="/" class="nav-logo mono">notes-api<span class="cursor"></span></a>
  <ul class="nav-links">
    <li><a href="#endpoints">Endpoints</a></li>
    <li><a href="#quickstart">Quick Start</a></li>
    <li><a href="https://github.com/haseebmirza/notes-api" target="_blank" rel="noopener">GitHub</a></li>
    <li><a href="/api-docs" class="btn-nav">Docs →</a></li>
  </ul>
</nav>

<section class="hero">
  <div class="wrap">
    <div class="hero-inner">
      <div>
        <p class="hero-eyebrow">REST API · Laravel 13</p>
        <h1>Notes.<br><span>Built to be</span><br>consumed.</h1>
        <p class="hero-desc">A clean REST API for creating, managing, and attaching files to notes. Paginated lists, search, soft deletes, S3 file storage — all in JSON.</p>
        <div class="hero-ctas">
          <a href="/api-docs" class="btn btn-primary">View Docs</a>
          <a href="https://github.com/haseebmirza/notes-api" target="_blank" rel="noopener" class="btn btn-secondary">GitHub ↗</a>
        </div>
        <div class="badges">
          <span class="badge">Laravel 13</span>
          <span class="badge">PHP 8.5</span>
          <span class="badge">MySQL · RDS</span>
          <span class="badge">AWS S3</span>
          <span class="badge">EC2</span>
        </div>
      </div>
      <div class="terminal" aria-label="Live API demo terminal">
        <div class="terminal-bar">
          <span class="dot dot-r"></span>
          <span class="dot dot-y"></span>
          <span class="dot dot-g"></span>
          <span class="terminal-title">notes-api — bash</span>
        </div>
        <div class="terminal-body" id="terminal-body"></div>
      </div>
    </div>
  </div>
</section>

<hr class="section-divider">

<section class="features" id="features">
  <div class="wrap">
    <p class="section-label">What it does</p>
    <h2 class="section-title">Everything you need,<br>nothing you don't.</h2>
    <div class="feature-grid">
      <div class="feature-card">
        <span class="feature-icon">CRUD + Search</span>
        <h3>Create &amp; manage notes</h3>
        <p>Full create, read, update, delete. Search across title and body. Soft delete with restore and force-delete endpoints.</p>
      </div>
      <div class="feature-card">
        <span class="feature-icon">S3 Storage</span>
        <h3>File attachments</h3>
        <p>Attach jpg, png, or pdf files (up to 5 MB) to any note. Files go straight to S3 at <code class="mono" style="font-size:.75rem;color:var(--accent)">notes/{id}/filename</code>.</p>
      </div>
      <div class="feature-card">
        <span class="feature-icon">Auto Docs</span>
        <h3>Ready-to-import Postman</h3>
        <p>Interactive docs at <code class="mono" style="font-size:.75rem;color:var(--accent)">/api-docs</code>. Download the Postman collection from <code class="mono" style="font-size:.75rem;color:var(--accent)">/docs.postman</code>.</p>
      </div>
    </div>
  </div>
</section>

<hr class="section-divider">

<section class="endpoints" id="endpoints">
  <div class="wrap">
    <p class="section-label">API reference</p>
    <h2 class="section-title">8 endpoints.</h2>
    <div style="overflow-x:auto;border-radius:8px;">
      <table class="endpoint-table">
        <thead><tr><th>Method</th><th>Path</th><th>Description</th></tr></thead>
        <tbody>
          <tr><td><span class="method method-get">GET</span></td><td class="path">/api/notes</td><td class="endpoint-desc">List notes — paginated, filterable by <code class="mono" style="font-size:.78rem">?search=</code></td></tr>
          <tr><td><span class="method method-post">POST</span></td><td class="path">/api/notes</td><td class="endpoint-desc">Create a note with optional file attachment</td></tr>
          <tr><td><span class="method method-get">GET</span></td><td class="path">/api/notes/{id}</td><td class="endpoint-desc">Retrieve a single note by ID</td></tr>
          <tr><td><span class="method method-put">PUT</span></td><td class="path">/api/notes/{id}</td><td class="endpoint-desc">Update title, body, or replace file</td></tr>
          <tr><td><span class="method method-delete">DELETE</span></td><td class="path">/api/notes/{id}</td><td class="endpoint-desc">Soft-delete a note (restorable)</td></tr>
          <tr><td><span class="method method-post">POST</span></td><td class="path">/api/notes/{id}/restore</td><td class="endpoint-desc">Restore a soft-deleted note</td></tr>
          <tr><td><span class="method method-delete">DELETE</span></td><td class="path">/api/notes/{id}/force</td><td class="endpoint-desc">Permanently delete note + S3 file</td></tr>
          <tr><td><span class="method method-post">POST</span></td><td class="path">/api/notes/{id}/file</td><td class="endpoint-desc">Upload or replace file attachment</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<hr class="section-divider">

<section class="quickstart" id="quickstart">
  <div class="wrap">
    <p class="section-label">Quick start</p>
    <h2 class="section-title">Create your first note.</h2>
    <div class="code-block">
      <div class="code-header">
        <span class="code-lang">curl</span>
        <button class="copy-btn" id="copy-btn" onclick="copyCode()">copy</button>
      </div>
      <code class="block" id="curl-code"><span class="hl-flag">curl</span> -X POST <span class="hl-url">{{ url('/api/notes') }}</span> <span class="hl-flag">-H</span> <span class="hl-val">"Content-Type: application/json"</span> <span class="hl-flag">-d</span> <span class="hl-val">'{"title":"My first note","body":"Notes API is live on EC2."}'</span></code>
    </div>
    <p style="color:var(--text-2);font-size:0.875rem;margin-top:16px;">
      Full docs at <a href="/api-docs" style="color:var(--accent);text-decoration:none;font-family:'JetBrains Mono',monospace;font-size:0.82rem;">/api-docs</a> — includes Postman collection and OpenAPI spec.
    </p>
  </div>
</section>

<footer>
  <div class="wrap">
    <div class="footer-inner">
      <p class="footer-left">Built with <span>Laravel 13</span> · Hosted on <span>AWS EC2</span> · Storage on <span>S3</span></p>
      <ul class="footer-links">
        <li><a href="/api-docs">Docs</a></li>
        <li><a href="/docs.postman" target="_blank" rel="noopener">Postman</a></li>
        <li><a href="https://github.com/haseebmirza/notes-api" target="_blank" rel="noopener">GitHub</a></li>
      </ul>
    </div>
  </div>
</footer>

<script>
const sequences = [
  {
    cmd: 'curl {{ url("/api/notes") }}',
    response: ['{','  <k>"data"</k>: [','    {','      <k>"id"</k>: <n>1</n>,','      <k>"title"</k>: <s>"Meeting notes"</s>,','      <k>"body"</k>: <s>"Q4 planning discussion."</s>,','      <k>"file_url"</k>: <nil>null</nil>','    }','  ],','  <k>"meta"</k>: { <k>"total"</k>: <n>1</n>, <k>"per_page"</k>: <n>15</n> }','}']
  },
  {
    cmd: `curl -X POST {{ url("/api/notes") }} \\\n  -H "Content-Type: application/json" \\\n  -d '{"title":"Deploy notes","body":"Live on EC2."}'`,
    response: ['{','  <k>"data"</k>: {','    <k>"id"</k>: <n>2</n>,','    <k>"title"</k>: <s>"Deploy notes"</s>,','    <k>"body"</k>: <s>"Live on EC2."</s>,','    <k>"file_url"</k>: <nil>null</nil>','  }','}']
  },
  {
    cmd: `curl -X POST {{ url("/api/notes/2/file") }} \\\n  -F "file=@agenda.pdf"`,
    response: ['{','  <k>"data"</k>: {','    <k>"id"</k>: <n>2</n>,','    <k>"file_url"</k>: <s>"https://haseeb-notes-files.s3.eu-north-1.amazonaws.com/notes/2/agenda.pdf"</s>','  }','}']
  },
  {
    cmd: 'curl -X DELETE {{ url("/api/notes/2") }}',
    response: ['<c>// 204 No Content</c>','<c>// Note soft-deleted. Restore with:</c>','<c>// POST /api/notes/2/restore</c>']
  }
];

function colorize(line) {
  return line
    .replace(/<k>(.*?)<\/k>/g, '<span class="t-key">$1</span>')
    .replace(/<s>(.*?)<\/s>/g, '<span class="t-str">$1</span>')
    .replace(/<n>(.*?)<\/n>/g, '<span class="t-num">$1</span>')
    .replace(/<nil>(.*?)<\/nil>/g, '<span class="t-null">$1</span>')
    .replace(/<c>(.*?)<\/c>/g, '<span class="t-comment">$1</span>');
}

const termBody = document.getElementById('terminal-body');
let seqIndex = 0;

async function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

async function typeText(el, text, speed = 22) {
  for (const ch of text) { el.textContent += ch; await sleep(speed + Math.random() * 18); }
}

async function runSequence(seq) {
  termBody.innerHTML = '';
  const cmdLine = document.createElement('div');
  const prompt = document.createElement('span');
  prompt.className = 't-prompt'; prompt.textContent = '$ ';
  cmdLine.appendChild(prompt);
  const cmdSpan = document.createElement('span');
  cmdSpan.className = 't-cmd';
  cmdLine.appendChild(cmdSpan);
  termBody.appendChild(cmdLine);
  const cur = document.createElement('span');
  cur.className = 't-cursor';
  cmdLine.appendChild(cur);
  const lines = seq.cmd.split('\n');
  for (let i = 0; i < lines.length; i++) {
    if (i === 0) { await typeText(cmdSpan, lines[i], 20); }
    else {
      const cont = document.createElement('div');
      cont.style.paddingLeft = '13px';
      const span = document.createElement('span');
      span.className = 't-cmd';
      cont.appendChild(span);
      termBody.appendChild(cont);
      await typeText(span, lines[i], 20);
    }
  }
  cur.remove();
  await sleep(300);
  const responseEl = document.createElement('div');
  responseEl.style.marginTop = '8px';
  termBody.appendChild(responseEl);
  for (const line of seq.response) {
    const div = document.createElement('div');
    div.innerHTML = colorize(line);
    responseEl.appendChild(div);
    await sleep(40);
  }
}

async function loop() {
  while (true) {
    await runSequence(sequences[seqIndex]);
    seqIndex = (seqIndex + 1) % sequences.length;
    await sleep(3200);
  }
}
loop();

function copyCode() {
  const text = `curl -X POST {{ url('/api/notes') }} \\\n  -H "Content-Type: application/json" \\\n  -d '{"title":"My first note","body":"Notes API is live on EC2."}'`;
  const btn = document.getElementById('copy-btn');
  navigator.clipboard.writeText(text).then(() => {
    btn.textContent = 'copied!'; btn.style.color = 'var(--accent)'; btn.style.borderColor = 'var(--accent)';
    setTimeout(() => { btn.textContent = 'copy'; btn.style.color = ''; btn.style.borderColor = ''; }, 2000);
  }).catch(() => {
    const el = document.getElementById('curl-code');
    const range = document.createRange(); range.selectNode(el);
    window.getSelection().removeAllRanges(); window.getSelection().addRange(range);
  });
}
</script>
</body>
</html>
