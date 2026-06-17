import { z } from 'zod'
import bcryptjs from 'bcryptjs'
import { prisma } from '../lib/prisma'

export const empresaSchema = z.object({
  razaoSocial: z.string().min(2, 'Razao social obrigatoria'),
  cnpj:        z.string().min(14, 'CNPJ invalido'),
  email:       z.string().email('E-mail invalido'),
  telefone:    z.string().optional(),
  senha:       z.string().min(6, 'Senha deve ter no minimo 6 caracteres').optional(),
  aprovada:    z.boolean().default(false)
})

export async function listarEmpresas(aprovada?: string) {
  return prisma.empresa.findMany({
    where: { ...(aprovada !== undefined && { aprovada: aprovada === 'true' }) },
    include: { _count: { select: { vagas: true } } },
    orderBy: { razaoSocial: 'asc' }
  })
}

export async function buscarEmpresaPorId(id: bigint) {
  return prisma.empresa.findUniqueOrThrow({
    where: { id },
    include: { vagas: { select: { id: true, titulo: true, ativa: true, bolsa: true } } }
  })
}

export async function criarEmpresa(body: unknown) {
  const dados = empresaSchema.parse(body)
  const senhaHash = dados.senha ? await bcryptjs.hash(dados.senha, 10) : undefined
  return prisma.empresa.create({
    data: {
      razaoSocial: dados.razaoSocial,
      cnpj:        dados.cnpj,
      email:       dados.email,
      telefone:    dados.telefone,
      senha:       senhaHash,
      aprovada:    dados.aprovada
    }
  })
}

export async function atualizarEmpresa(id: bigint, body: unknown) {
  const dados = empresaSchema.partial().parse(body)
  const senhaHash = dados.senha ? await bcryptjs.hash(dados.senha, 10) : undefined
  return prisma.empresa.update({
    where: { id },
    data: {
      ...(dados.razaoSocial !== undefined && { razaoSocial: dados.razaoSocial }),
      ...(dados.cnpj        !== undefined && { cnpj: dados.cnpj }),
      ...(dados.email       !== undefined && { email: dados.email }),
      ...(dados.telefone    !== undefined && { telefone: dados.telefone }),
      ...(dados.aprovada    !== undefined && { aprovada: dados.aprovada }),
      ...(senhaHash         !== undefined && { senha: senhaHash })
    }
  })
}

export async function removerEmpresa(id: bigint) {
  return prisma.empresa.delete({ where: { id } })
}

export async function loginEmpresa(email: string, senha: string) {
  const empresa = await prisma.empresa.findFirst({ where: { email } })
  if (!empresa) throw new Error('E-mail ou senha incorretos.')

  if (!empresa.senha) throw new Error('Empresa sem senha cadastrada. Entre em contato com a UniALFA.')

  const valida = await bcryptjs.compare(senha, empresa.senha)
  if (!valida) throw new Error('E-mail ou senha incorretos.')

  if (!empresa.aprovada) throw new Error('Empresa ainda não aprovada pela UniALFA. Aguarde a aprovação.')

  return {
    id:          Number(empresa.id),
    nome:        empresa.razaoSocial,
    email:       empresa.email,
    cnpj:        empresa.cnpj,
    telefone:    empresa.telefone,
    perfil:      'empresa'
  }
}
