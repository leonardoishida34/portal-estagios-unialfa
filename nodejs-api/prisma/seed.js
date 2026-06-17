const { PrismaClient } = require('@prisma/client')
const prisma = new PrismaClient()

async function main() {
  console.log('Iniciando seed...')

  await prisma.candidatura.deleteMany()
  await prisma.vaga.deleteMany()
  await prisma.empresa.deleteMany()
  await prisma.aluno.deleteMany()
  await prisma.usuario.deleteMany()

  await prisma.usuario.createMany({
    data: [
      { nome: 'Administrador Chefe', login: 'admin', senha: '123456', perfil: 'ADMIN' },
      { nome: 'Operador de Dados', login: 'operador', senha: '123456', perfil: 'OPERADOR' },
    ]
  })

  await prisma.aluno.createMany({
    data: [
      { ra: '12345678', nome: 'Joao Silva', curso: 'Sistemas para Internet', apto: true },
      { ra: '87654321', nome: 'Maria Souza', curso: 'Sistemas para Internet', apto: true },
    ]
  })

  const empresa = await prisma.empresa.create({
    data: { razaoSocial: 'Tek Norte Tecnologia', cnpj: '12.345.678/0001-90', email: 'contato@teknorte.com.br', telefone: '(44) 99999-1111', aprovada: true }
  })

  const vaga = await prisma.vaga.create({
    data: { titulo: 'Estagio FULL STACK', descricao: 'Estagio FULL STACK', bolsa: 1799.91, empresaId: empresa.id, ativa: true }
  })

  await prisma.candidatura.create({
    data: { alunoRa: '12345678', vagaId: vaga.id, status: 'Aprovada' }
  })

  console.log('Seed concluido!')
}

main()
  .catch(e => { console.error(e); process.exit(1) })
  .finally(() => prisma.$disconnect())
