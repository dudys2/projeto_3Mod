// (reduce)
export function totalMultas(lista) {
    return lista.reduce((soma, emprestimo) => soma + emprestimo.valor_multa, 0);
}
// :(filter)
export function filtrarPorStatus(lista, status) {
    return lista.filter(emprestimo => emprestimo.status === status);
}
//  (Destaques)
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
// (map)
export function formatarMoeda(valor) {
    return valor.toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
}
