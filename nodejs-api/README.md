# Portal de Estágios - API Node.js

API RESTful do Portal de Estágios UniALFA.  
Stack: **Node.js · Express · Prisma · Zod · CORS · SQLite**

---

## Como rodar

```bash
npm install
cp .env.example .env
npm run db:migrate
npm run db:seed
npm run dev
```

API disponível em: `http://localhost:3000`

---

## Endpoints

### Empresas
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /empresas | Lista todas as empresas |
| GET | /empresas/:id | Busca empresa (inclui vagas) |
| POST | /empresas | Cadastra uma empresa |
| PUT | /empresas/:id | Atualiza uma empresa |
| DELETE | /empresas/:id | Remove uma empresa |

### Vagas
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /vagas | Lista todas as vagas |
| GET | /vagas/:id | Busca vaga (inclui empresa e candidaturas) |
| POST | /vagas | Cria uma vaga |
| PUT | /vagas/:id | Atualiza uma vaga |
| DELETE | /vagas/:id | Remove uma vaga |

### Candidaturas
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /candidaturas | Lista todas |
| GET | /candidaturas/:id | Busca pelo ID |
| POST | /candidaturas | Cria uma candidatura |
| PATCH | /candidaturas/:id/status | Atualiza status |
| DELETE | /candidaturas/:id | Remove |

### Alunos
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /alunos | Lista todos |
| GET | /alunos/:id | Busca aluno (inclui candidaturas) |
| POST | /alunos | Cadastra um aluno |
| PUT | /alunos/:id | Atualiza um aluno |
| DELETE | /alunos/:id | Remove |
