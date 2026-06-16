const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')
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

async function listar(req, res, next) {
  try {
    const usuarios = await prisma.usuario.findMany({
      select: { id: true, nome: true, login: true, perfil: true },
      orderBy: { nome: 'asc' }
    })
    res.json(usuarios)
  } catch (err) { next(err) }
}

async function login(req, res, next) {
  try {
    const { login, senha } = loginSchema.parse(req.body)
    const usuario = await prisma.usuario.findFirst({
      where: { login, senha },
      select: { id: true, nome: true, login: true, perfil: true }
    })
    if (!usuario) return res.status(401).json({ error: 'Login ou senha invalidos' })
    res.json({ mensagem: 'Login realizado com sucesso', usuario })
  } catch (err) { next(err) }
}

async function criar(req, res, next) {
  try {
    const dados = usuarioSchema.parse(req.body)
    const usuario = await prisma.usuario.create({
      data: dados,
      select: { id: true, nome: true, login: true, perfil: true }
    })
    res.status(201).json(usuario)
  } catch (err) { next(err) }
}

async function remover(req, res, next) {
  try {
    await prisma.usuario.delete({ where: { id: BigInt(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}

module.exports = { listar, login, criar, remover }
