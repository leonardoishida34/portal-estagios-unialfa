import { z } from 'zod'
import { prisma } from '../lib/prisma'
import { criarNotificacao } from './notificacaoService'

export const candidaturaSchema = z.object({
  alunoRa: z.string().min(6, 'RA deve ter no mínimo 6 caracteres'),
  vagaId:  z.number().int().positive('ID da vaga invalido')
})

export const statusSchema = z.object({
  status: z.enum(['Pendente', 'Aprovada', 'Reprovada'])
})

export async function listarCandidaturas(filtros?: { vagaId?: number; alunoRa?: string }) {
  const where: Record<string, unknown> = {}
  if (filtros?.vagaId)  where.vagaId  = BigInt(filtros.vagaId)
  if (filtros?.alunoRa) where.alunoRa = filtros.alunoRa

  return prisma.candidatura.findMany({
    where,
    include: {
      aluno: { select: { ra: true, nome: true, curso: true, apto: true } },
      vaga:  { select: { id: true, titulo: true, empresa: { select: { razaoSocial: true } } } }
    },
    orderBy: { id: 'desc' }
  })
}

export async function buscarCandidaturaPorId(id: bigint) {
  return prisma.candidatura.findUniqueOrThrow({
    where: { id },
    include: { aluno: true, vaga: { include: { empresa: true } } }
  })
}

export async function criarCandidatura(body: unknown) {
  const dados = candidaturaSchema.parse(body)

  const aluno = await prisma.aluno.findUnique({ where: { ra: dados.alunoRa } })
  if (!aluno) throw Object.assign(new Error('Aluno nao encontrado'), { statusCode: 404 })
  if (!aluno.apto) throw Object.assign(new Error('Aluno nao esta apto para candidatura'), { statusCode: 400 })

  const vaga = await prisma.vaga.findUnique({ where: { id: BigInt(dados.vagaId) } })
  if (!vaga) throw Object.assign(new Error('Vaga nao encontrada'), { statusCode: 404 })
  if (!vaga.ativa) throw Object.assign(new Error('Vaga nao esta ativa'), { statusCode: 400 })

  const candidatura = await prisma.candidatura.create({
    data: { alunoRa: dados.alunoRa, vagaId: BigInt(dados.vagaId) },
    include: {
      aluno: { select: { nome: true } },
      vaga:  { select: { titulo: true, empresa: { select: { razaoSocial: true } } } }
    }
  })

  await criarNotificacao(
    dados.alunoRa,
    `Sua candidatura para a vaga "${vaga.titulo}" foi recebida e está em análise.`
  )

  return { mensagem: 'Candidatura enviada com sucesso!', candidatura }
}

export async function atualizarStatusCandidatura(id: bigint, body: unknown) {
  const { status } = statusSchema.parse(body)

  const candidatura = await prisma.candidatura.update({
    where: { id },
    data: { status },
    include: {
      aluno: { select: { ra: true, nome: true } },
      vaga:  { select: { titulo: true } }
    }
  })

  const mensagemStatus = status === 'Aprovada'
    ? `Parabéns! Sua candidatura para a vaga "${candidatura.vaga.titulo}" foi APROVADA.`
    : `Sua candidatura para a vaga "${candidatura.vaga.titulo}" foi reprovada.`

  await criarNotificacao(candidatura.aluno.ra, mensagemStatus)

  return { mensagem: `Status atualizado para ${status}`, candidatura }
}

export async function removerCandidatura(id: bigint) {
  return prisma.candidatura.delete({ where: { id } })
}
