generator client {
  provider = "prisma-client-js"
}

datasource db {
  provider = "sqlite"
  url      = env("DATABASE_URL")
}

model Aluno {
  id           Int            @id @default(autoincrement())
  nome         String
  email        String         @unique
  curso        String
  periodo      Int
  createdAt    DateTime       @default(now())
  candidaturas Candidatura[]
}

model Vaga {
  id           Int            @id @default(autoincrement())
  titulo       String
  empresa      String
  descricao    String
  area         String
  cargaHoraria Int
  remuneracao  Float?
  status       String         @default("ABERTA")
  createdAt    DateTime       @default(now())
  candidaturas Candidatura[]
}

model Candidatura {
  id        Int      @id @default(autoincrement())
  alunoId   Int
  vagaId    Int
  status    String   @default("PENDENTE")
  createdAt DateTime @default(now())
  aluno     Aluno    @relation(fields: [alunoId], references: [id])
  vaga      Vaga     @relation(fields: [vagaId], references: [id])

  @@unique([alunoId, vagaId])
}
