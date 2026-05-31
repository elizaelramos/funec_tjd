-- =====================================================================
--  Criação do usuário dedicado da aplicação TJD / FUNEC
--  Banco: funec_app
--
--  COMO USAR:
--   1. Troque o valor de @app_password abaixo por uma senha forte sua.
--   2. Execute este script logado como root (MySQL Workbench ou linha
--      de comando). Veja instruções no final do arquivo.
--   3. Use o MESMO usuário/senha no arquivo .env do Laravel.
--
--  Boa prática: este usuário tem acesso APENAS ao banco funec_app,
--  nunca ao servidor inteiro (diferente do root).
-- =====================================================================

-- Garante que o banco exista (você já criou, mas não custa garantir)
CREATE DATABASE IF NOT EXISTS funec_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- >>> TROQUE A SENHA ABAIXO <<<
-- ---------------------------------------------------------------------
SET @app_user     = 'funec_app';
SET @app_password = 'TROQUE_ESTA_SENHA';

-- ---------------------------------------------------------------------
-- Cria o usuário para conexões locais (localhost e 127.0.0.1).
-- O Laravel conecta via TCP em 127.0.0.1, mas criamos os dois por
-- segurança, pois o MySQL pode resolver a conexão como 'localhost'.
-- ---------------------------------------------------------------------
SET @sql1 = CONCAT('CREATE USER IF NOT EXISTS ''', @app_user, '''@''localhost'' IDENTIFIED BY ''', @app_password, '''');
SET @sql2 = CONCAT('CREATE USER IF NOT EXISTS ''', @app_user, '''@''127.0.0.1'' IDENTIFIED BY ''', @app_password, '''');
PREPARE s1 FROM @sql1; EXECUTE s1; DEALLOCATE PREPARE s1;
PREPARE s2 FROM @sql2; EXECUTE s2; DEALLOCATE PREPARE s2;

-- Concede acesso somente ao banco funec_app
GRANT ALL PRIVILEGES ON funec_app.* TO 'funec_app'@'localhost';
GRANT ALL PRIVILEGES ON funec_app.* TO 'funec_app'@'127.0.0.1';

FLUSH PRIVILEGES;

-- Confirmação
SELECT CONCAT('Usuario ', @app_user, ' criado com acesso ao banco funec_app.') AS resultado;

-- =====================================================================
--  COMO EXECUTAR
--  -------------------------------------------------------------------
--  Opção 1 — MySQL Workbench:
--     Abra este arquivo, conecte como root e clique no raio (Execute).
--
--  Opção 2 — Linha de comando (ajuste o caminho do mysql.exe):
--     "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe" -u root -p < "C:\xampp\htdocs\tjd\database\sql\create_funec_user.sql"
--     (vai pedir a senha do root)
-- =====================================================================
