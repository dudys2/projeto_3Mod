// reduce: soma o valor de todas as multas
export function totalMultas(lista) {
    return lista.reduce((soma, emprestimo) => soma + emprestimo.valor_multa, 0);
}
// filter: separa os empréstimos por status
export function filtrarPorStatus(lista, status) {
    return lista.filter(emprestimo => emprestimo.status === status);
}
// ranking: descobre o título mais frequente na lista
export function livroMaisEmprestado(lista) {
    const contagem = {};
    lista.forEach(emprestimo => {
        var _a;
        contagem[emprestimo.titulo] = ((_a = contagem[emprestimo.titulo]) !== null && _a !== void 0 ? _a : 0) + 1;
    });
    let destaque = null;
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
export function formatarMoeda(valor) {
    return valor.toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
}
