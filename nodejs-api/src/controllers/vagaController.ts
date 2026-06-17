import { Request, Response, NextFunction } from 'express'
import { PrismaClient } from '@prisma/client'
import { z } from 'zod'

const prisma = new PrismaClient()

const vagaSchema = z.object({
  titulo:    z.string().min(3, 'Titulo obrigatorio'),
  descricao: z.string().min(5, 'Descricao obrigatoria'),
  bolsa:     z.number().positive().optional(),
  empresaId: z.number().int().positive('ID da empresa invalido'),
  ativa:     z.boolean().default(true)
})

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { ativa } = req.query
    const vagas = await prisma.vaga.findMany({
      where: { ...(ativa !== undefined && { ativa: ativa === 'true' }) },
      include: {
        empresa: { select: { id: true, razaoSocial: true, aprovada: true } },
        _count: { select: { candidaturas: true } }
      },
      orderBy: { id: 'desc' }
    })
    res.json(vagas)
  } catch (err) { next(err) }
}

export async function buscarPorId(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const vaga = await prisma.vaga.findUniqueOrThrow({
      where: { id: BigInt(req.params.id) },
      include: {
        empresa: true,
        candidaturas: {
          include: { aluno: { select: { ra: true, nome: true, curso: true, apto: true } } }
        }
      }
    })
    res.json(vaga)
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = vagaSchema.parse(req.body)
    const vaga = await prisma.vaga.create({
      data: { ...dados, empresaId: BigInt(dados.empresaId) },
      include: { empresa: { select: { razaoSocial: true } } }
    })
    res.status(201).json(vaga)
  } catch (err) { next(err) }
}

export async function atualizar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = vagaSchema.partial().parse(req.body)
    const vaga = await prisma.vaga.update({
      where: { id: BigInt(req.params.id) },
      data: { ...dados, ...(dados.empresaId && { empresaId: BigInt(dados.empresaId) }) }
    })
    res.json(vaga)
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await prisma.vaga.delete({ where: { id: BigInt(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}
