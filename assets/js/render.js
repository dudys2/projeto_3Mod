import { totalMultas, filtrarPorStatus, livroMaisEmprestado, formatarMoeda } from "./logica.js";
// Ícones simples em SVG (sem dependência externa)
const ICONE_LIVRO = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>`;
const ICONE_RELOGIO = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>`;
const ICONE_MOEDA = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v10M9 9.5c0-1.4 1.3-2.5 3-2.5s3 1.1 3 2.5-1.3 2-3 2.5-3 1.1-3 2.5 1.3 2.5 3 2.5 3-1.1 3-2.5"/></svg>`;
const ICONE_DESTAQUE = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 21h8M12 17v4M7 4h10v4a5 5 0 0 1-10 0V4z"/><path d="M17 5h3a3 3 0 0 1-3 4M7 5H4a3 3 0 0 0 3 4"/></svg>`;
// Manipulação segura do DOM: nunca acessa um elemento sem checar se existe
export function renderPainel(lista) {
    const cards = document.getElementById("cards");
    const corpoTabela = document.getElementById("corpo-tabela");
    const vazio = document.getElementById("vazio");
    if (!cards || !corpoTabela || !vazio) {
        return;
    }
    if (lista.length === 0) {
        vazio.style.display = "block";
        cards.innerHTML = "";
        corpoTabela.innerHTML = "";
        return;
    }
    vazio.style.display = "none";
    const ativos = filtrarPorStatus(lista, "ativo");
    const atrasados = filtrarPorStatus(lista, "atrasado");
    const destaque = livroMaisEmprestado(lista);
    const total = totalMultas(lista);
    const classeAtrasados = atrasados.length > 0 ? "alerta" : "ok";
    const classeMultas = total > 0 ? "alerta" : "ok";
    cards.innerHTML = `
    <div class="col-md-3"><div class="ficha">
      <span class="icone">${ICONE_LIVRO}</span>
      <div><span class="rotulo">Ativos</span><span class="valor">${ativos.length}</span></div>
    </div></div>
    <div class="col-md-3"><div class="ficha ${classeAtrasados}">
      <span class="icone">${ICONE_RELOGIO}</span>
      <div><span class="rotulo">Atrasados</span><span class="valor">${atrasados.length}</span></div>
    </div></div>
    <div class="col-md-3"><div class="ficha ${classeMultas}">
      <span class="icone">${ICONE_MOEDA}</span>
      <div><span class="rotulo">Multas</span><span class="valor">${formatarMoeda(total)}</span></div>
    </div></div>
    <div class="col-md-3"><div class="ficha">
      <span class="icone">${ICONE_DESTAQUE}</span>
      <div><span class="rotulo">Destaque</span><span class="valor" style="font-size:1.05rem;">${destaque !== null && destaque !== void 0 ? destaque : "—"}</span></div>
    </div></div>
  `;
    corpoTabela.innerHTML = lista.map(emprestimo => `
    <tr>
      <td>${emprestimo.titulo}</td>
      <td>${emprestimo.usuario}</td>
      <td>${emprestimo.status}</td>
    </tr>
  `).join("");
}
