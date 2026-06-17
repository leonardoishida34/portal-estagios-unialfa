import { Request, Response, NextFunction } from 'express'
import {
  listarCandidaturas,
  buscarCandidaturaPorId,
  criarCandidatura,
  atualizarStatusCandidatura,
  removerCandidatura
} from '../services/candidaturaService'

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const vagaId  = req.query.vaga_id  ? Number(req.query.vaga_id)  : undefined
    const alunoRa = req.query.aluno_ra ? String(req.query.aluno_ra) : undefined
    res.json(await listarCandidaturas({ vagaId, alunoRa }))
  } catch (err) { next(err) }
}

export async function buscarPorId(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await buscarCandidaturaPorId(BigInt(req.params.id)))
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const resultado = await criarCandidatura(req.body)
    res.status(201).json(resultado)
  } catch (err: any) {
    if (err.statusCode) {
      res.status(err.statusCode).json({ error: err.message })
      return
    }
    next(err)
  }
}

export async function atualizarStatus(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await atualizarStatusCandidatura(BigInt(req.params.id), req.body))
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await removerCandidatura(BigInt(req.params.id))
    res.status(204).send()
  } catch (err) { next(err) }
}
