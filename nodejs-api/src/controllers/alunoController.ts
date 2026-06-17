import { Request, Response, NextFunction } from 'express'
import {
  listarAlunos,
  buscarAlunoPorRa,
  criarAluno,
  atualizarAluno,
  loginAluno,
  removerAluno
} from '../services/alunoService'

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await listarAlunos())
  } catch (err) { next(err) }
}

export async function buscarPorRa(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await buscarAlunoPorRa(req.params.ra))
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.status(201).json(await criarAluno(req.body))
  } catch (err) { next(err) }
}

export async function atualizar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await atualizarAluno(req.params.ra, req.body))
  } catch (err) { next(err) }
}

export async function login(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { ra, senha } = req.body
    res.json(await loginAluno(ra, senha))
  } catch (err: any) {
    res.status(401).json({ erro: err.message })
  }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await removerAluno(req.params.ra)
    res.status(204).send()
  } catch (err) { next(err) }
}
