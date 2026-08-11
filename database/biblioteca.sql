/* =========================================================
   LIBRARY MANAGER
   BANCO DE DADOS - MYSQL
   ========================================================= */


/* =========================================================
   1. CRIAR BANCO
   ========================================================= */

DROP DATABASE IF EXISTS library_manager;

CREATE DATABASE library_manager
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE library_manager;


/* =========================================================
   2. TABELA DE AUTORES
   ========================================================= */

CREATE TABLE autores (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(120) NOT NULL,

    nacionalidade VARCHAR(80),

    data_nascimento DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/* =========================================================
   3. TABELA DE CATEGORIAS
   ========================================================= */

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(80) NOT NULL UNIQUE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/* =========================================================
   4. TABELA DE LIVROS
   ========================================================= */

CREATE TABLE livros (
    id INT AUTO_INCREMENT PRIMARY KEY,

    titulo VARCHAR(180) NOT NULL,

    isbn VARCHAR(20) UNIQUE,

    ano INT NOT NULL,

    quantidade INT NOT NULL DEFAULT 0,

    autor_id INT NOT NULL,

    categoria_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_livros_autor
        FOREIGN KEY (autor_id)
        REFERENCES autores(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_livros_categoria
        FOREIGN KEY (categoria_id)
        REFERENCES categorias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_livros_quantidade
        CHECK (quantidade >= 0)
);


/* =========================================================
   5. TABELA DE USUÁRIOS
   ========================================================= */

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(120) NOT NULL,

    cpf VARCHAR(14) NOT NULL UNIQUE,

    telefone VARCHAR(20),

    email VARCHAR(150) NOT NULL UNIQUE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/* =========================================================
   6. TABELA DE EMPRÉSTIMOS
   ========================================================= */

CREATE TABLE emprestimos (
    id INT AUTO_INCREMENT PRIMARY KEY,

    livro_id INT NOT NULL,

    usuario_id INT NOT NULL,

    data_emprestimo DATE NOT NULL,

    data_prevista DATE NOT NULL,

    data_devolucao DATE NULL,

    status ENUM(
        'Emprestado',
        'Devolvido',
        'Atrasado'
    ) NOT NULL DEFAULT 'Emprestado',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_emprestimos_livro
        FOREIGN KEY (livro_id)
        REFERENCES livros(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_emprestimos_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


/* =========================================================
   7. ÍNDICES
   ========================================================= */

CREATE INDEX idx_livros_titulo
ON livros(titulo);

CREATE INDEX idx_livros_autor
ON livros(autor_id);

CREATE INDEX idx_livros_categoria
ON livros(categoria_id);

CREATE INDEX idx_emprestimos_livro
ON emprestimos(livro_id);

CREATE INDEX idx_emprestimos_usuario
ON emprestimos(usuario_id);

CREATE INDEX idx_emprestimos_status
ON emprestimos(status);


/* =========================================================
   8. DADOS DOS AUTORES
   ========================================================= */

INSERT INTO autores
(
    nome,
    nacionalidade,
    data_nascimento
)
VALUES
(
    'Machado de Assis',
    'Brasileira',
    '1839-06-21'
),
(
    'Clarice Lispector',
    'Brasileira',
    '1920-12-10'
),
(
    'George Orwell',
    'Britânica',
    '1903-06-25'
),
(
    'J. K. Rowling',
    'Britânica',
    '1965-07-31'
),
(
    'Stephen King',
    'Americana',
    '1947-09-21'
);


/* =========================================================
   9. DADOS DAS CATEGORIAS
   ========================================================= */

INSERT INTO categorias
(nome)
VALUES
('Romance'),
('Ficção'),
('Fantasia'),
('Clássico'),
('Terror');


/* =========================================================
   10. DADOS DOS LIVROS
   ========================================================= */

INSERT INTO livros
(
    titulo,
    isbn,
    ano,
    quantidade,
    autor_id,
    categoria_id
)
VALUES
(
    'Dom Casmurro',
    '978000000001',
    1899,
    8,
    1,
    4
),
(
    'Memórias Póstumas de Brás Cubas',
    '978000000002',
    1881,
    5,
    1,
    4
),
(
    'A Hora da Estrela',
    '978000000003',
    1977,
    6,
    2,
    1
),
(
    '1984',
    '978000000004',
    1949,
    10,
    3,
    2
),
(
    'Harry Potter e a Pedra Filosofal',
    '978000000005',
    1997,
    12,
    4,
    3
),
(
    'O Iluminado',
    '978000000006',
    1977,
    7,
    5,
    5
);


/* =========================================================
   11. DADOS DOS USUÁRIOS
   ========================================================= */

INSERT INTO usuarios
(
    nome,
    cpf,
    telefone,
    email
)
VALUES
(
    'EDUARDOOOOOOOO',
    '111.111.111-11',
    '44999990000',
    'EDUARDOOOO@email.com'
),
(
    'EDUARDOOOOOO',
    '222.222.222-22',
    '44999990001',
    'EDUARDOOOO@email.com'
),
(
    'Pedro Oliveira',
    '333.333.333-33',
    '44999990002',
    'pedro@email.com'
),
(
    'EDUARDOOOO',
    '444.444.444-44',
    '44999990003',
    'EDUARDOO@email.com'
);


/* =========================================================
   12. EMPRÉSTIMOS DE TESTE
   ========================================================= */

INSERT INTO emprestimos
(
    livro_id,
    usuario_id,
    data_emprestimo,
    data_prevista,
    data_devolucao,
    status
)
VALUES
(
    1,
    1,
    DATE_SUB(CURDATE(), INTERVAL 2 DAY),
    DATE_ADD(CURDATE(), INTERVAL 5 DAY),
    NULL,
    'Emprestado'
),
(
    4,
    2,
    DATE_SUB(CURDATE(), INTERVAL 10 DAY),
    DATE_SUB(CURDATE(), INTERVAL 3 DAY),
    NULL,
    'Atrasado'
),
(
    5,
    3,
    DATE_SUB(CURDATE(), INTERVAL 5 DAY),
    DATE_ADD(CURDATE(), INTERVAL 2 DAY),
    NULL,
    'Emprestado'
),
(
    2,
    4,
    DATE_SUB(CURDATE(), INTERVAL 15 DAY),
    DATE_SUB(CURDATE(), INTERVAL 8 DAY),
    DATE_SUB(CURDATE(), INTERVAL 5 DAY),
    'Devolvido'
);


/* =========================================================
   13. FUNCTION
   Calcula dias de atraso
   ========================================================= */

DELIMITER $$

CREATE FUNCTION fn_dias_atraso(
    p_data_prevista DATE,
    p_data_referencia DATE
)
RETURNS INT
DETERMINISTIC
BEGIN

    DECLARE dias INT;

    SET dias =
        DATEDIFF(
            p_data_referencia,
            p_data_prevista
        );

    IF dias > 0 THEN
        RETURN dias;
    END IF;

    RETURN 0;

END$$

DELIMITER ;


/* =========================================================
   14. VIEW DE LIVROS
   ========================================================= */

CREATE VIEW vw_livros_completos AS

SELECT

    l.id,

    l.titulo,

    l.isbn,

    l.ano,

    l.quantidade,

    a.nome AS autor,

    a.nacionalidade,

    c.nome AS categoria

FROM livros l

INNER JOIN autores a
    ON a.id = l.autor_id

INNER JOIN categorias c
    ON c.id = l.categoria_id;


/* =========================================================
   15. VIEW DO DASHBOARD
   ========================================================= */

CREATE VIEW vw_dashboard AS

SELECT

    (
        SELECT COUNT(*)
        FROM livros
    ) AS total_livros,

    (
        SELECT COALESCE(SUM(quantidade), 0)
        FROM livros
    ) AS total_exemplares,

    (
        SELECT COUNT(*)
        FROM usuarios
    ) AS total_usuarios,

    (
        SELECT COUNT(*)
        FROM emprestimos
        WHERE status = 'Emprestado'
    ) AS emprestimos_ativos,

    (
        SELECT COUNT(*)
        FROM emprestimos
        WHERE data_devolucao IS NULL
        AND data_prevista < CURDATE()
    ) AS livros_atrasados;


/* =========================================================
   16. TRIGGER
   Impede quantidade negativa
   ========================================================= */

DELIMITER $$

CREATE TRIGGER trg_livros_before_update

BEFORE UPDATE ON livros

FOR EACH ROW

BEGIN

    IF NEW.quantidade < 0 THEN

        SET NEW.quantidade = 0;

    END IF;

END$$

DELIMITER ;


/* =========================================================
   17. TRIGGER
   Valida empréstimo
   ========================================================= */

DELIMITER $$

CREATE TRIGGER trg_emprestimos_before_insert

BEFORE INSERT ON emprestimos

FOR EACH ROW

BEGIN

    IF NEW.data_emprestimo IS NULL THEN

        SET NEW.data_emprestimo = CURDATE();

    END IF;


    IF NEW.data_prevista < NEW.data_emprestimo THEN

        SIGNAL SQLSTATE '45000'

        SET MESSAGE_TEXT =
            'A data prevista nao pode ser anterior a data do emprestimo.';

    END IF;

END$$

DELIMITER ;


/* =========================================================
   18. PROCEDURE
   Listar livros
   ========================================================= */

DELIMITER $$

CREATE PROCEDURE sp_listar_livros(
    IN p_pesquisa VARCHAR(180),
    IN p_limite INT,
    IN p_offset INT
)

BEGIN

    SELECT

        l.id,

        l.titulo,

        l.isbn,

        l.ano,

        l.quantidade,

        l.autor_id,

        a.nome AS autor,

        l.categoria_id,

        c.nome AS categoria

    FROM livros l

    INNER JOIN autores a
        ON a.id = l.autor_id

    INNER JOIN categorias c
        ON c.id = l.categoria_id

    WHERE

        p_pesquisa IS NULL

        OR p_pesquisa = ''

        OR l.titulo LIKE
            CONCAT('%', p_pesquisa, '%')

        OR a.nome LIKE
            CONCAT('%', p_pesquisa, '%')

        OR c.nome LIKE
            CONCAT('%', p_pesquisa, '%')

    ORDER BY l.id DESC

    LIMIT p_offset, p_limite;

END$$

DELIMITER ;


/* =========================================================
   19. PROCEDURE
   Listar usuários
   ========================================================= */

DELIMITER $$

CREATE PROCEDURE sp_listar_usuarios(
    IN p_pesquisa VARCHAR(120),
    IN p_limite INT,
    IN p_offset INT
)

BEGIN

    SELECT

        id,

        nome,

        cpf,

        telefone,

        email

    FROM usuarios

    WHERE

        p_pesquisa IS NULL

        OR p_pesquisa = ''

        OR nome LIKE
            CONCAT('%', p_pesquisa, '%')

        OR cpf LIKE
            CONCAT('%', p_pesquisa, '%')

        OR email LIKE
            CONCAT('%', p_pesquisa, '%')

    ORDER BY id DESC

    LIMIT p_offset, p_limite;

END$$

DELIMITER ;


/* =========================================================
   20. PROCEDURE
   Listar autores
   ========================================================= */

DELIMITER $$

CREATE PROCEDURE sp_listar_autores(
    IN p_pesquisa VARCHAR(120),
    IN p_limite INT,
    IN p_offset INT
)

BEGIN

    SELECT

        id,

        nome,

        nacionalidade,

        data_nascimento

    FROM autores

    WHERE

        p_pesquisa IS NULL

        OR p_pesquisa = ''

        OR nome LIKE
            CONCAT('%', p_pesquisa, '%')

        OR nacionalidade LIKE
            CONCAT('%', p_pesquisa, '%')

    ORDER BY id DESC

    LIMIT p_offset, p_limite;

END$$

DELIMITER ;


/* =========================================================
   21. PROCEDURE DO DASHBOARD
   ========================================================= */

DELIMITER $$

CREATE PROCEDURE sp_dashboard()

BEGIN

    /* -------------------------
       RESUMO
       ------------------------- */

    SELECT

        COUNT(DISTINCT l.id)
            AS total_livros,

        COALESCE(
            SUM(l.quantidade),
            0
        ) AS total_exemplares,

        (
            SELECT COUNT(*)
            FROM usuarios
        ) AS total_usuarios,

        (
            SELECT COUNT(*)
            FROM emprestimos
            WHERE status = 'Emprestado'
        ) AS emprestimos_ativos,

        (
            SELECT COUNT(*)
            FROM emprestimos
            WHERE data_devolucao IS NULL
            AND data_prevista < CURDATE()
        ) AS livros_atrasados,

        (
            SELECT COUNT(*)
            FROM emprestimos
            WHERE YEAR(data_emprestimo)
                = YEAR(CURDATE())

            AND MONTH(data_emprestimo)
                = MONTH(CURDATE())
        ) AS emprestimos_mes

    FROM livros l;


    /* -------------------------
       LIVROS POR CATEGORIA
       ------------------------- */

    SELECT

        c.nome AS categoria,

        COUNT(l.id) AS quantidade

    FROM categorias c

    LEFT JOIN livros l
        ON l.categoria_id = c.id

    GROUP BY

        c.id,
        c.nome

    ORDER BY quantidade DESC;


    /* -------------------------
       EMPRÉSTIMOS POR MÊS
       ------------------------- */

    SELECT

        DATE_FORMAT(
            e.data_emprestimo,
            '%Y-%m'
        ) AS mes,

        COUNT(*) AS total

    FROM emprestimos e

    WHERE e.data_emprestimo >=
        DATE_SUB(
            CURDATE(),
            INTERVAL 11 MONTH
        )

    GROUP BY

        DATE_FORMAT(
            e.data_emprestimo,
            '%Y-%m'
        )

    ORDER BY mes;


    /* -------------------------
       TOP 5 LIVROS
       ------------------------- */

    SELECT

        l.titulo,

        COUNT(e.id)
            AS total_emprestimos

    FROM livros l

    LEFT JOIN emprestimos e
        ON e.livro_id = l.id

    GROUP BY

        l.id,
        l.titulo

    ORDER BY

        total_emprestimos DESC

    LIMIT 5;

END$$

DELIMITER ;


/* =========================================================
   22. CTE
   Consulta para relatório
   ========================================================= */

WITH estatisticas AS (

    SELECT

        COUNT(*) AS total_livros,

        SUM(quantidade)
            AS total_exemplares

    FROM livros

),

emprestimos AS (

    SELECT

        COUNT(*) AS total_emprestimos

    FROM emprestimos

)

SELECT

    e.total_livros,

    e.total_exemplares,

    emp.total_emprestimos

FROM estatisticas e

CROSS JOIN emprestimos emp;


/* =========================================================
   23. CONSULTA COM FUNÇÃO
   ========================================================= */

SELECT

    e.id,

    l.titulo,

    u.nome AS usuario,

    e.data_prevista,

    fn_dias_atraso(
        e.data_prevista,
        CURDATE()
    ) AS dias_atraso,

    e.status

FROM emprestimos e

INNER JOIN livros l
    ON l.id = e.livro_id

INNER JOIN usuarios u
    ON u.id = e.usuario_id;


/* =========================================================
   24. CONSULTA DA VIEW
   ========================================================= */

SELECT *

FROM vw_livros_completos;


/* =========================================================
   25. CONSULTA DO DASHBOARD
   ========================================================= */

SELECT *

FROM vw_dashboard;


/* =========================================================
   FIM DO SCRIPT
   ========================================================= */