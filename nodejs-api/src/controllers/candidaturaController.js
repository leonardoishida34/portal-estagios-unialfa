const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')

const prisma = new PrismaClient()

const candidaturaSchema = z.object({
  alunoId: z.number().int().positive('ID do aluno inválido'),
  vagaId: z.number().int().positive('ID da vaga inválido')
})

const statusSchema = z.object({
  status: z.enum(['PENDENTE', 'APROVADA', 'REPROVADA'])
})

async function listar(req, res, next) {
  try {
    const candidaturas = await prisma.candidatura.findMany({
      include: {
        aluno: { select: { id: true, nome: true, email: true, curso: true } },
        vaga: { select: { id: true, titulo: true, area: true, empresa: { select: { nome: true } } } }
      },
      orderBy: { createdAt: 'desc' }
    })
    res.json(candidaturas)
  } catch (err) { next(err) }
}

async function buscarPorId(req, res, next) {
  try {
    const candidatura = await prisma.candidatura.findUniqueOrThrow({
      where: { id: Number(req.params.id) },
      include: { aluno: true, vaga: { include: { empresa: true } } }
    })
    res.json(candidatura)
  } catch (err) { next(err) }
}

async function criar(req, res, next) {
  try {
    const dados = candidaturaSchema.parse(req.body)

    const vaga = await prisma.vaga.findUnique({ where: { id: dados.vagaId } })
    if (!vaga) return res.status(404).json({ error: 'Vaga não encontrada' })
    if (vaga.status === 'FECHADA') return res.status(400).json({ error: 'Vaga está fechada para candidaturas' })

    const candidatura = await prisma.candidatura.create({
      data: dados,
      include: {
        aluno: { select: { nome: true, email: true } },
        vaga: { select: { titulo: true, empresa: { select: { nome: true } } } }
      }
    })

    res.status(201).json({
      mensagem: 'Candidatura enviada com sucesso!',
      candidatura
    })
  } catch (err) { next(err) }
}

async function atualizarStatus(req, res, next) {
  try {
    const { status } = statusSchema.parse(req.body)
    const candidatura = await prisma.candidatura.update({
      where: { id: Number(req.params.id) },
      data: { status }
    })
    res.json({ mensagem: `Status atualizado para ${status}`, candidatura })
  } catch (err) { next(err) }
}

async function remover(req, res, next) {
  try {
    await prisma.candidatura.delete({ where: { id: Number(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}

module.exports = { listar, buscarPorId, criar, atualizarStatus, remover }
