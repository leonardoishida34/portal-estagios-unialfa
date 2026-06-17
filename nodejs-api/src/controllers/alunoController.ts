import { Request, Response, NextFunction } from 'express'
import { PrismaClient } from '@prisma/client'
import { z } from 'zod'
import bcryptjs from 'bcryptjs'

const prisma = new PrismaClient()

const alunoSchema = z.object({
  ra:    z.string().length(8, 'RA deve ter exatamente 8 caracteres'),
  nome:  z.string().min(3, 'Nome obrigatorio'),
  curso: z.string().min(3, 'Curso obrigatorio'),
  senha: z.string().optional(),
  apto:  z.boolean().default(true)
})

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const alunos = await prisma.aluno.findMany({
      include: { _count: { select: { candidaturas: true } } },
      orderBy: { nome: 'asc' }
    })
    res.json(alunos)
  } catch (err) { next(err) }
}

export async function buscarPorRa(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const aluno = await prisma.aluno.findUniqueOrThrow({
      where: { ra: req.params.ra },
      include: {
        candidaturas: {
          include: { vaga: { select: { titulo: true, ativa: true, empresa: { select: { razaoSocial: true } } } } }
        }
      }
    })
    res.json(aluno)
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = alunoSchema.parse(req.body)
    
    // Se a senha foi enviada, criptografa com bcrypt
    const dataParaCriar: any = {
      ra: dados.ra,
      nome: dados.nome,
      curso: dados.curso,
      apto: dados.apto
    }
    
    if (dados.senha) {
      dataParaCriar.senha = await bcryptjs.hash(dados.senha, 10)
    }
    
    const aluno = await prisma.aluno.create({ data: dataParaCriar })
    res.status(201).json(aluno)
  } catch (err) { next(err) }
}

export async function atualizar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const dados = alunoSchema.partial().parse(req.body)
    
    const dataParaAtualizar: any = {}
    if (dados.ra !== undefined) dataParaAtualizar.ra = dados.ra
    if (dados.nome !== undefined) dataParaAtualizar.nome = dados.nome
    if (dados.curso !== undefined) dataParaAtualizar.curso = dados.curso
    if (dados.apto !== undefined) dataParaAtualizar.apto = dados.apto
    
    if (dados.senha) {
      dataParaAtualizar.senha = await bcryptjs.hash(dados.senha, 10)
    }
    
    const aluno = await prisma.aluno.update({ where: { ra: req.params.ra }, data: dataParaAtualizar })
    res.json(aluno)
  } catch (err) { next(err) }
}

export async function login(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { ra, senha } = req.body

    if (!ra || !senha) {
      res.status(400).json({ erro: 'RA e senha são obrigatórios.' })
      return
    }

    // Busca o aluno pelo RA
    const aluno = await prisma.aluno.findUniqueOrThrow({
      where: { ra }
    })

    // Verifica se o aluno tem senha cadastrada
    if (!aluno.senha) {
      res.status(401).json({ erro: 'Aluno ainda não possui senha cadastrada. Faça o cadastro primeiro.' })
      return
    }

    // Compara a senha digitada com o hash armazenado
    const senhaValida = await bcryptjs.compare(senha, aluno.senha)

    if (!senhaValida) {
      res.status(401).json({ erro: 'RA ou senha incorretos.' })
      return
    }

    // Retorna os dados do aluno (sem a senha)
    res.json({
      id: aluno.ra,
      ra: aluno.ra,
      nome: aluno.nome,
      curso: aluno.curso,
      apto: aluno.apto,
      perfil: 'aluno'
    })
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await prisma.candidatura.deleteMany({ where: { alunoRa: req.params.ra } })
    await prisma.aluno.delete({ where: { ra: req.params.ra } })
    res.status(204).send()
  } catch (err) { next(err) }
}