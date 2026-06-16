const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')

const prisma = new PrismaClient()

const alunoSchema = z.object({
  nome: z.string().min(3, 'Nome deve ter no mínimo 3 caracteres'),
  email: z.string().email('E-mail inválido'),
  curso: z.string().min(3, 'Curso obrigatório'),
  periodo: z.number().int().min(1).max(10)
})

async function listar(req, res, next) {
  try {
    const alunos = await prisma.aluno.findMany({
      include: { _count: { select: { candidaturas: true } } },
      orderBy: { nome: 'asc' }
    })
    res.json(alunos)
  } catch (err) { next(err) }
}

async function buscarPorId(req, res, next) {
  try {
    const aluno = await prisma.aluno.findUniqueOrThrow({
      where: { id: Number(req.params.id) },
      include: {
        candidaturas: {
          include: { vaga: { select: { titulo: true, area: true, status: true, empresa: { select: { nome: true } } } } }
        }
      }
    })
    res.json(aluno)
  } catch (err) { next(err) }
}

async function criar(req, res, next) {
  try {
    const dados = alunoSchema.parse(req.body)
    const aluno = await prisma.aluno.create({ data: dados })
    res.status(201).json(aluno)
  } catch (err) { next(err) }
}

async function atualizar(req, res, next) {
  try {
    const dados = alunoSchema.partial().parse(req.body)
    const aluno = await prisma.aluno.update({ where: { id: Number(req.params.id) }, data: dados })
    res.json(aluno)
  } catch (err) { next(err) }
}

async function remover(req, res, next) {
  try {
    await prisma.aluno.delete({ where: { id: Number(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}

module.exports = { listar, buscarPorId, criar, atualizar, remover }
