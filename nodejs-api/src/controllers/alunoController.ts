import { Request, Response, NextFunction } from 'express'
import { PrismaClient } from '@prisma/client'
import { z } from 'zod'

const prisma = new PrismaClient()

const alunoSchema = z.object({
  ra:    z.string().length(8, 'RA deve ter exatamente 8 caracteres'),
  nome:  z.string().min(3, 'Nome obrigatorio'),
  curso: z.string().min(3, 'Curso obrigatorio'),
  apto:  z.boolean().default(true)
})

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const alunos = await prisma.aluno.findMany({
      include: { _count: { select: { candidaturas: true } } },
      orderBy: { nome: 'asc' }
    })
    res.json(alunos)
  } catch (err) { next(err) }
}

export async function buscarPorRa(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const aluno = await prisma.aluno.findUniqueOrThrow({
      where: { ra: req.params.ra },
      include: {
        candidaturas: {
          include: { vaga: { select: { titulo: true, ativa: true, empresa: { select: { razaoSocial: true } } } } }
        }
      }
    })
    res.json(aluno)
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = alunoSchema.parse(req.body)
    const aluno = await prisma.aluno.create({ data: dados })
    res.status(201).json(aluno)
  } catch (err) { next(err) }
}

export async function atualizar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = alunoSchema.partial().parse(req.body)
    const aluno = await prisma.aluno.update({ where: { ra: req.params.ra }, data: dados })
    res.json(aluno)
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await prisma.candidatura.deleteMany({ where: { alunoRa: req.params.ra } })
    await prisma.aluno.delete({ where: { ra: req.params.ra } })
    res.status(204).send()
  } catch (err) { next(err) }
}
