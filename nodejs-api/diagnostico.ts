#!/usr/bin/env ts-node
import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

async function verificarCandidaturas() {
  console.log('\n' + '='.repeat(80))
  console.log('DIAGNÓSTICO DE CANDIDATURAS - PORTAL ESTÁGIOS')
  console.log('='.repeat(80) + '\n')

  try {
    // 1. Alunos
    console.log('📚 ALUNOS:')
    const alunos = await prisma.aluno.findMany()
    alunos.forEach(a => {
      console.log(`  RA: ${a.ra} | Nome: ${a.nome} | Apto: ${a.apto ? '✅' : '❌'}`)
    })

    // 2. Vagas
    console.log('\n💼 VAGAS:')
    const vagas = await prisma.vaga.findMany()
    vagas.forEach(v => {
      console.log(`  ID: ${v.id} | Título: ${v.titulo} | Ativa: ${v.ativa ? '✅' : '❌'} | Empresa ID: ${v.empresaId}`)
    })

    // 3. Empresas
    console.log('\n🏢 EMPRESAS:')
    const empresas = await prisma.empresa.findMany()
    empresas.forEach(e => {
      console.log(`  ID: ${e.id} | Razão Social: ${e.razaoSocial}`)
    })

    // 4. Candidaturas brutas
    console.log('\n📋 CANDIDATURAS (TODAS):')
    const candidaturasRaw = await prisma.candidatura.findMany()
    if (candidaturasRaw.length === 0) {
      console.log('  ❌ Nenhuma candidatura encontrada!')
    } else {
      candidaturasRaw.forEach(c => {
        console.log(`  ID: ${c.id} | Aluno RA: ${c.alunoRa} | Vaga ID: ${c.vagaId} | Status: ${c.status}`)
      })
    }

    // 5. Candidaturas completas (com relações)
    console.log('\n✅ CANDIDATURAS COMPLETAS (com dados relacionados):')
    const candidaturasCompletas = await prisma.candidatura.findMany({
      include: {
        aluno: { select: { ra: true, nome: true, apto: true } },
        vaga: { select: { id: true, titulo: true, ativa: true, empresa: { select: { razaoSocial: true } } } }
      }
    })
    if (candidaturasCompletas.length === 0) {
      console.log('  ❌ Nenhuma candidatura encontrada!')
    } else {
      candidaturasCompletas.forEach(c => {
        console.log(`  ID: ${c.id}`)
        console.log(`    Aluno: ${c.aluno?.nome} (RA: ${c.alunoRa}, Apto: ${c.aluno?.apto ? '✅' : '❌'})`)
        console.log(`    Vaga: ${c.vaga?.titulo} (ID: ${c.vagaId}, Ativa: ${c.vaga?.ativa ? '✅' : '❌'})`)
        console.log(`    Empresa: ${c.vaga?.empresa?.razaoSocial}`)
        console.log(`    Status: ${c.status}`)
        console.log('')
      })
    }

    // 6. Análise de problemas
    console.log('\n🔍 ANÁLISE DE PROBLEMAS:')
    for (const cand of candidaturasRaw) {
      const aluno = alunos.find(a => a.ra === cand.alunoRa)
      const vaga = vagas.find(v => v.id === cand.vagaId)

      if (!aluno) {
        console.log(`  ⚠️  Candidatura ${cand.id}: Aluno ${cand.alunoRa} NÃO EXISTE`)
      } else if (!aluno.apto) {
        console.log(`  ⚠️  Candidatura ${cand.id}: Aluno ${aluno.nome} NÃO ESTÁ APTO`)
      }

      if (!vaga) {
        console.log(`  ⚠️  Candidatura ${cand.id}: Vaga ${cand.vagaId} NÃO EXISTE`)
      } else if (!vaga.ativa) {
        console.log(`  ⚠️  Candidatura ${cand.id}: Vaga "${vaga.titulo}" NÃO ESTÁ ATIVA`)
      } else {
        const empresa = empresas.find(e => e.id === vaga.empresaId)
        if (!empresa) {
          console.log(`  ⚠️  Candidatura ${cand.id}: Empresa ${vaga.empresaId} NÃO EXISTE`)
        }
      }
    }

    console.log('\n' + '='.repeat(80) + '\n')

  } catch (error) {
    console.error('❌ Erro:', error)
  } finally {
    await prisma.$disconnect()
  }
}

verificarCandidaturas()
