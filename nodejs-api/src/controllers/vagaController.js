const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')
const prisma = new PrismaClient()

const vagaSchema = z.object({
  titulo:    z.string().min(3, 'Titulo obrigatorio'),
  descricao: z.string().min(5, 'Descricao obrigatoria'),
  bolsa:     z.number().positive().optional(),
  empresaId: z.number().int().positive('ID da empresa invalido'),
  ativa:     z.boolean().default(true)
})

async function listar(req, res, next) {
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

async function buscarPorId(req, res, next) {
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

async function criar(req, res, next) {
  try {
    const dados = vagaSchema.parse(req.body)
    const vaga = await prisma.vaga.create({
      data: { ...dados, empresaId: BigInt(dados.empresaId) },
      include: { empresa: { select: { razaoSocial: true } } }
    })
    res.status(201).json(vaga)
  } catch (err) { next(err) }
}

async function atualizar(req, res, next) {
  try {
    const dados = vagaSchema.partial().parse(req.body)
    if (dados.empresaId) dados.empresaId = BigInt(dados.empresaId)
    const vaga = await prisma.vaga.update({ where: { id: BigInt(req.params.id) }, data: dados })
    res.json(vaga)
  } catch (err) { next(err) }
}

async function remover(req, res, next) {
  try {
    await prisma.vaga.delete({ where: { id: BigInt(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}

module.exports = { listar, buscarPorId, criar, atualizar, remover }
