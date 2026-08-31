import { Emprestimo } from "./interfaces.js";

// reduce: soma o valor de todas as multas
export function totalMultas(lista: Emprestimo[]): number {
  return lista.reduce((soma, emprestimo) => soma + emprestimo.valor_multa, 0);
}

// filter: separa os empréstimos por status
export function filtrarPorStatus(lista: Emprestimo[], status: Emprestimo["status"]): Emprestimo[] {
  return lista.filter(emprestimo => emprestimo.status === status);
}

// ranking: descobre o título mais frequente na lista
export function livroMaisEmprestado(lista: Emprestimo[]): string | null {
  const contagem: Record<string, number> = {};

  lista.forEach(emprestimo => {
    contagem[emprestimo.titulo] = (contagem[emprestimo.titulo] ?? 0) + 1;
  });

  let destaque: string | null = null;
  let maiorValor = 0;

  for (const titulo in contagem) {
    if (contagem[titulo] > maiorValor) {
      maiorValor = contagem[titulo];
      destaque = titulo;
    }
  }

  return destaque;
}

// map (usado no render.ts): formata número em moeda brasileira
export function formatarMoeda(valor: number): string {
  return valor.toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
}
