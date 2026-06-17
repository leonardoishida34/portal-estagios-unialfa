import { Request, Response, NextFunction } from 'express'
import { PrismaClient } from '@prisma/client'
import { z } from 'zod'

const prisma = new PrismaClient()

const candidaturaSchema = z.object({
  alunoRa: z.string().length(8, 'RA deve ter 8 caracteres'),
  vagaId:  z.number().int().positive('ID da vaga invalido')
})

const statusSchema = z.object({
  status: z.enum(['Pendente', 'Aprovada', 'Reprovada'])
})

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const candidaturas = await prisma.candidatura.findMany({
      include: {
        aluno: { select: { ra: true, nome: true, curso: true, apto: true } },
        vaga:  { select: { id: true, titulo: true, empresa: { select: { razaoSocial: true } } } }
      },
      orderBy: { id: 'desc' }
    })
    res.json(candidaturas)
  } catch (err) { next(err) }
}

export async function buscarPorId(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const candidatura = await prisma.candidatura.findUniqueOrThrow({
      where: { id: BigInt(req.params.id) },
      include: { aluno: true, vaga: { include: { empresa: true } } }
    })
    res.json(candidatura)
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = candidaturaSchema.parse(req.body)

    const aluno = await prisma.aluno.findUnique({ where: { ra: dados.alunoRa } })
    if (!aluno) { res.status(404).json({ error: 'Aluno nao encontrado' }); return }
    if (!aluno.apto) { res.status(400).json({ error: 'Aluno nao esta apto para candidatura' }); return }

    const vaga = await prisma.vaga.findUnique({ where: { id: BigInt(dados.vagaId) } })
    if (!vaga) { res.status(404).json({ error: 'Vaga nao encontrada' }); return }
    if (!vaga.ativa) { res.status(400).json({ error: 'Vaga nao esta ativa' }); return }

    const candidatura = await prisma.candidatura.create({
      data: { alunoRa: dados.alunoRa, vagaId: BigInt(dados.vagaId) },
      include: {
        aluno: { select: { nome: true } },
        vaga:  { select: { titulo: true, empresa: { select: { razaoSocial: true } } } }
      }
    })
    res.status(201).json({ mensagem: 'Candidatura enviada com sucesso!', candidatura })
  } catch (err) { next(err) }
}

export async function atualizarStatus(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { status } = statusSchema.parse(req.body)
    const candidatura = await prisma.candidatura.update({
      where: { id: BigInt(req.params.id) },
      data: { status }
    })
    res.json({ mensagem: `Status atualizado para ${status}`, candidatura })
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await prisma.candidatura.delete({ where: { id: BigInt(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}
