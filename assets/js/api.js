export async function buscarEmprestimos() {
    const resposta = await fetch("../api/dashboard.php");
    if (!resposta.ok) {
        throw new Error("Falha ao buscar dados do servidor.");
    }
    return await resposta.json();
}
