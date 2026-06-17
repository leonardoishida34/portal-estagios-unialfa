import { z } from 'zod'
import bcryptjs from 'bcryptjs'
import { prisma } from '../lib/prisma'

export const alunoSchema = z.object({
  ra:    z.string().min(6, 'RA deve ter no mínimo 6 caracteres'),
  nome:  z.string().min(3, 'Nome obrigatorio'),
  curso: z.string().min(3, 'Curso obrigatorio'),
  senha: z.string().optional(),
  apto:  z.boolean().default(true)
})

export async function listarAlunos() {
  return prisma.aluno.findMany({
    include: { _count: { select: { candidaturas: true } } },
    orderBy: { nome: 'asc' }
  })
}

export async function buscarAlunoPorRa(ra: string) {
  return prisma.aluno.findUniqueOrThrow({
    where: { ra },
    include: {
      candidaturas: {
        include: { vaga: { select: { titulo: true, ativa: true, empresa: { select: { razaoSocial: true } } } } }
      }
    }
  })
}

export async function criarAluno(body: unknown) {
  const dados = alunoSchema.parse(body)
  const dataParaCriar: Record<string, unknown> = {
    ra: dados.ra, nome: dados.nome, curso: dados.curso, apto: dados.apto
  }
  if (dados.senha) dataParaCriar.senha = await bcryptjs.hash(dados.senha, 10)
  return prisma.aluno.create({ data: dataParaCriar as any })
}

export async function atualizarAluno(ra: string, body: unknown) {
  const dados = alunoSchema.partial().parse(body)
  const dataParaAtualizar: Record<string, unknown> = {}
  if (dados.ra    !== undefined) dataParaAtualizar.ra    = dados.ra
  if (dados.nome  !== undefined) dataParaAtualizar.nome  = dados.nome
  if (dados.curso !== undefined) dataParaAtualizar.curso = dados.curso
  if (dados.apto  !== undefined) dataParaAtualizar.apto  = dados.apto
  if (dados.senha) dataParaAtualizar.senha = await bcryptjs.hash(dados.senha, 10)
  return prisma.aluno.update({ where: { ra }, data: dataParaAtualizar as any })
}

export async function loginAluno(ra: string, senha: string) {
  if (!ra || !senha) throw new Error('RA e senha são obrigatórios.')

  const aluno = await prisma.aluno.findUniqueOrThrow({ where: { ra } })

  if (!aluno.senha) throw new Error('Aluno ainda não possui senha cadastrada. Faça o cadastro primeiro.')

  const valida = await bcryptjs.compare(senha, aluno.senha)
  if (!valida) throw new Error('RA ou senha incorretos.')

  return { id: aluno.ra, ra: aluno.ra, nome: aluno.nome, curso: aluno.curso, apto: aluno.apto, perfil: 'aluno' }
}

export async function removerAluno(ra: string) {
  await prisma.candidatura.deleteMany({ where: { alunoRa: ra } })
  return prisma.aluno.delete({ where: { ra } })
}
