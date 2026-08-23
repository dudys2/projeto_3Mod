import { buscarEmprestimos } from "./api.js";
import { renderPainel } from "./render.js";
// Resolução de fluxo assíncrono, com try/catch para falhas de rede
async function iniciar() {
    try {
        const dados = await buscarEmprestimos();
        renderPainel(dados);
    }
    catch (erro) {
        console.error("Erro ao carregar dashboard:", erro);
        const vazio = document.getElementById("vazio");
        if (vazio) {
            vazio.textContent = "Erro ao carregar os dados do servidor.";
            vazio.style.display = "block";
        }
    }
}
iniciar();
