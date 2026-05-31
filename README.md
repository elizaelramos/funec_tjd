# 🏛️ TJD · FUNEC - Sistema de Gestão de Processos

**Tribunal de Justiça Desportiva do Futebol Estadual do Mato Grosso do Sul**

Sistema web moderno para gerenciamento completo de processos disciplinares e julgamentos desportivos, desenvolvido com Laravel, Blade Templates e CSS customizado.

---

## 📋 Visão Geral

O TJD · FUNEC é uma aplicação robusta que automatiza o fluxo completo de processos disciplinares, desde a criação até o julgamento final, com suporte para recursos e período de recurso de 72 horas (3 dias corridos).

### Principais Recursos

✅ **Gestão de Processos**
- Criação, edição e consulta de processos
- Rastreamento completo do fluxo
- 7 situações/estados diferentes
- Histórico de andamentos

✅ **Sistema de Pautas**
- Agendamento de sessões de julgamento
- Agrupamento de múltiplos processos
- Data, hora e local definidos
- Status de julgamento

✅ **Período de Recurso**
- Cálculo automático de 72 horas
- Monitoramento de prazos
- Aceitar/rejeitar recursos
- Reagendar para novo julgamento

✅ **Gestão de Documentos**
- Anexação de documentos em cada etapa
- Tipos: origem, citação, decisão, recurso
- Armazenamento seguro
- Acesso público quando apropriado

✅ **Área Pública**
- Consulta de processos por número
- Visualização de decisões e prazos de recurso
- Acesso a documentos públicos
- Lista de pautas agendadas

✅ **Painel Administrativo**
- Acesso restrito para editores do tribunal
- Controle total de processos e pautas
- Gerenciamento de documentos
- Administração de usuários (admin)

---

## 🛠️ Stack Tecnológico

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Blade Templates + HTML5 + CSS3
- **Banco de Dados:** MySQL 8.0+
- **Autenticação:** Laravel Breeze
- **CSS:** Tailwind + Design System customizado
- **JavaScript:** Alpine.js (interatividade)
- **Servidor:** Apache/XAMPP

---

## 📦 Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18+ (para build de assets)
- MySQL 8.0+
- Git

---

## 🚀 Instalação e Setup

### 1. Clonar o repositório

\`\`\`bash
git clone https://github.com/elizaelramos/funec_tjd.git
cd funec_tjd
\`\`\`

### 2. Instalar dependências PHP

\`\`\`bash
composer install
\`\`\`

### 3. Instalar dependências Node.js

\`\`\`bash
npm install
\`\`\`

### 4. Configurar arquivo \`.env\`

\`\`\`bash
cp .env.example .env
php artisan key:generate
\`\`\`

### 5. Criar banco de dados

\`\`\`bash
mysql -u root -p -e "CREATE DATABASE funec_tjd CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
\`\`\`

### 6. Executar migrations

\`\`\`bash
php artisan migrate
\`\`\`

### 7. Build dos assets

\`\`\`bash
npm run build
\`\`\`

---

## 📚 Documentação do Fluxo

A documentação completa do fluxo de processos está disponível em:

- **[LEIA-ME-PRIMEIRO.md](LEIA-ME-PRIMEIRO.md)** - Guia de navegação
- **[RESUMO_FLUXO.md](RESUMO_FLUXO.md)** - Visão executiva (5-10 min)
- **[FLUXO_PROCESSO_TRIBUNAL.md](FLUXO_PROCESSO_TRIBUNAL.md)** - Documentação completa
- **[GUIA_VISUAL_FLUXO.md](GUIA_VISUAL_FLUXO.md)** - Guia visual para impressão
- **[VALIDACAO_TRIBUNAL.md](VALIDACAO_TRIBUNAL.md)** - Formulário de validação

---

## 📄 Licença

Projeto do Tribunal de Justiça Desportiva do Futebol Estadual do Mato Grosso do Sul (TJD · FUNEC).

---

## 👨‍💼 Desenvolvedor

**Eliza Ramos**  
📧 elizaelramos@gmail.com

---

**Desenvolvido com ❤️ para o desporto profissional**
