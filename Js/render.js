import { totalMultas, filtrarPorStatus, livroMaisEmprestado, formatarMoeda } from "./logica.js";
// Manipulação Segura do DOM
export function renderPainel(lista) {
    const cards = document.getElementById("cards");
    const corpoTabela = document.getElementById("corpo-tabela");
    const vazio = document.getElementById("vazio");
    if (!cards || !corpoTabela || !vazio) {
        return;
    }
    //  (Edge Cases)
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
    cards.innerHTML = `
    <div class="col-md-3"><div class="card p-3">Ativos<br><strong>${ativos.length}</strong></div></div>
    <div class="col-md-3"><div class="card p-3">Atrasados<br><strong>${atrasados.length}</strong></div></div>
    <div class="col-md-3"><div class="card p-3">Total em multas<br><strong>${formatarMoeda(total)}</strong></div></div>
    <div class="col-md-3"><div class="card p-3">Destaque<br><strong>${destaque !== null && destaque !== void 0 ? destaque : "-"}</strong></div></div>
  `;
    corpoTabela.innerHTML = lista.map(emprestimo => `
    <tr>
      <td>${emprestimo.titulo}</td>
      <td>${emprestimo.usuario}</td>
      <td>${emprestimo.status}</td>
    </tr>
  `).join("");
}
