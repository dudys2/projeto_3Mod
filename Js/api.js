//  consumo de API via fetch, usando async/await
export async function buscarEmprestimos() {
    const resposta = await fetch("../api/dashboard.php");
    if (!resposta.ok) {
        throw new Error("Falha ao buscar dados do servidor.");
    }
    const dados = await resposta.json();
    return dados;
}
