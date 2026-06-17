import { Request, Response, NextFunction } from 'express'
import { PrismaClient } from '@prisma/client'
import { z } from 'zod'

const prisma = new PrismaClient()

const usuarioSchema = z.object({
  nome:   z.string().min(3, 'Nome obrigatorio'),
  login:  z.string().min(3, 'Login obrigatorio'),
  senha:  z.string().min(6, 'Senha deve ter no minimo 6 caracteres'),
  perfil: z.enum(['ADMIN', 'OPERADOR'])
})

const loginSchema = z.object({
  login: z.string(),
  senha: z.string()
})

export async function listar(_req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const usuarios = await prisma.usuario.findMany({
      select: { id: true, nome: true, login: true, perfil: true },
      orderBy: { nome: 'asc' }
    })
    res.json(usuarios)
  } catch (err) { next(err) }
}

export async function login(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { login, senha } = loginSchema.parse(req.body)
    const usuario = await prisma.usuario.findFirst({
      where: { login, senha },
      select: { id: true, nome: true, login: true, perfil: true }
    })
    if (!usuario) { res.status(401).json({ error: 'Login ou senha invalidos' }); return }
    res.json({ mensagem: 'Login realizado com sucesso', usuario })
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = usuarioSchema.parse(req.body)
    const usuario = await prisma.usuario.create({
      data: dados,
      select: { id: true, nome: true, login: true, perfil: true }
    })
    res.status(201).json(usuario)
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await prisma.usuario.delete({ where: { id: BigInt(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}
