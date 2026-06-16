const { PrismaClient } = require('@prisma/client')
const prisma = new PrismaClient()

async function main() {
  console.log('🌱 Iniciando seed...')

  await prisma.candidatura.deleteMany()
  await prisma.vaga.deleteMany()
  await prisma.empresa.deleteMany()
  await prisma.aluno.deleteMany()

  // Empresas
  const empresa1 = await prisma.empresa.create({
    data: { nome: 'TechSoft', cnpj: '12.345.678/0001-90', area: 'TI', email: 'rh@techsoft.com', telefone: '(44) 3333-1111' }
  })
  const empresa2 = await prisma.empresa.create({
    data: { nome: 'InfoSystems', cnpj: '98.765.432/0001-10', area: 'Suporte', email: 'rh@infosystems.com', telefone: '(44) 3333-2222' }
  })
  const empresa3 = await prisma.empresa.create({
    data: { nome: 'DataCorp', cnpj: '11.222.333/0001-44', area: 'BI', email: 'rh@datacorp.com' }
  })

  // Alunos
  const aluno1 = await prisma.aluno.create({
    data: { nome: 'Maria Silva', email: 'maria@unialfa.com', curso: 'Sistemas para Internet', periodo: 3 }
  })
  const aluno2 = await prisma.aluno.create({
    data: { nome: 'João Souza', email: 'joao@unialfa.com', curso: 'Ciência da Computação', periodo: 4 }
  })

  // Vagas
  const vaga1 = await prisma.vaga.create({
    data: { empresaId: empresa1.id, titulo: 'Desenvolvedor Web Junior', descricao: 'Desenvolvimento de sistemas web', area: 'TI', cargaHoraria: 20, remuneracao: 800 }
  })
  const vaga2 = await prisma.vaga.create({
    data: { empresaId: empresa2.id, titulo: 'Estagiário de Suporte', descricao: 'Suporte técnico a usuários', area: 'Suporte', cargaHoraria: 30, remuneracao: 600 }
  })
  await prisma.vaga.create({
    data: { empresaId: empresa3.id, titulo: 'Analista de Dados', descricao: 'Análise de relatórios e dashboards', area: 'BI', cargaHoraria: 20, remuneracao: 900, status: 'FECHADA' }
  })

  // Candidaturas
  await prisma.candidatura.create({ data: { alunoId: aluno1.id, vagaId: vaga1.id } })
  await prisma.candidatura.create({ data: { alunoId: aluno2.id, vagaId: vaga1.id } })
  await prisma.candidatura.create({ data: { alunoId: aluno1.id, vagaId: vaga2.id } })

  console.log('✅ Seed concluído!')
}

main()
  .catch(e => { console.error(e); process.exit(1) })
  .finally(() => prisma.$disconnect())
