const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')

const prisma = new PrismaClient()

const vagaSchema = z.object({
  empresaId: z.number().int().positive('ID da empresa inválido'),
  titulo: z.string().min(3, 'Título deve ter no mínimo 3 caracteres'),
  descricao: z.string().min(10, 'Descrição deve ter no mínimo 10 caracteres'),
  area: z.string().min(2, 'Área obrigatória'),
  cargaHoraria: z.number().int().min(1).max(40),
  remuneracao: z.number().positive().optional(),
  status: z.enum(['ABERTA', 'FECHADA']).default('ABERTA')
})

async function listar(req, res, next) {
  try {
    const { status, area } = req.query

    const vagas = await prisma.vaga.findMany({
      where: {
        ...(status && { status }),
        ...(area && { area: { contains: area } })
      },
      include: {
        empresa: { select: { id: true, nome: true, email: true } },
        _count: { select: { candidaturas: true } }
      },
      orderBy: { createdAt: 'desc' }
    })

    res.json(vagas)
  } catch (err) {
    next(err)
  }
}

async function buscarPorId(req, res, next) {
  try {
    const vaga = await prisma.vaga.findUniqueOrThrow({
      where: { id: Number(req.params.id) },
      include: {
        empresa: true,
        candidaturas: {
          include: { aluno: { select: { id: true, nome: true, email: true, curso: true } } }
        }
      }
    })

    res.json(vaga)
  } catch (err) {
    next(err)
  }
}

async function criar(req, res, next) {
  try {
    const dados = vagaSchema.parse(req.body)
    const vaga = await prisma.vaga.create({
      data: dados,
      include: { empresa: { select: { nome: true } } }
    })
    res.status(201).json(vaga)
  } catch (err) {
    next(err)
  }
}

async function atualizar(req, res, next) {
  try {
    const dados = vagaSchema.partial().parse(req.body)
    const vaga = await prisma.vaga.update({
      where: { id: Number(req.params.id) },
      data: dados
    })
    res.json(vaga)
  } catch (err) {
    next(err)
  }
}

async function remover(req, res, next) {
  try {
    await prisma.vaga.delete({ where: { id: Number(req.params.id) } })
    res.status(204).send()
  } catch (err) {
    next(err)
  }
}

module.exports = { listar, buscarPorId, criar, atualizar, remover }
