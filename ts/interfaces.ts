// Mapeia 100% do JSON devolvido por api/dashboard.php, sem uso de "any"
export interface Emprestimo {
  id: number;
  titulo: string;
  autor: string | null;
  usuario: string;
  categoria: string | null;
  data_prevista_devolucao: string;
  valor_multa: number;
  status: "ativo" | "devolvido" | "atrasado";
  dias_atraso: number;
}
