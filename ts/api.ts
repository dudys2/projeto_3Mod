import { Emprestimo } from "./interfaces.js";

export async function buscarEmprestimos(): Promise<Emprestimo[]> {
  const resposta = await fetch("../api/dashboard.php");

  if (!resposta.ok) {
    throw new Error("Falha ao buscar dados do servidor.");
  }

  return await resposta.json();
}
