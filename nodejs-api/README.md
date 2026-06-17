# Portal de Estágios - API Node.js

Stack: Node.js · Express · Prisma · Zod · CORS · MySQL

## Como rodar

```bash
npm install
cp .env.example .env
# Edita o .env com sua senha do MySQL
npm run db:push
npm run db:seed
npm run dev
```

## Endpoints

### Alunos (PK = ra)
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /alunos | Lista todos |
| GET | /alunos/:ra | Busca por RA |
| POST | /alunos | Cadastra |
| PUT | /alunos/:ra | Atualiza |
| DELETE | /alunos/:ra | Remove (apaga candidaturas primeiro) |

### Empresas
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /empresas | Lista todas (filtro: ?aprovada=true) |
| GET | /empresas/:id | Busca por ID |
| POST | /empresas | Cadastra |
| PUT | /empresas/:id | Atualiza |
| DELETE | /empresas/:id | Remove |

### Vagas
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /vagas | Lista todas (filtro: ?ativa=true) |
| GET | /vagas/:id | Busca por ID |
| POST | /vagas | Cria |
| PUT | /vagas/:id | Atualiza |
| DELETE | /vagas/:id | Remove |

### Candidaturas
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /candidaturas | Lista todas |
| GET | /candidaturas/:id | Busca por ID |
| POST | /candidaturas | Cria (valida aluno apto e vaga ativa) |
| PATCH | /candidaturas/:id/status | Atualiza status |
| DELETE | /candidaturas/:id | Remove |

### Usuários
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /usuarios | Lista todos |
| POST | /usuarios/login | Autentica |
| POST | /usuarios | Cadastra |
| DELETE | /usuarios/:id | Remove |
