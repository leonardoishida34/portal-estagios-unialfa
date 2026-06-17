const { PrismaClient } = require('@prisma/client')
const { z } = require('zod')
const prisma = new PrismaClient()

const candidaturaSchema = z.object({
  alunoRa: z.string().length(8, 'RA deve ter 8 caracteres'),
  vagaId:  z.number().int().positive('ID da vaga invalido')
})

const statusSchema = z.object({
  status: z.enum(['Pendente', 'Aprovada', 'Reprovada'])
})

async function listar(req, res, next) {
  try {
    const candidaturas = await prisma.candidatura.findMany({
      include: {
        aluno: { select: { ra: true, nome: true, curso: true, apto: true } },
        vaga:  { select: { id: true, titulo: true, empresa: { select: { razaoSocial: true } } } }
      },
      orderBy: { id: 'desc' }
    })
    res.json(candidaturas)
  } catch (err) { next(err) }
}

async function buscarPorId(req, res, next) {
  try {
    const candidatura = await prisma.candidatura.findUniqueOrThrow({
      where: { id: BigInt(req.params.id) },
      include: { aluno: true, vaga: { include: { empresa: true } } }
    })
    res.json(candidatura)
  } catch (err) { next(err) }
}

async function criar(req, res, next) {
  try {
    const dados = candidaturaSchema.parse(req.body)

    const aluno = await prisma.aluno.findUnique({ where: { ra: dados.alunoRa } })
    if (!aluno) return res.status(404).json({ error: 'Aluno nao encontrado' })
    if (!aluno.apto) return res.status(400).json({ error: 'Aluno nao esta apto para candidatura' })

    const vaga = await prisma.vaga.findUnique({ where: { id: BigInt(dados.vagaId) } })
    if (!vaga) return res.status(404).json({ error: 'Vaga nao encontrada' })
    if (!vaga.ativa) return res.status(400).json({ error: 'Vaga nao esta ativa' })

    const candidatura = await prisma.candidatura.create({
      data: { alunoRa: dados.alunoRa, vagaId: BigInt(dados.vagaId) },
      include: {
        aluno: { select: { nome: true } },
        vaga:  { select: { titulo: true, empresa: { select: { razaoSocial: true } } } }
      }
    })

    res.status(201).json({ mensagem: 'Candidatura enviada com sucesso!', candidatura })
  } catch (err) { next(err) }
}

async function atualizarStatus(req, res, next) {
  try {
    const { status } = statusSchema.parse(req.body)
    const candidatura = await prisma.candidatura.update({
      where: { id: BigInt(req.params.id) },
      data: { status }
    })
    res.json({ mensagem: `Status atualizado para ${status}`, candidatura })
  } catch (err) { next(err) }
}

async function remover(req, res, next) {
  try {
    await prisma.candidatura.delete({ where: { id: BigInt(req.params.id) } })
    res.status(204).send()
  } catch (err) { next(err) }
}

module.exports = { listar, buscarPorId, criar, atualizarStatus, remover }
