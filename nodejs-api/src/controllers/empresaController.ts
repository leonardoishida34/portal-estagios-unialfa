import { Request, Response, NextFunction } from 'express'
import { PrismaClient } from '@prisma/client'
import { z } from 'zod'

const prisma = new PrismaClient()

const empresaSchema = z.object({
  razaoSocial: z.string().min(2, 'Razao social obrigatoria'),
  cnpj:        z.string().min(14, 'CNPJ invalido'),
  email:       z.string().email('E-mail invalido'),
  telefone:    z.string().optional(),
  aprovada:    z.boolean().default(false)
})

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { aprovada } = req.query
    const empresas = await prisma.empresa.findMany({
      where: { ...(aprovada !== undefined && { aprovada: aprovada === 'true' }) },
      include: { _count: { select: { vagas: true } } },
      orderBy: { razaoSocial: 'asc' }
    })
    res.json(empresas)
  } catch (err) { next(err) }
}

export async function buscarPorId(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const empresa = await prisma.empresa.findUniqueOrThrow({
      where: { id: BigInt(req.params.id) },
      include: { vagas: { select: { id: true, titulo: true, ativa: true, bolsa: true } } }
    })
    res.json(empresa)
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = empresaSchema.parse(req.body)
    const empresa = await prisma.empresa.create({ data: dados })
    res.status(201).json(empresa)
  } catch (err) { next(err) }
}

export async function atualizar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = empresaSchema.partial().parse(req.body)
    const empresa = await prisma.empresa.update({ where: { id: BigInt(req.params.id) }, data: dados })
    res.json(empresa)
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await prisma.empresa.delete({ where: { id: BigInt(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}
