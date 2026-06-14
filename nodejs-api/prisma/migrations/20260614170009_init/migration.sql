-- CreateTable
CREATE TABLE "Aluno" (
    "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "nome" TEXT NOT NULL,
    "email" TEXT NOT NULL,
    "curso" TEXT NOT NULL,
    "periodo" INTEGER NOT NULL,
    "createdAt" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- CreateTable
CREATE TABLE "Vaga" (
    "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "titulo" TEXT NOT NULL,
    "empresa" TEXT NOT NULL,
    "descricao" TEXT NOT NULL,
    "area" TEXT NOT NULL,
    "cargaHoraria" INTEGER NOT NULL,
    "remuneracao" REAL,
    "status" TEXT NOT NULL DEFAULT 'ABERTA',
    "createdAt" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- CreateTable
CREATE TABLE "Candidatura" (
    "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "alunoId" INTEGER NOT NULL,
    "vagaId" INTEGER NOT NULL,
    "status" TEXT NOT NULL DEFAULT 'PENDENTE',
    "createdAt" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "Candidatura_alunoId_fkey" FOREIGN KEY ("alunoId") REFERENCES "Aluno" ("id") ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT "Candidatura_vagaId_fkey" FOREIGN KEY ("vagaId") REFERENCES "Vaga" ("id") ON DELETE RESTRICT ON UPDATE CASCADE
);

-- CreateIndex
CREATE UNIQUE INDEX "Aluno_email_key" ON "Aluno"("email");

-- CreateIndex
CREATE UNIQUE INDEX "Candidatura_alunoId_vagaId_key" ON "Candidatura"("alunoId", "vagaId");
