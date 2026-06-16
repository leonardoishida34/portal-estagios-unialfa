const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')

const prisma = new PrismaClient()

const empresaSchema = z.object({
  nome: z.string().min(2, 'Nome da empresa obrigatório'),
  cnpj: z.string().min(14, 'CNPJ inválido'),
  area: z.string().min(2, 'Área obrigatória'),
  email: z.string().email('E-mail inválido'),
  telefone: z.string().optional()
})

async function listar(req, res, next) {
  try {
    const { area } = req.query

    const empresas = await prisma.empresa.findMany({
      where: { ...(area && { area: { contains: area } }) },
      include: { _count: { select: { vagas: true } } },
      orderBy: { nome: 'asc' }
    })

    res.json(empresas)
  } catch (err) {
    next(err)
  }
}

async function buscarPorId(req, res, next) {
  try {
    const empresa = await prisma.empresa.findUniqueOrThrow({
      where: { id: Number(req.params.id) },
      include: {
        vagas: {
          select: { id: true, titulo: true, area: true, status: true, cargaHoraria: true, remuneracao: true }
        }
      }
    })

    res.json(empresa)
  } catch (err) {
    next(err)
  }
}

async function criar(req, res, next) {
  try {
    const dados = empresaSchema.parse(req.body)
    const empresa = await prisma.empresa.create({ data: dados })
    res.status(201).json(empresa)
  } catch (err) {
    next(err)
  }
}

async function atualizar(req, res, next) {
  try {
    const dados = empresaSchema.partial().parse(req.body)
    const empresa = await prisma.empresa.update({
      where: { id: Number(req.params.id) },
      data: dados
    })
    res.json(empresa)
  } catch (err) {
    next(err)
  }
}

async function remover(req, res, next) {
  try {
    await prisma.empresa.delete({ where: { id: Number(req.params.id) } })
    res.status(204).send()
  } catch (err) {
    next(err)
  }
}

module.exports = { listar, buscarPorId, criar, atualizar, remover }
