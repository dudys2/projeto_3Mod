# Sistema de Biblioteca

## Recompilar o TypeScript (se editar algo em ts/)

```
cd ts
npm install -g typescript   (só na primeira vez)
tsc --project tsconfig.json
```

## Onde está cada item da rubrica

- Banco de Dados Avançado → `database/biblioteca.sql` (CTE, views, procedure, trigger, function)
- 3 CRUDs + Bootstrap + template → `pages/*.php`, `includes/*.php`
- Regra de exclusão → `excluirLivro()` em `includes/funcoes.php`
- Lógica Avançada (TypeScript) → `ts/interfaces.ts`, `ts/logica.ts`
- Tech Forge (API, DOM, dashboard) → `api/dashboard.php`, `ts/api.ts`, `ts/render.ts`, `ts/main.ts`
