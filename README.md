# Portal de Estágios UniALFA

> Conectando Talentos às Oportunidades Locais

Projeto desenvolvido para o Hackathon Institucional do 3º Período do curso de **Tecnologia em Sistemas para Internet** da Faculdade UniALFA – Umuarama/PR.

---

## Descrição do Problema

A Faculdade UniALFA e as empresas da região não possuem um canal centralizado para divulgação e gerenciamento de vagas de estágio. O processo atual é burocrático e disperso, dificultando tanto a busca dos alunos por oportunidades quanto o gerenciamento das empresas parceiras.

## Solução Proposta

Um **Portal de Estágios** com arquitetura distribuída, composto por três módulos integrados:

- **Back Office Institucional (Java/Swing)**: gerenciamento administrativo pela UniALFA
- **API RESTful (Node.js)**: motor central com regras de negócio, autenticação e notificações
- **Portal Web (PHP OO)**: portal do aluno e painel da empresa

---

## Objetivos

- Centralizar as vagas de estágio em uma plataforma única e acessível
- Permitir que empresas gerenciem suas vagas de forma autônoma
- Permitir que alunos visualizem vagas, se candidatem e acompanhem candidaturas em tempo real
- Fornecer à equipe da UniALFA controle administrativo completo (aprovação, importação, relatórios)

---

## Arquitetura

```
┌─────────────────────┐     JDBC      ┌────────────────────────────┐
│  Backoffice Java     │──────────────▶│                            │
│  (Swing Desktop)     │               │        MySQL DB             │
└─────────────────────┘               │     portal_estagios        │
                                       │                            │
┌─────────────────────┐  HTTP/REST    │                            │
│  Portal PHP          │──────────────▶│   Node.js API (Express)   │
│  (Alunos/Empresas)   │  localhost:3000│   Controllers|Services   │
└─────────────────────┘               └────────────────────────────┘
```

---

## Tecnologias Utilizadas

| Módulo | Tecnologia |
|--------|------------|
| Back Office | Java, Maven, Swing, MySQL Connector/J |
| API | Node.js, TypeScript, Express, Prisma ORM, Zod, bcryptjs |
| Portal Web | PHP 8+ (OOP), HTML, CSS |
| Banco de Dados | MySQL 8 (via XAMPP) |
| Versionamento | Git, GitHub |

---

## Estrutura do Projeto

```
hackathon-portal-estagios/
├── backoffice-java/          # Aplicação desktop Java (Maven)
│   └── src/
│       ├── dao/              # Data Access Objects (JDBC)
│       ├── model/            # Aluno, Empresa, Vaga, Candidatura, Usuario
│       ├── service/          # Regras de negócio
│       ├── util/             # ConnectionFactory, GeradorRelatorios, ImportadorAlunos
│       └── view/             # Telas Swing
├── nodejs-api/               # API RESTful
│   ├── prisma/               # Schema + seed
│   └── src/
│       ├── controllers/      # Tratamento HTTP
│       ├── services/         # Regras de negócio
│       ├── routes/           # Endpoints
│       ├── middlewares/      # errorHandler
│       └── lib/              # prisma.ts (cliente compartilhado)
└── portal-estagios-php/      # Portal Web PHP
    ├── aluno/                # Vagas, candidaturas, notificações
    ├── empresa/              # Painel, vagas, candidatos
    ├── includes/             # header, footer
    └── src/
        ├── Controllers/
        ├── Services/
        └── Models/
```

---

## Endpoints da API (Node.js — porta 3000)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /api/vagas | Listar vagas (filtro: ?ativa=true&empresa_id=X) |
| POST | /api/vagas | Criar vaga |
| PUT | /api/vagas/:id | Atualizar vaga |
| DELETE | /api/vagas/:id | Remover vaga |
| GET | /api/candidaturas | Listar candidaturas |
| POST | /api/candidaturas | Criar candidatura |
| PATCH | /api/candidaturas/:id/status | Atualizar status |
| DELETE | /api/candidaturas/:id | Remover candidatura |
| GET | /api/alunos | Listar alunos |
| POST | /api/alunos | Criar aluno |
| POST | /api/alunos/auth/login | Autenticar aluno |
| GET | /api/empresas | Listar empresas |
| POST | /api/empresas | Cadastrar empresa |
| POST | /api/empresas/login | Autenticar empresa |
| PUT | /api/empresas/:id | Atualizar empresa |
| GET | /api/notificacoes | Listar notificações (?aluno_ra=X) |
| GET | /api/notificacoes/nao-lidas | Contar não lidas (?aluno_ra=X) |
| PATCH | /api/notificacoes/:id/lida | Marcar notificação como lida |
| PATCH | /api/notificacoes/lidas | Marcar todas como lidas |
| GET | /api/usuarios | Listar usuários do backoffice |
| POST | /api/usuarios/login | Autenticar usuário do backoffice |

---

## Instalação e Execução Local

### Pré-requisitos
- XAMPP (Apache + MySQL) instalado e rodando
- Node.js 18+
- Java 17+ com Maven
- Git

### 1. Clone o repositório
```bash
git clone https://github.com/leonardoishida34/portal-estagios-unialfa.git
cd portal-estagios-unialfa
```

### 2. API Node.js
```bash
cd nodejs-api
npm install
cp .env.example .env          # configurar DATABASE_URL
npx prisma db push
npx prisma db seed
npm run dev                   # http://localhost:3000
```

### 3. Portal PHP
```bash
# Copiar pasta para htdocs do XAMPP e iniciar Apache
# Acessar: http://localhost/hackathon-portal-estagios/portal-estagios-php
```

### 4. Backoffice Java
```bash
cd backoffice-java
mvn package
java -jar target/estagios-backoffice-1.0-SNAPSHOT.jar
```

---

## Funcionalidades Implementadas

### Backoffice Java
- Login com autenticação por perfil (ADMIN/OPERADOR)
- Gestão de Empresas: aprovar, bloquear, excluir
- Gestão de Alunos: cadastrar, editar, controlar aptidão (`apto`)
- Importação de alunos via arquivo `.txt` (formato: `RA;Nome;Curso`)
- Gestão de Vagas e Candidaturas: consulta e alteração de status
- Relatórios `.txt`: Empresas, Alunos, Vagas, Candidaturas

### API Node.js
- CRUD completo para Vagas, Candidaturas, Alunos, Empresas, Usuários
- Sistema de notificações automáticas ao criar candidatura e ao atualizar status
- Validação de entrada com Zod
- Hash de senhas com bcryptjs
- Arquitetura em camadas: Controller → Service → Prisma ORM

### Portal PHP
- Portal do Aluno: listagem de vagas, candidatura com 1 clique, acompanhamento de status
- Notificações em tempo real com ícone de sino e contador de não lidas
- Painel da Empresa: CRUD completo de vagas, aprovação/reprovação de candidatos
- Edição de perfil da empresa
- Cadastro de alunos e empresas

---

## Evidências de Testes

- Candidatura criada pelo portal PHP aparece no backoffice Java após atualizar a lista
- Aprovação/reprovação via painel da empresa gera notificação automática para o aluno
- Relatórios `.txt` gerados com dados reais do banco
- Importação de alunos via arquivo `.txt` validada e funcional

---

## Licença

Projeto acadêmico desenvolvido para fins avaliativos – Hackathon UniALFA 2026.
