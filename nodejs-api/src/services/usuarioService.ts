import { z } from 'zod'
import { prisma } from '../lib/prisma'

export const usuarioSchema = z.object({
  nome:   z.string().min(3, 'Nome obrigatorio'),
  login:  z.string().min(3, 'Login obrigatorio'),
  senha:  z.string().min(6, 'Senha deve ter no minimo 6 caracteres'),
  perfil: z.enum(['ADMIN', 'OPERADOR'])
})

export async function listarUsuarios() {
  return prisma.usuario.findMany({
    select: { id: true, nome: true, login: true, perfil: true },
    orderBy: { nome: 'asc' }
  })
}

export async function loginUsuario(login: string, senha: string) {
  const usuario = await prisma.usuario.findFirst({
    where: { login, senha },
    select: { id: true, nome: true, login: true, perfil: true }
  })
  if (!usuario) throw new Error('Login ou senha invalidos')
  return { mensagem: 'Login realizado com sucesso', usuario }
}

export async function criarUsuario(body: unknown) {
  const dados = usuarioSchema.parse(body)
  return prisma.usuario.create({
    data: dados,
    select: { id: true, nome: true, login: true, perfil: true }
  })
}

export async function removerUsuario(id: bigint) {
  return prisma.usuario.delete({ where: { id } })
}
