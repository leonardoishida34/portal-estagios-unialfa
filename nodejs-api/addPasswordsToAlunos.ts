import { PrismaClient } from '@prisma/client'
import bcryptjs from 'bcryptjs'

const prisma = new PrismaClient()

async function addPasswordsToAlunos() {
  try {
    console.log('🔍 Buscando alunos sem senha...')
    
    const alunosSemSenha = await prisma.aluno.findMany({
      where: { senha: null }
    })

    if (alunosSemSenha.length === 0) {
      console.log('✅ Todos os alunos já possuem senha cadastrada!')
      return
    }

    console.log(`📋 Encontrados ${alunosSemSenha.length} aluno(s) sem senha\n`)

    for (const aluno of alunosSemSenha) {
      // Cria senha padrão: aluno + RA (ex: aluno25041700)
      const senhaPadrao = `aluno${aluno.ra}`
      const senhaHash = await bcryptjs.hash(senhaPadrao, 10)

      await prisma.aluno.update({
        where: { ra: aluno.ra },
        data: { senha: senhaHash }
      })

      console.log(`✅ ${aluno.ra} - ${aluno.nome}`)
      console.log(`   Senha padrão: "${senhaPadrao}"`)
    }

    console.log(`\n🎉 Sucesso! ${alunosSemSenha.length} aluno(s) atualizado(s)`)
    console.log('\n📝 Os alunos podem fazer login com:')
    console.log('   - Usuário: RA (8 dígitos)')
    console.log('   - Senha: aluno + RA')
    console.log('   Exemplo: RA 25041700 → Senha: aluno25041700')

  } catch (error) {
    console.error('❌ Erro ao atualizar alunos:', error)
    process.exit(1)
  } finally {
    await prisma.$disconnect()
  }
}

addPasswordsToAlunos()
