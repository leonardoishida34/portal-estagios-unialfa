const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')
const prisma = new PrismaClient()

const empresaSchema = z.object({
  razaoSocial: z.string().min(2, 'Razao social obrigatoria'),
  cnpj:        z.string().min(14, 'CNPJ invalido'),
  email:       z.string().email('E-mail invalido'),
  telefone:    z.string().optional(),
  aprovada:    z.boolean().default(false)
})

async function listar(req, res, next) {
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

async function buscarPorId(req, res, next) {
  try {
    const empresa = await prisma.empresa.findUniqueOrThrow({
      where: { id: BigInt(req.params.id) },
      include: { vagas: { select: { id: true, titulo: true, ativa: true, bolsa: true } } }
    })
    res.json(empresa)
  } catch (err) { next(err) }
}

async function criar(req, res, next) {
  try {
    const dados = empresaSchema.parse(req.body)
    const empresa = await prisma.empresa.create({ data: dados })
    res.status(201).json(empresa)
  } catch (err) { next(err) }
}

async function atualizar(req, res, next) {
  try {
    const dados = empresaSchema.partial().parse(req.body)
    const empresa = await prisma.empresa.update({ where: { id: BigInt(req.params.id) }, data: dados })
    res.json(empresa)
  } catch (err) { next(err) }
}

async function remover(req, res, next) {
  try {
    await prisma.empresa.delete({ where: { id: BigInt(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}

module.exports = { listar, buscarPorId, criar, atualizar, remover }
