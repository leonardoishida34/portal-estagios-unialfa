# Portal de Estágios - API Node.js

API RESTful do Portal de Estágios UniALFA.  
Stack: **Node.js · Express · Prisma · Zod · CORS · SQLite**

---

## Como rodar

```bash
# 1. Instalar dependências
npm install

# 2. Copiar o arquivo de variáveis de ambiente
cp .env.example .env

# 3. Criar o banco e rodar as migrations
npm run db:migrate

# 4. Popular o banco com dados de exemplo
npm run db:seed

# 5. Iniciar o servidor
npm run dev
```

API disponível em: `http://localhost:3000`

---

## Endpoints

### Vagas

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /vagas | Lista todas as vagas |
| GET | /vagas/:id | Busca uma vaga pelo ID |
| POST | /vagas | Cria uma nova vaga |
| PUT | /vagas/:id | Atualiza uma vaga |
| DELETE | /vagas/:id | Remove uma vaga |

**Filtros disponíveis:**
```
GET /vagas?status=ABERTA
GET /vagas?area=TI
```

**Corpo do POST/PUT:**
```json
{
  "titulo": "Desenvolvedor Web Junior",
  "empresa": "TechSoft",
  "descricao": "Desenvolvimento de sistemas web com JavaScript",
  "area": "TI",
  "cargaHoraria": 20,
  "remuneracao": 800,
  "status": "ABERTA"
}
```

---

### Candidaturas

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /candidaturas | Lista todas as candidaturas |
| GET | /candidaturas/:id | Busca uma candidatura pelo ID |
| POST | /candidaturas | Cria uma candidatura |
| PATCH | /candidaturas/:id/status | Atualiza o status |
| DELETE | /candidaturas/:id | Remove uma candidatura |

**Corpo do POST:**
```json
{
  "alunoId": 1,
  "vagaId": 2
}
```

**Corpo do PATCH /status:**
```json
{
  "status": "APROVADA"
}
```
> Status possíveis: `PENDENTE`, `APROVADA`, `REPROVADA`

---

### Alunos

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | /alunos | Lista todos os alunos |
| GET | /alunos/:id | Busca um aluno pelo ID |
| POST | /alunos | Cadastra um aluno |
| PUT | /alunos/:id | Atualiza um aluno |
| DELETE | /alunos/:id | Remove um aluno |

**Corpo do POST/PUT:**
```json
{
  "nome": "Maria Silva",
  "email": "maria@unialfa.com",
  "curso": "Sistemas para Internet",
  "periodo": 3
}
```
