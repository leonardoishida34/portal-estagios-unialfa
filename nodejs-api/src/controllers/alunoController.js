const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')
const prisma = new PrismaClient()

const alunoSchema = z.object({
  ra:   z.string().length(8, 'RA deve ter exatamente 8 caracteres'),
  nome: z.string().min(3, 'Nome obrigatorio'),
  curso: z.string().min(3, 'Curso obrigatorio'),
  apto: z.boolean().default(true)
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

async function buscarPorRa(req, res, next) {
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
    const aluno = await prisma.aluno.update({ where: { ra: req.params.ra }, data: dados })
    res.json(aluno)
  } catch (err) { next(err) }
}

async function remover(req, res, next) {
  try {
    await prisma.candidatura.deleteMany({ where: { alunoRa: req.params.ra } })
    await prisma.aluno.delete({ where: { ra: req.params.ra } })
    res.status(204).send()
  } catch (err) { next(err) }
}

module.exports = { listar, buscarPorRa, criar, atualizar, remover }
