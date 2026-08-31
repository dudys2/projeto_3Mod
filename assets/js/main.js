import { buscarEmprestimos } from "./api.js";
import { renderPainel } from "./render.js";
async function iniciar() {
    try {
        const dados = await buscarEmprestimos();
        renderPainel(dados);
    }
    catch (erro) {
        console.error("Erro ao carregar painel:", erro);
        const vazio = document.getElementById("vazio");
        if (vazio) {
            vazio.textContent = "Não foi possível carregar os dados agora. Tente recarregar a página.";
            vazio.style.display = "block";
        }
    }
}
iniciar();
