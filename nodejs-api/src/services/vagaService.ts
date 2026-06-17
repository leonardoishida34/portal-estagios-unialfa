import { z } from 'zod'
import { prisma } from '../lib/prisma'

export const vagaSchema = z.object({
  titulo:    z.string().min(3, 'Titulo obrigatorio'),
  descricao: z.string().min(5, 'Descricao obrigatoria'),
  bolsa:     z.number().positive().optional(),
  empresaId: z.number().int().positive('ID da empresa invalido'),
  ativa:     z.boolean().default(true)
})

export async function listarVagas(ativa?: string, empresaId?: string) {
  return prisma.vaga.findMany({
    where: {
      ...(ativa     !== undefined && { ativa: ativa === 'true' }),
      ...(empresaId !== undefined && { empresaId: BigInt(empresaId) })
    },
    include: {
      empresa: { select: { id: true, razaoSocial: true, aprovada: true } },
      _count: { select: { candidaturas: true } }
    },
    orderBy: { id: 'desc' }
  })
}

export async function buscarVagaPorId(id: bigint) {
  return prisma.vaga.findUniqueOrThrow({
    where: { id },
    include: {
      empresa: true,
      candidaturas: {
        include: { aluno: { select: { ra: true, nome: true, curso: true, apto: true } } }
      }
    }
  })
}

export async function criarVaga(body: unknown) {
  const dados = vagaSchema.parse(body)
  return prisma.vaga.create({
    data: { ...dados, empresaId: BigInt(dados.empresaId) },
    include: { empresa: { select: { razaoSocial: true } } }
  })
}

export async function atualizarVaga(id: bigint, body: unknown) {
  const dados = vagaSchema.partial().parse(body)
  return prisma.vaga.update({
    where: { id },
    data: { ...dados, ...(dados.empresaId && { empresaId: BigInt(dados.empresaId) }) }
  })
}

export async function removerVaga(id: bigint) {
  return prisma.vaga.delete({ where: { id } })
}
