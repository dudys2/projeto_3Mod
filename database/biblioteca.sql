DROP DATABASE IF EXISTS biblioteca;
CREATE DATABASE biblioteca;
USE biblioteca;

CREATE TABLE autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL
);

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL
);

CREATE TABLE livros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    autor_id INT,
    categoria_id INT,
    quantidade_total INT DEFAULT 1,
    quantidade_disponivel INT DEFAULT 1,
    FOREIGN KEY (autor_id) REFERENCES autores(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE emprestimos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livro_id INT NOT NULL,
    usuario_id INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_prevista_devolucao DATE NOT NULL,
    data_devolucao DATE NULL,
    valor_multa DECIMAL(6,2) DEFAULT 0.00,
    status ENUM('ativo','devolvido','atrasado') DEFAULT 'ativo',
    FOREIGN KEY (livro_id) REFERENCES livros(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- dados de teste
INSERT INTO autores (nome) VALUES ('J.R.R. Tolkien'), ('Robert C. Martin');
INSERT INTO categorias (nome) VALUES ('Fantasia'), ('Tecnologia');
INSERT INTO livros (titulo, autor_id, categoria_id, quantidade_total, quantidade_disponivel) VALUES
('O Hobbit', 1, 1, 3, 2),
('Clean Code', 2, 2, 2, 0);
INSERT INTO usuarios (nome, email) VALUES
('Ana Souza', 'ana@escola.com'),
('Bruno Lima', 'bruno@escola.com');
INSERT INTO emprestimos (livro_id, usuario_id, data_emprestimo, data_prevista_devolucao, valor_multa, status) VALUES
(1, 1, '2026-07-20', '2026-08-10', 12.00, 'atrasado'),
(2, 2, '2026-08-01', '2026-08-20', 0.00, 'ativo');

-- 1) Function reutilizável: calcula dias de atraso
DELIMITER $$
CREATE FUNCTION fn_dias_atraso(p_prevista DATE, p_devolucao DATE)
RETURNS INT DETERMINISTIC
BEGIN
    IF p_devolucao IS NULL THEN
        RETURN GREATEST(DATEDIFF(CURDATE(), p_prevista), 0);
    END IF;
    RETURN GREATEST(DATEDIFF(p_devolucao, p_prevista), 0);
END $$
DELIMITER ;

-- 2) View que centraliza várias tabelas (livros + autores + usuarios + categorias)
CREATE VIEW vw_emprestimos_detalhados AS
SELECT e.id, l.titulo, a.nome AS autor, u.nome AS usuario, c.nome AS categoria,
       e.data_prevista_devolucao, e.valor_multa, e.status,
       fn_dias_atraso(e.data_prevista_devolucao, e.data_devolucao) AS dias_atraso
FROM emprestimos e
JOIN livros l ON l.id = e.livro_id
JOIN usuarios u ON u.id = e.usuario_id
LEFT JOIN autores a ON a.id = l.autor_id
LEFT JOIN categorias c ON c.id = l.categoria_id;

-- 3) CTE + View analítica (indicadores por categoria, pro dashboard.php)
CREATE VIEW vw_indicadores_por_categoria AS
WITH base AS (
    SELECT e.status, e.valor_multa, l.categoria_id
    FROM emprestimos e
    JOIN livros l ON l.id = e.livro_id
)
SELECT c.nome AS categoria,
       COUNT(*) AS total_emprestimos,
       SUM(base.status = 'atrasado') AS atrasados,
       SUM(base.valor_multa) AS total_multas
FROM base
JOIN categorias c ON c.id = base.categoria_id
GROUP BY c.nome;

-- 4) Trigger: multa nunca pode ser negativa
DELIMITER $$
CREATE TRIGGER trg_multa_positiva
BEFORE UPDATE ON emprestimos
FOR EACH ROW
BEGIN
    IF NEW.valor_multa < 0 THEN
        SET NEW.valor_multa = ABS(NEW.valor_multa);
    END IF;
END $$
DELIMITER ;

-- 5) Procedure: busca + filtro + paginação (pra emprestimos.php)
DELIMITER $$
CREATE PROCEDURE sp_listar_emprestimos(
    IN p_status VARCHAR(20),
    IN p_busca VARCHAR(150),
    IN p_pagina INT,
    IN p_itens INT
)
BEGIN
    DECLARE v_offset INT DEFAULT (p_pagina - 1) * p_itens;
    SELECT * FROM vw_emprestimos_detalhados
    WHERE (p_status IS NULL OR status = p_status)
      AND (p_busca IS NULL OR titulo LIKE CONCAT('%', p_busca, '%'))
    ORDER BY dias_atraso DESC
    LIMIT p_itens OFFSET v_offset;
END $$
DELIMITER ;