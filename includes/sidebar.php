<?php $paginaAtual = basename($_SERVER['PHP_SELF']); ?>

<div class="biblioteca-sidebar">
  <span class="eyebrow">Acervo</span>
  <div class="list-group">
    <a href="dashboard.php" class="list-group-item list-group-item-action <?= $paginaAtual === 'dashboard.php' ? 'active-page' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
      Painel
    </a>
    <a href="livros.php" class="list-group-item list-group-item-action <?= $paginaAtual === 'livros.php' ? 'active-page' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
      Livros
    </a>
    <a href="autores.php" class="list-group-item list-group-item-action <?= $paginaAtual === 'autores.php' ? 'active-page' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6"/></svg>
      Autores
    </a>
    <a href="usuarios.php" class="list-group-item list-group-item-action <?= $paginaAtual === 'usuarios.php' ? 'active-page' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3"/><path d="M2 20c0-2.8 3.1-5 7-5s7 2.2 7 5"/><circle cx="17" cy="9" r="2.4"/><path d="M15.5 13.2c2.6.3 4.5 2 4.5 4.3"/></svg>
      Usuários
    </a>
    <a href="emprestimos.php" class="list-group-item list-group-item-action <?= $paginaAtual === 'emprestimos.php' ? 'active-page' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10M7 12h10M7 17h6"/><path d="M4 4h16v16H4z" opacity="0"/></svg>
      Empréstimos
    </a>
  </div>
</div>

<div class="flex-grow-1">
