# 🎓 Portal de Estágios UniALFA

> Conectando Talentos às Oportunidades Locais

Projeto desenvolvido para o Hackathon Institucional do 3º Período do curso de **Tecnologia em Sistemas para Internet** da Faculdade UniALFA – Umuarama/PR.

---

## 📌 Descrição do Problema

A Faculdade UniALFA e as empresas da região não possuem um canal centralizado para divulgação e gerenciamento de vagas de estágio. O processo atual é burocrático e disperso, dificultando tanto a busca dos alunos por oportunidades quanto o gerenciamento das empresas parceiras.

## 💡 Solução Proposta

Um **Portal de Estágios** interativo com arquitetura distribuída, composto por três módulos integrados:

- **Back Office Institucional** (Java/Swing): gerenciamento administrativo pela UniALFA
- **API RESTful** (Node.js): motor central com regras de negócio
- **Front-end Web** (PHP OO): portal do aluno e painel da empresa

---

## 🎯 Objetivos

- Centralizar as vagas de estágio em uma plataforma única e acessível
- Permitir que empresas gerenciem suas vagas de forma autônoma
- Permitir que alunos visualizem vagas e acompanhem candidaturas
- Fornecer à equipe da UniALFA controle administrativo completo do ecossistema

---

## 🏗️ Arquitetura

```
┌─────────────────────┐        ┌──────────────────────┐
│  AMBIENTE LOCAL     │        │  AMBIENTE WEB PÚBLICO │
│                     │        │                       │
│  Java Swing         │        │  Portal do Aluno      │
│  (Back Office       │        │  (PHP OO)             │
│   UniALFA)          │        │                       │
│        │            │        │  Painel da Empresa    │
│        │ JDBC       │        │  (PHP OO)             │
└────────┼────────────┘        └──────────┬────────────┘
         │                                │ HTTP
         ▼                                ▼
    ┌─────────────────────────────────────────┐
    │           API RESTful (Node.js)          │
    │     Controllers | Services | Repos       │
    └───────────────────┬─────────────────────┘
                        │ ORM/Query
                        ▼
                  ┌──────────────┐
                  │  MySQL DB    │
                  │ (Vagas,      │
                  │  Alunos,     │
                  │  Empresas,   │
                  │  Candidat.)  │
                  └──────────────┘
```

---

## 🛠️ Tecnologias Utilizadas

| Módulo | Tecnologia |
|---|---|
| Back Office | Java, Maven, Java Swing, MySQL Connector |
| API | Node.js, Express, Zod, Migrations, Seeds |
| Front-end | PHP OO, HTML, CSS |
| Banco de Dados | MySQL |
| Versionamento | Git, GitHub |

---

## 🚀 Instalação e Execução Local

### Pré-requisitos

- Java 17+, Maven
- Node.js 18+, npm
- PHP 8.1+
- MySQL 8.0+

### 1. Clone o repositório

```bash
git clone https://github.com/leonardoishida34/portal-estagios-unialfa.git
cd portal-estagios-unialfa
```

### 2. Banco de Dados

```bash
# Crie o banco no MySQL
mysql -u root -p -e "CREATE DATABASE portal_estagios;"
```

### 3. API Node.js

```bash
cd nodejs-api
npm install
cp .env.example .env
# Configure as variáveis no .env (DB_HOST, DB_USER, DB_PASS, etc.)
npm run migrate
npm run seed
npm run dev
# API disponível em http://localhost:3000
```

### 4. Back Office Java

```bash
cd java-backoffice
mvn clean install
mvn exec:java
# Ou abra o projeto no IntelliJ/Eclipse e execute o main
```

### 5. Front-end PHP

```bash
cd php-frontend
# Configure o servidor PHP apontando para esta pasta
php -S localhost:8080
# Acesse http://localhost:8080
```

---

## 📁 Estrutura do Projeto

```
portal-estagios-unialfa/
├── README.md
├── .gitignore
├── java-backoffice/          → criado pelo responsável do módulo Java
├── nodejs-api/               → criado pelo responsável do módulo Node.js
├── php-frontend/             → criado pelo responsável do módulo PHP
└── docs/
    └── guia-estilo.md
```

---

## 👥 Equipe e Contribuições

| Nome | RA | Responsabilidade |
|---|---|---|
| [Nome 1] | [RA] | Java – Back Office Institucional |
| [Nome 2] | [RA] | Node.js – API RESTful |
| [Nome 3] | [RA] | PHP – Front-end Web |
| [Leonardo Hitoshi Ishida] | [250282] | DevOps, Git e Documentação |
| [Nome 5] | [RA] | UX, Figma e protótipos |

---

## 🧪 Evidências de Testes

> *(Adicionar prints e descrições conforme funcionalidades forem implementadas)*

- [ ] CRUD de Vagas via API
- [ ] Candidatura pelo Portal do Aluno
- [ ] Aprovação de empresa pelo Back Office Java
- [ ] Notificação de status de candidatura
- [ ] Importação de alunos via .txt

---

## 📄 Licença

Projeto acadêmico desenvolvido para fins avaliativos – Hackathon UniALFA 2026.
