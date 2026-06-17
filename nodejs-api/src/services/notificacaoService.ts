import { prisma } from '../lib/prisma'

export async function listarNotificacoes(alunoRa: string) {
  return prisma.notificacao.findMany({
    where: { alunoRa },
    orderBy: { criadaEm: 'desc' }
  })
}

export async function contarNaoLidas(alunoRa: string) {
  return prisma.notificacao.count({ where: { alunoRa, lida: false } })
}

export async function marcarComoLida(id: bigint) {
  return prisma.notificacao.update({ where: { id }, data: { lida: true } })
}

export async function marcarTodasComoLidas(alunoRa: string) {
  return prisma.notificacao.updateMany({ where: { alunoRa, lida: false }, data: { lida: true } })
}

export async function criarNotificacao(alunoRa: string, mensagem: string) {
  return prisma.notificacao.create({ data: { alunoRa, mensagem } })
}
