const { PrismaClient } = require('@prisma/client')
const prisma = new PrismaClient()

async function main() {
  console.log('🌱 Iniciando seed...')

  await prisma.candidatura.deleteMany()
  await prisma.vaga.deleteMany()
  await prisma.aluno.deleteMany()

  const aluno1 = await prisma.aluno.create({
    data: { nome: 'Maria Silva', email: 'maria@unialfa.com', curso: 'Sistemas para Internet', periodo: 3 }
  })
  const aluno2 = await prisma.aluno.create({
    data: { nome: 'João Souza', email: 'joao@unialfa.com', curso: 'Ciência da Computação', periodo: 4 }
  })

  const vaga1 = await prisma.vaga.create({
    data: { titulo: 'Desenvolvedor Web Junior', empresa: 'TechSoft', descricao: 'Desenvolvimento de sistemas web', area: 'TI', cargaHoraria: 20, remuneracao: 800, status: 'ABERTA' }
  })
  const vaga2 = await prisma.vaga.create({
    data: { titulo: 'Estagiário de Suporte', empresa: 'InfoSystems', descricao: 'Suporte técnico a usuários', area: 'Suporte', cargaHoraria: 30, remuneracao: 600, status: 'ABERTA' }
  })
  await prisma.vaga.create({
    data: { titulo: 'Analista de Dados', empresa: 'DataCorp', descricao: 'Análise de relatórios e dashboards', area: 'BI', cargaHoraria: 20, remuneracao: 900, status: 'FECHADA' }
  })

  await prisma.candidatura.create({ data: { alunoId: aluno1.id, vagaId: vaga1.id } })
  await prisma.candidatura.create({ data: { alunoId: aluno2.id, vagaId: vaga1.id } })
  await prisma.candidatura.create({ data: { alunoId: aluno1.id, vagaId: vaga2.id } })

  console.log('✅ Seed concluído!')
}

main()
  .catch(e => { console.error(e); process.exit(1) })
  .finally(() => prisma.$disconnect())
